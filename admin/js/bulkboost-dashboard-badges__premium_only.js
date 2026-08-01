/**
 * BulkBoost Pro — badge styling for the Design Settings dashboard.
 *
 * Registers the badge state keys (so the Badge tab controls hydrate/save) and
 * the window.bulkboostDashboardExtras hooks the core dashboard script calls when
 * present. Loaded BEFORE bulkboost-dashboard.js so the keymap is registered in
 * time. This whole file is excluded from the Free build (the __premium_only
 * filename suffix) and is enqueued from a Freemius premium-only PHP block.
 */
(function () {
    'use strict';

    // Extra state-key (camel) -> option-key (snake) pairs merged into the core keymap.
    window.bulkboostDashboardExtraKeymap = {
        labelHotBg: 'label_hot_bg', labelHotText: 'label_hot_text',
        labelPopularBg: 'label_popular_bg', labelPopularText: 'label_popular_text',
        labelBestdealBg: 'label_bestdeal_bg', labelBestdealText: 'label_bestdeal_text',
        saveBadgeBg: 'save_badge_bg', saveBadgeText: 'save_badge_text',
        shippingBadgeBg: 'shipping_badge_bg', shippingBadgeText: 'shipping_badge_text'
    };

    window.bulkboostDashboardExtras = {
        // Live sample chips in the Badge tab, one per badge type.
        chips: function (root, s) {
            var map = {
                hot: ['labelHotBg', 'labelHotText'],
                popular: ['labelPopularBg', 'labelPopularText'],
                bestdeal: ['labelBestdealBg', 'labelBestdealText'],
                save: ['saveBadgeBg', 'saveBadgeText'],
                shipping: ['shippingBadgeBg', 'shippingBadgeText']
            };
            root.querySelectorAll('[data-badge-chip]').forEach(function (el) {
                var keys = map[el.dataset.badgeChip];
                if (!keys) { return; }
                el.style.background = s[keys[0]];
                el.style.color = s[keys[1]];
            });
        },

        // Per-tier label badge (HOT / MOST POPULAR / BEST DEAL) overlapping the card.
        label: function (card, i, s) {
            var labelText = ['HOT', 'MOST POPULAR', 'BEST DEAL'][i];
            var labelKeys = [
                ['labelHotBg', 'labelHotText'],
                ['labelPopularBg', 'labelPopularText'],
                ['labelBestdealBg', 'labelBestdealText']
            ][i];
            if (!labelText) { return; }
            var badge = document.createElement('div');
            badge.textContent = labelText;
            var bs = badge.style;
            bs.position = 'absolute'; bs.top = '-9px'; bs.left = '16px';
            bs.padding = '4px 9px'; bs.borderRadius = '999px';
            bs.fontSize = '10px'; bs.fontWeight = '700'; bs.letterSpacing = '.06em';
            bs.textTransform = 'uppercase'; bs.whiteSpace = 'nowrap';
            bs.background = s[labelKeys[0]]; bs.color = s[labelKeys[1]];
            bs.boxShadow = '0 3px 8px -2px rgba(0,0,0,.3)';
            card.appendChild(badge);
        },

        // "Save X%" pill under the price.
        save: function (right, o, s) {
            if (!(o.old && o.old > o.price)) { return; }
            var pct = Math.round(((o.old - o.price) / o.old) * 100);
            var sb = document.createElement('span');
            sb.textContent = 'Save ' + pct + '%';
            var sbs = sb.style;
            sbs.display = 'inline-block'; sbs.marginTop = '5px';
            sbs.padding = '2px 8px'; sbs.borderRadius = '999px';
            sbs.fontSize = '10px'; sbs.fontWeight = '700'; sbs.letterSpacing = '.03em';
            sbs.background = s.saveBadgeBg; sbs.color = s.saveBadgeText;
            right.appendChild(sb);
        },

        // Free-shipping banner under the best-value tier.
        shipping: function (wrap, s) {
            var ship = document.createElement('div');
            ship.textContent = '🚚 FREE Shipping';
            var shs = ship.style;
            shs.padding = '8px 14px'; shs.borderRadius = '8px'; shs.textAlign = 'center';
            shs.fontSize = '12px'; shs.fontWeight = '600';
            shs.background = s.shippingBadgeBg; shs.color = s.shippingBadgeText;
            wrap.appendChild(ship);
        }
    };

})();
