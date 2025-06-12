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

(() => {
  /* ------------ constants & helpers ------------ */
  const DARK_ATTR = 'data-theme';
  const DARK_VAL  = 'dark';
  const DARK_MARK = 'dark:';
  const isDark    = () =>
    document.documentElement.getAttribute(DARK_ATTR) === DARK_VAL;

  // Cache <el> → [lightClass, darkClass]
  const snapshots = new WeakMap();

  const prefixFor = t => {
    if (t.startsWith('bg-gradient-to-')) return 'bg-gradient-to-';
    if (t.startsWith('bg-') && t.includes('/')) return 'bg-';
    const i = t.indexOf('-');
    return i === -1 ? t : t.slice(0, i + 1);
  };

  /* ------------ snapshot builder ------------ */
  const snap = el => {
    if (snapshots.has(el)) return;

    const light = el.className;
    if (!light.includes(DARK_MARK)) return;

    const toks     = light.trim().split(/\s+/);
    const toAdd    = [];              // tokens to add in dark mode
    const killByPx = Object.create(null); // prefix → should kill?

    // pass 1 – record dark tokens & prefixes
    for (const t of toks) {
      if (!t.startsWith(DARK_MARK)) continue;
      const base = t.slice(DARK_MARK.length);
      if (!base) continue;
      toAdd.push(base);
      killByPx[prefixFor(base)] = false;
    }
    if (!Object.keys(killByPx).length) return;

    // pass 2 – decide which *light* tokens to drop (right → left)
    const killIdx = new Set();
    for (let i = toks.length - 1; i >= 0; i--) {
      const t = toks[i];
      if (t.startsWith(DARK_MARK) || t.includes(':')) continue;
      const p = prefixFor(t);
      if (p in killByPx && killByPx[p] === false) {
        killIdx.add(i);
        killByPx[p] = true;
        if (Object.values(killByPx).every(Boolean)) break;
      }
    }

    // build dark snapshot
    const dark = [];
    for (let i = 0; i < toks.length; i++) {
      if (killIdx.has(i) || toks[i].startsWith(DARK_MARK)) continue;
      dark.push(toks[i]);
    }
    for (const d of toAdd) if (!dark.includes(d)) dark.push(d);

    snapshots.set(el, [light, dark.join(' ')]);
  };

  /* ------------ apply theme to all cached els ------------ */
  const applyTheme = () => {
    const darkOn = isDark();
    snapshots.forEach(([light, dark], el) => {
      el.className = darkOn ? dark : light;
    });
  };

  /* ------------ scan a subtree once ------------ */
  const scan = root => {
    root.querySelectorAll('[class*="dark:"]').forEach(el => {
      snap(el);                         // build snapshot only once
      const [light, dark] = snapshots.get(el) || [];
      if (dark) el.className = isDark() ? dark : light;
    });
  };

  /* ------------ observers ------------ */
  // 1) watch only for *inserted* nodes that might contain dark: tokens
  new MutationObserver(muts => {
    let queued = false;
    muts.forEach(m => {
      m.addedNodes.forEach(n => {
        if (n.nodeType === 1) {
          if (n.className && n.className.includes(DARK_MARK)) snap(n);
          scan(n);                       // deep scan once
          queued = true;
        }
      });
    });
    if (queued) requestAnimationFrame(applyTheme);
  }).observe(document.body, { subtree: true, childList: true });

  // 2) watch the root for actual theme flips
  new MutationObserver(() => requestAnimationFrame(applyTheme))
    .observe(document.documentElement, {
      attributes: true,
      attributeFilter: [DARK_ATTR]
    });

  /* ------------ bootstrap ------------ */
  scan(document);        // initial page load
  applyTheme();          // pick the right snapshot
})();
