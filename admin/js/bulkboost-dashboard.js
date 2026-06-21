/**
 * BulkBoost admin dashboard runtime.
 *
 * Hydrates the static control markup from saved settings, binds every control
 * to a live in-memory state, renders the storefront live preview on each
 * change, and persists via AJAX with dirty-tracking + discard.
 *
 * Config arrives via wp_localize_script as `BulkBoostDash`:
 *   { ajaxUrl, nonce, currency, settings:{snake_keys}, defaults:{snake_keys},
 *     product:{name, sub} }
 */
(function () {
    'use strict';

    var CFG = window.BulkBoostDash || {};
    var root = document.querySelector('.bb-admin[data-bb-dashboard]');
    if (!root) { return; }

    // state-key (camel) -> option-key (snake)
    var KEYMAP = {
        activeBg: 'background_color_active',
        activeText: 'text_color_active',
        accent: 'accent_color',
        inactiveBorder: 'border_color_inactive',
        radius: 'box_corner_radius',
        borderW: 'border_width',
        gap: 'card_gap',
        selector: 'selector_style',
        labelWeight: 'label_font_weight', labelSize: 'label_font_size',
        descWeight: 'description_font_weight', descSize: 'description_font_size',
        priceWeight: 'price_font_weight', priceSize: 'price_font_size',
        showOld: 'show_old_price',
        oldWeight: 'old_price_font_weight', oldSize: 'old_price_font_size',
        badgeOn: 'badge_enabled', badgeText: 'badge_text',
        badgeBg: 'badge_bg_color', badgeColor: 'badge_text_color',
        badgePos: 'badge_position', badgeTarget: 'badge_target'
    };
    var BOOLS = { showOld: 1, badgeOn: 1 };       // stored as yes/no
    var NUMS = {
        radius: 1, borderW: 1, gap: 1, labelSize: 1, descSize: 1,
        priceSize: 1, oldSize: 1
    };

    var cur = CFG.currency || '$';

    function fromOptions(opts) {
        var st = {};
        Object.keys(KEYMAP).forEach(function (camel) {
            var raw = opts[KEYMAP[camel]];
            if (BOOLS[camel]) { st[camel] = (raw === 'yes' || raw === true || raw === '1'); }
            else if (NUMS[camel]) { st[camel] = Number(raw); if (isNaN(st[camel])) st[camel] = 0; }
            else { st[camel] = (raw == null ? '' : String(raw)); }
        });
        st.selected = 1;            // preview-only
        return st;
    }
    function toOptions(st) {
        var opts = {};
        Object.keys(KEYMAP).forEach(function (camel) {
            var v = st[camel];
            if (BOOLS[camel]) { v = v ? 'yes' : 'no'; }
            opts[KEYMAP[camel]] = v;
        });
        return opts;
    }
    function clone(o) { return JSON.parse(JSON.stringify(o)); }

    var defaults = fromOptions(CFG.defaults || {});
    var state = fromOptions(Object.assign({}, CFG.defaults || {}, CFG.settings || {}));
    var lastSaved = clone(state);
    var saved = true;

    function hexA(hex, a) {
        var h = String(hex || '#000000').replace('#', '');
        if (h.length === 3) { h = h.split('').map(function (c) { return c + c; }).join(''); }
        var n = parseInt(h, 16);
        return 'rgba(' + ((n >> 16) & 255) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + a + ')';
    }
    function safeHex(v) { return /^#[0-9a-fA-F]{6}$/.test(v) ? v : '#000000'; }

    // Keep the preset swatches, native color input, and hex field in sync for one key.
    function syncColor(key, exceptEl) {
        root.querySelectorAll('.bb-swatch[data-key="' + key + '"]').forEach(function (s) {
            s.classList.toggle('is-selected', String(s.dataset.val).toLowerCase() === String(state[key]).toLowerCase());
        });
        root.querySelectorAll('.bb-color-input[data-key="' + key + '"]').forEach(function (c) {
            if (c !== exceptEl) { c.value = safeHex(state[key]); }
        });
        root.querySelectorAll('.bb-hex-input[data-key="' + key + '"]').forEach(function (h) {
            if (h !== exceptEl) { h.value = state[key]; }
        });
    }

    /* ---------- status / dirty ---------- */
    function markDirty() {
        saved = false;
        renderStatus();
    }
    function renderStatus() {
        var el = root.querySelector('[data-bb-status]');
        if (el) {
            el.className = 'bb-status ' + (saved ? 'is-saved' : 'is-dirty');
            el.innerHTML = '<span class="dot"></span>' + (saved ? 'All changes saved' : 'Unsaved changes');
        }
        var saveBtn = root.querySelector('[data-bb-save]');
        var discardBtn = root.querySelector('[data-bb-discard]');
        if (saveBtn) { saveBtn.disabled = saved; }
        if (discardBtn) { discardBtn.disabled = saved; }
    }

    /* ---------- hydrate controls from state ---------- */
    function hydrate() {
        // swatches
        root.querySelectorAll('.bb-swatch[data-key]').forEach(function (b) {
            b.classList.toggle('is-selected', state[b.dataset.key] === b.dataset.val);
        });
        // segmented
        root.querySelectorAll('.bb-seg-opt[data-key]').forEach(function (b) {
            b.classList.toggle('is-active', String(state[b.dataset.key]) === b.dataset.val);
        });
        // toggles
        root.querySelectorAll('.bb-toggle[data-key]').forEach(function (b) {
            b.classList.toggle('is-on', !!state[b.dataset.key]);
        });
        // ranges
        root.querySelectorAll('input[type=range][data-key]').forEach(function (inp) {
            inp.value = state[inp.dataset.key];
            var lbl = inp.parentNode.querySelector('.val');
            if (lbl) { lbl.textContent = state[inp.dataset.key] + 'px'; }
        });
        // selects
        root.querySelectorAll('select[data-key]').forEach(function (s) { s.value = state[s.dataset.key]; });
        // numbers + text (incl. hex fields, which are type=text)
        root.querySelectorAll('input[type=number][data-key], input[type=text][data-key]').forEach(function (i) {
            i.value = state[i.dataset.key];
        });
        // native color inputs
        root.querySelectorAll('.bb-color-input[data-key]').forEach(function (c) {
            c.value = safeHex(state[c.dataset.key]);
        });
        // conditional dimming
        root.querySelectorAll('[data-dim-key]').forEach(function (el) {
            el.classList.toggle('bb-dim', !state[el.dataset.dimKey]);
        });
        // accent-driven CSS var (sliders, etc.)
        root.style.setProperty('--bb-accent', state.accent || '#10976a');
        renderStatus();
    }

    /* ---------- bind ---------- */
    function setVal(key, val) { state[key] = val; markDirty(); renderPreview(); }

    function bind() {
        root.querySelectorAll('.bb-swatch[data-key]').forEach(function (b) {
            b.addEventListener('click', function () {
                var k = b.dataset.key;
                setVal(k, b.dataset.val);
                syncColor(k);
                if (k === 'accent') { root.style.setProperty('--bb-accent', b.dataset.val); }
            });
        });
        // native color picker
        root.querySelectorAll('.bb-color-input[data-key]').forEach(function (c) {
            c.addEventListener('input', function () {
                var k = c.dataset.key;
                state[k] = c.value; markDirty();
                syncColor(k, c);
                if (k === 'accent') { root.style.setProperty('--bb-accent', c.value); }
                renderPreview();
            });
        });
        // hex text field (only applies a valid 6-digit hex)
        root.querySelectorAll('.bb-hex-input[data-key]').forEach(function (h) {
            h.addEventListener('input', function () {
                var v = h.value.trim();
                if (!/^#?[0-9a-fA-F]{6}$/.test(v)) { return; }
                if (v.charAt(0) !== '#') { v = '#' + v; }
                var k = h.dataset.key;
                state[k] = v.toLowerCase(); markDirty();
                syncColor(k, h);
                if (k === 'accent') { root.style.setProperty('--bb-accent', state[k]); }
                renderPreview();
            });
        });
        root.querySelectorAll('.bb-seg-opt[data-key]').forEach(function (b) {
            b.addEventListener('click', function () {
                var k = b.dataset.key;
                setVal(k, b.dataset.val);
                root.querySelectorAll('.bb-seg-opt[data-key="' + k + '"]').forEach(function (s) {
                    s.classList.toggle('is-active', s === b);
                });
            });
        });
        root.querySelectorAll('.bb-toggle[data-key]').forEach(function (b) {
            b.addEventListener('click', function () {
                var k = b.dataset.key, v = !state[k];
                setVal(k, v);
                b.classList.toggle('is-on', v);
                root.querySelectorAll('[data-dim-key="' + k + '"]').forEach(function (el) {
                    el.classList.toggle('bb-dim', !v);
                });
            });
        });
        root.querySelectorAll('input[type=range][data-key]').forEach(function (inp) {
            inp.addEventListener('input', function () {
                var v = Number(inp.value);
                state[inp.dataset.key] = v; markDirty();
                var lbl = inp.parentNode.querySelector('.val');
                if (lbl) { lbl.textContent = v + 'px'; }
                renderPreview();
            });
        });
        root.querySelectorAll('select[data-key]').forEach(function (s) {
            s.addEventListener('change', function () { setVal(s.dataset.key, s.value); });
        });
        root.querySelectorAll('input[type=number][data-key]').forEach(function (i) {
            i.addEventListener('input', function () { setVal(i.dataset.key, Number(i.value) || 0); });
        });
        root.querySelectorAll('input[type=text][data-key]:not(.bb-hex-input)').forEach(function (i) {
            i.addEventListener('input', function () { setVal(i.dataset.key, i.value); });
        });
        // tabs (UI only)
        root.querySelectorAll('.bb-tab[data-tab]').forEach(function (t) {
            t.addEventListener('click', function () {
                root.querySelectorAll('.bb-tab').forEach(function (x) { x.classList.toggle('is-active', x === t); });
                root.querySelectorAll('[data-tab-panel]').forEach(function (p) {
                    p.style.display = (p.dataset.tabPanel === t.dataset.tab) ? '' : 'none';
                });
            });
        });
        // save / discard
        var saveBtn = root.querySelector('[data-bb-save]');
        if (saveBtn) { saveBtn.addEventListener('click', save); }
        var discardBtn = root.querySelector('[data-bb-discard]');
        if (discardBtn) {
            discardBtn.addEventListener('click', function () {
                state = clone(lastSaved); saved = true; hydrate(); renderPreview();
            });
        }
    }

    /* ---------- live preview ---------- */
    var BASE = [
        { label: '1 item', desc: 'Get 1 item and enjoy our product', price: 100, old: 120 },
        { label: '2 items', desc: 'Get 2 for a better price — only today', price: 150, old: 200 },
        { label: '3 items', desc: 'Best value — stock up and save big', price: 210, old: 300 }
    ];
    function money(n) { return cur + n; }

    function renderPreview() {
        var s = state;
        var wrap = root.querySelector('[data-bb-offers]');
        if (!wrap) { return; }
        wrap.style.gap = s.gap + 'px';

        wrap.innerHTML = '';
        BASE.forEach(function (o, i) {
            var active = i === s.selected;
            var tCol = active ? s.activeText : '#1b1c18';
            var dCol = active ? hexA(s.activeText, 0.68) : '#7a7c71';
            var oCol = active ? hexA(s.activeText, 0.5) : '#a6a89d';

            var showBadge = false;
            if (s.badgeOn) {
                if (s.badgeTarget === 'all') showBadge = true;
                else if (s.badgeTarget === 'active') showBadge = active;
                else if (s.badgeTarget === 'best') showBadge = (i === BASE.length - 1);
            }

            var card = document.createElement('div');
            card.className = 'bb-offer';
            card.style.borderRadius = s.radius + 'px';
            card.style.border = s.borderW + 'px solid ' + (active ? s.accent : s.inactiveBorder);
            card.style.background = active ? s.activeBg : '#ffffff';
            card.style.boxShadow = active
                ? '0 10px 26px -14px ' + hexA(s.accent, 0.55)
                : '0 1px 2px rgba(20,20,15,.04)';
            card.addEventListener('click', function () {
                s.selected = i; renderPreview();
            });

            // badge
            if (showBadge) {
                var badge = document.createElement('div');
                badge.textContent = s.badgeText;
                var bs = badge.style;
                bs.position = 'absolute'; bs.fontSize = '10px'; bs.fontWeight = '700';
                bs.letterSpacing = '.06em'; bs.textTransform = 'uppercase';
                bs.background = s.badgeBg; bs.color = s.badgeColor; bs.whiteSpace = 'nowrap';
                bs.boxShadow = '0 3px 8px -2px rgba(0,0,0,.3)';
                if (s.badgePos === 'ribbon') {
                    bs.top = '0'; bs.left = '0'; bs.padding = '4px 12px';
                    bs.borderRadius = s.radius + 'px 0 10px 0';
                } else if (s.badgePos === 'left') {
                    bs.top = '-9px'; bs.left = '16px'; bs.padding = '4px 9px'; bs.borderRadius = '999px';
                } else {
                    bs.top = '-9px'; bs.right = '16px'; bs.padding = '4px 9px'; bs.borderRadius = '999px';
                }
                card.appendChild(badge);
            }

            // left
            var left = document.createElement('div');
            left.className = 'bb-offer-left';
            var outer = document.createElement('div');
            outer.className = 'bb-radio-outer';
            outer.style.borderRadius = s.selector === 'checkbox' ? '6px' : '50%';
            outer.style.borderColor = active ? s.activeText : '#cfd0c8';
            outer.style.display = s.selector === 'none' ? 'none' : 'flex';
            var inner = document.createElement('div');
            inner.className = 'bb-radio-inner';
            inner.style.borderRadius = s.selector === 'checkbox' ? '2px' : '50%';
            inner.style.background = active ? s.activeText : 'transparent';
            inner.style.transform = active ? 'scale(1)' : 'scale(0)';
            outer.appendChild(inner); left.appendChild(outer);

            var txt = document.createElement('div'); txt.style.minWidth = '0';
            var lab = document.createElement('div');
            lab.textContent = o.label;
            lab.style.cssText = 'line-height:1.2;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;';
            lab.style.fontWeight = s.labelWeight; lab.style.fontSize = s.labelSize + 'px'; lab.style.color = tCol;
            var des = document.createElement('div');
            des.textContent = o.desc;
            des.style.cssText = 'margin-top:3px;line-height:1.3;';
            des.style.fontWeight = s.descWeight; des.style.fontSize = s.descSize + 'px'; des.style.color = dCol;
            txt.appendChild(lab); txt.appendChild(des); left.appendChild(txt);
            card.appendChild(left);

            // right
            var right = document.createElement('div');
            right.className = 'bb-offer-right';
            if (s.showOld && o.old) {
                var old = document.createElement('div');
                old.textContent = money(o.old);
                old.style.cssText = 'text-decoration:line-through;margin-bottom:2px;';
                old.style.fontWeight = s.oldWeight; old.style.fontSize = s.oldSize + 'px'; old.style.color = oCol;
                right.appendChild(old);
            }
            var price = document.createElement('div');
            price.textContent = money(o.price);
            price.style.lineHeight = '1.1';
            price.style.fontWeight = s.priceWeight; price.style.fontSize = s.priceSize + 'px'; price.style.color = tCol;
            right.appendChild(price);
            card.appendChild(right);

            wrap.appendChild(card);
        });
    }

    /* ---------- save ---------- */
    function save() {
        var saveBtn = root.querySelector('[data-bb-save]');
        if (saveBtn) { saveBtn.disabled = true; saveBtn.textContent = 'Saving…'; }

        var opts = toOptions(state);
        var body = new URLSearchParams();
        body.append('action', 'bulkboost_save_design');
        body.append('nonce', CFG.nonce || '');
        Object.keys(opts).forEach(function (k) { body.append('settings[' + k + ']', opts[k]); });

        fetch(CFG.ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
            body: body.toString()
        }).then(function (r) { return r.json(); }).then(function (res) {
            if (saveBtn) { saveBtn.textContent = 'Save Changes'; }
            if (res && res.success) {
                lastSaved = clone(state); saved = true; renderStatus();
            } else {
                saved = false; renderStatus();
                window.alert((res && res.data && res.data.message) || 'Could not save settings.');
            }
        }).catch(function () {
            if (saveBtn) { saveBtn.textContent = 'Save Changes'; }
            saved = false; renderStatus();
            window.alert('Network error while saving.');
        });
    }

    /* ---------- init ---------- */
    hydrate();
    bind();
    renderPreview();
})();
