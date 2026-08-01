/**
 * BulkBoost Pro — per-tier badge fields for the product data panel.
 *
 * Registers window.bulkboostAdminBlockExtras, which the core admin script
 * (bulkboost-admin.js) calls only when it exists. This whole file is excluded
 * from the Free build (the __premium_only filename suffix), and it is enqueued
 * from a Freemius premium-only PHP block, so the Free version contains no
 * badge code at all.
 */
(function ($) {
    'use strict';

    // Badge label options shown in the per-block dropdown.
    var BADGE_LABEL_OPTIONS = [
        { value: 'none', text: 'No label badge' },
        { value: 'hot', text: 'HOT' },
        { value: 'popular', text: 'POPULAR' },
        { value: 'bestdeal', text: 'BEST DEAL' }
    ];

    function buildBadgeLabelSelect(index) {
        var html = '<select id="_bulkboost_qd_badge_label_' + index + '" name="_bulkboost_qd_badge_label[' + index + ']">';
        BADGE_LABEL_OPTIONS.forEach(function (opt) {
            html += '<option value="' + opt.value + '">' + opt.text + '</option>';
        });
        html += '</select>';
        return html;
    }

    // Label-tab badge HTML (HOT / MOST POPULAR / BEST DEAL 🔥) overlapping the card top-left.
    function buildLabelTabHtml(badgeLabel) {
        var labelMap = {
            hot: { text: 'HOT', cls: 'bulkboost-tab-hot' },
            popular: { text: 'MOST POPULAR', cls: 'bulkboost-tab-popular' },
            bestdeal: { text: 'BEST DEAL 🔥', cls: 'bulkboost-tab-bestdeal' }
        };
        if (!badgeLabel || badgeLabel === 'none' || !labelMap[badgeLabel]) {
            return '';
        }
        var info = labelMap[badgeLabel];
        return '<div class="bulkboost-label-tab ' + info.cls + '">' + info.text + '</div>';
    }

    // "Save X%" pill shown under the price inside the card.
    function buildSaveBadgeHtml(saveEnabledChecked, saveOverrideVal, autoSavePercent) {
        if (!saveEnabledChecked) {
            return '';
        }
        var saveText = (saveOverrideVal && saveOverrideVal.trim() !== '')
            ? saveOverrideVal.trim()
            : (autoSavePercent !== null && autoSavePercent > 0 ? ('Save ' + autoSavePercent + '%') : '');
        if (!saveText) {
            return '';
        }
        if (saveText.indexOf('%') === -1 && /^\d+(\.\d+)?$/.test(saveText)) {
            saveText = 'Save ' + saveText + '%';
        }
        return '<span class="bulkboost-badge bulkboost-badge-save">' + saveText + '</span>';
    }

    // Full-width "+ FREE Shipping" banner shown directly below the card.
    function buildShippingBannerHtml(freeShippingChecked) {
        if (!freeShippingChecked) {
            return '';
        }
        return '<div class="bulkboost-shipping-banner"><span class="bulkboost-shipping-icon">🚚</span> + FREE Shipping</div>';
    }

    window.bulkboostAdminBlockExtras = {
        // Badge fields appended to each quantity-discount block.
        fieldsRow: function (index) {
            return `
                <div class="badge-fields-row">
                    <div class="badge-field-block">
                        <h4 style="margin:0; padding:0;">Label Badge</h4>
                        ${buildBadgeLabelSelect(index)}
                    </div>
                    <div class="badge-field-block">
                        <h4 style="margin:0; padding:0;">Free Shipping Badge</h4>
                        <label class="badge-checkbox-label">
                            <input type="checkbox" id="_bulkboost_qd_badge_free_shipping_${index}" name="_bulkboost_qd_badge_free_shipping[${index}]" value="yes">
                            Show "Free Shipping"
                        </label>
                    </div>
                    <div class="badge-field-block">
                        <h4 style="margin:0; padding:0;">Save % Badge</h4>
                        <label class="badge-checkbox-label">
                            <input type="checkbox" class="badge-save-enabled" id="_bulkboost_qd_badge_save_enabled_${index}" name="_bulkboost_qd_badge_save_enabled[${index}]" value="yes">
                            Show savings badge
                        </label>
                        <input type="text" class="badge-save-override" id="_bulkboost_qd_badge_save_override_${index}" name="_bulkboost_qd_badge_save_override[${index}]" placeholder="Auto-calculated (or type e.g. 20%)" value="">
                    </div>
                </div>
            `;
        },

        // Populate the badge fields for a block from saved meta.
        applyValues: function (index) {
            if (typeof bulkboost_data === 'undefined') {
                return;
            }
            var labels = bulkboost_data.badge_labels || [];
            var freeShip = bulkboost_data.badge_free_shipping || [];
            var saveEnabled = bulkboost_data.badge_save_enabled || [];
            var saveOverride = bulkboost_data.badge_save_override || [];

            if (labels[index]) {
                $('#_bulkboost_qd_badge_label_' + index).val(labels[index]);
            }
            if (freeShip[index] === 'yes') {
                $('#_bulkboost_qd_badge_free_shipping_' + index).prop('checked', true);
            }
            if (saveEnabled[index] === 'yes') {
                $('#_bulkboost_qd_badge_save_enabled_' + index).prop('checked', true);
            }
            if (saveOverride[index]) {
                $('#_bulkboost_qd_badge_save_override_' + index).val(saveOverride[index]);
            }
        },

        // Badge HTML for the live preview of one tier.
        previewFor: function (block$, autoSavePercent) {
            var badgeLabel = block$.find('select[name^="_bulkboost_qd_badge_label"]').val();
            var freeShippingChecked = block$.find('input[name^="_bulkboost_qd_badge_free_shipping"]').is(':checked');
            var saveEnabledChecked = block$.find('input[name^="_bulkboost_qd_badge_save_enabled"]').is(':checked');
            var saveOverrideVal = block$.find('input[name^="_bulkboost_qd_badge_save_override"]').val();

            return {
                top: buildLabelTabHtml(badgeLabel),
                mid: buildSaveBadgeHtml(saveEnabledChecked, saveOverrideVal, autoSavePercent),
                bottom: buildShippingBannerHtml(freeShippingChecked)
            };
        }
    };

})(jQuery);
