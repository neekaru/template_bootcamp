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
(() => {
  /* ---------- constants ---------- */
  const DARK_ATTR = 'data-theme';
  const DARK_VAL  = 'dark';
  const DARK_MARK = 'dark:';

  /* ---------- tiny helpers ---------- */
  const getCls = el => el.getAttribute('class') || '';
  const setCls = (el, v) => el.setAttribute('class', v);
  const isDark = () => document.documentElement.getAttribute(DARK_ATTR) === DARK_VAL;
  const hasDark = el => getCls(el).includes(DARK_MARK);

  /* ---------- prefix helper ---------- */
  const prefixFor = t => {
    if (t.startsWith('bg-gradient-to-')) return 'bg-gradient-to-'; // gradients
    if (t.startsWith('bg-') && t.includes('/')) return 'bg-';      // bg‑colour/opacity
    const i = t.indexOf('-');
    return i === -1 ? t : t.slice(0, i + 1);
  };

  /* ---------- one‑time snapshot builder ---------- */
  const bakeSnapshots = el => {
    if (el.dataset.darkClasses) return;               // already processed

    const light = getCls(el);
    if (!light.includes(DARK_MARK)) return;           // nothing to do

    const tokens = light.trim().split(/\s+/);
    const wantKill = Object.create(null);
    const toAdd = [];

    // pass 1 – gather dark info
    for (const t of tokens) {
      if (!t.startsWith(DARK_MARK)) continue;
      const base = t.slice(DARK_MARK.length);
      if (!base) continue;
      toAdd.push(base);
      wantKill[prefixFor(base)] = false;
    }
    if (!Object.keys(wantKill).length) return;

    // pass 2 – choose ONE light token per prefix (right→left)
    const killIdx = new Set();
    for (let i = tokens.length - 1; i >= 0; i--) {
      const t = tokens[i];
      if (t.startsWith(DARK_MARK) || t.includes(':')) continue;
      const p = prefixFor(t);
      if (p in wantKill && wantKill[p] === false) {
        killIdx.add(i);
        wantKill[p] = true;
        if (Object.values(wantKill).every(Boolean)) break;
      }
    }

    // build dark snapshot
    const final = [];
    for (let i = 0; i < tokens.length; i++) {
      if (killIdx.has(i) || tokens[i].startsWith(DARK_MARK)) continue;
      final.push(tokens[i]);
    }
    for (const d of toAdd) if (!final.includes(d)) final.push(d);

    el.dataset.lightClasses = light;
    el.dataset.darkClasses  = final.join(' ');
  };

  /* ---------- theme applicator ---------- */
  const applyCurrentTheme = el => {
    if (isDark()) {
      if (el.dataset.darkClasses) setCls(el, el.dataset.darkClasses);
    } else if (el.dataset.lightClasses) {
      setCls(el, el.dataset.lightClasses);
    }
  };

  /* ---------- global DOM observer ---------- */
  const bodyObs = new MutationObserver(muts => {
    for (const m of muts) {
      if (m.type === 'attributes') {
        if (hasDark(m.target)) { bakeSnapshots(m.target); applyCurrentTheme(m.target); }
      } else {
        m.addedNodes.forEach(node => {
          if (node.nodeType !== 1) return;
          if (hasDark(node)) { bakeSnapshots(node); applyCurrentTheme(node); }
          node.querySelectorAll('[class*="dark:"]').forEach(el => { bakeSnapshots(el); applyCurrentTheme(el); });
        });
      }
    }
  });

  /* ---------- root theme‑flip observer ---------- */
  new MutationObserver(() => {
    // wait a frame to debounce rapid flips
    requestAnimationFrame(() => {
      document.querySelectorAll('[data-dark-classes],[data-light-classes]')
              .forEach(applyCurrentTheme);
    });
  }).observe(document.documentElement, { attributes: true, attributeFilter: [DARK_ATTR] });

  /* ---------- bootstrap ---------- */
  document.querySelectorAll('[class*="dark:"]').forEach(el => bakeSnapshots(el));
  bodyObs.observe(document.body, { subtree: true, childList: true, attributes: true, attributeFilter: ['class'] });
  document.querySelectorAll('[data-dark-classes],[data-light-classes]').forEach(applyCurrentTheme);
})();