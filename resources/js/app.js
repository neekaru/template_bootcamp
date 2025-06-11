import './bootstrap';
import * as FilePond from 'filepond';
import 'filepond/dist/filepond.css';
import GLightbox from 'glightbox';
import 'glightbox/dist/css/glightbox.css';

// Make GLightbox available globally
window.GLightbox = GLightbox;

// Stagewise toolbar integration (development only)
if (import.meta.env && import.meta.env.DEV) {
    import('@stagewise/toolbar').then(({ initToolbar }) => {
        initToolbar({ plugins: [] });
    });
}

/* ---------- helpers (unchanged) ---------- */
const getClass = el =>
  typeof el.className === 'string'
    ? el.className
    : (el.className && el.className.baseVal) || '';

const setClass = (el, value) => {
  if (typeof el.className === 'string') el.className = value;
  else if (el.className && 'baseVal' in el.className) el.className.baseVal = value;
  else el.setAttribute('class', value);
};

const isDarkTheme = () =>
  document.documentElement.getAttribute('data-theme') === 'dark';

const hasDark = el => getClass(el).includes('dark:');

/* ---------- restore helper ---------- */
const restoreLight = el => {
  const snap = el.dataset.lightClasses;
  if (!snap) return;
  setClass(el, snap);
  delete el.dataset.lightClasses;
};

/* ---------- core swapper (always derived from stored light snapshot) ---------- */
const processDarkClasses = el => {
  if (!isDarkTheme()) return;                    // do nothing in light mode

  /* 1. get the light snapshot (or take one) */
  const lightSnapshot =
    el.dataset.lightClasses || (el.dataset.lightClasses = getClass(el));

  if (lightSnapshot.indexOf('dark:') === -1) return; // no dark: token → nothing

  const tokens = lightSnapshot.trim().split(/\s+/);  // one allocation
  const wantKill = Object.create(null);              // prefix → false|idx
  const toAdd    = [];                               // stripped dark classes

  /* pass 1 – collect dark info */
  for (const t of tokens) {
    if (!t.startsWith('dark:')) continue;
    const base = t.slice(5);
    if (!base) continue;

    toAdd.push(base);
    const dash = base.indexOf('-');
    if (dash !== -1) wantKill[base.slice(0, dash + 1)] = false;
  }
  if (!Object.keys(wantKill).length) return;

  /* pass 2 – right→left pick ONE light token per prefix */
  const killIdx = new Set();
  for (let i = tokens.length - 1; i >= 0; i--) {
    const t = tokens[i];
    if (t.startsWith('dark:') || t.includes(':')) continue;
    const dash = t.indexOf('-');
    if (dash === -1) continue;

    const p = t.slice(0, dash + 1);
    if (p in wantKill && wantKill[p] === false) {
      killIdx.add(i);
      wantKill[p] = true;
      if (Object.values(wantKill).every(Boolean)) break;
    }
  }

  /* build dark class list */
  const final = [];
  for (let i = 0; i < tokens.length; i++) {
    const tok = tokens[i];
    if (tok.startsWith('dark:') || killIdx.has(i)) continue;
    final.push(tok);
  }
  for (const d of toAdd) if (!final.includes(d)) final.push(d);

  setClass(el, final.join(' '));
};

/* ---------- micro-task batching (unchanged) ---------- */
let pending = new Set(), scheduled = false;
const schedule = () => {
  if (scheduled || !isDarkTheme()) return;
  scheduled = true;
  queueMicrotask(() => {
    pending.forEach(processDarkClasses);
    pending.clear();
    scheduled = false;
  });
};

/* ---------- body observer (unchanged) ---------- */
const bodyObs = new MutationObserver(muts => {
  if (!isDarkTheme()) return;
  for (const m of muts) {
    if (m.type === 'attributes') {
      if (hasDark(m.target)) pending.add(m.target);
    } else {
      m.addedNodes.forEach(node => {
        if (node.nodeType !== 1) return;
        if (hasDark(node)) pending.add(node);
        node.querySelectorAll('[class*="dark:"]').forEach(el => pending.add(el));
      });
    }
  }
  if (pending.size) schedule();
});

const startBodyObs = () => bodyObs.observe(document.body, {
  subtree: true, childList: true,
  attributes: true, attributeFilter: ['class']
});
const stopBodyObs = () => bodyObs.disconnect();

/* ---------- theme observer ---------- */
new MutationObserver(() => {
  /* use a micro-task to see the *settled* theme value after rapid flips */
  queueMicrotask(() => {
    if (isDarkTheme()) {
      document.querySelectorAll('[class*="dark:"]').forEach(processDarkClasses);
      startBodyObs();
    } else {
      stopBodyObs();
      document.querySelectorAll('[data-light-classes]').forEach(restoreLight);
    }
  });
}).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

/* ---------- bootstrap ---------- */
if (isDarkTheme()) {
  document.querySelectorAll('[class*="dark:"]').forEach(processDarkClasses);
  startBodyObs();
}
