(function ($) {
    'use strict';

    $(document).ready(function () {

        $('#quantity_pricing, #preview').attr('style', '');

        // Tab switching logic
        $('.tabs a').click(function (e) {
            e.preventDefault();
            var tab_id = $(this).attr('href');
            $('.tabs a').removeClass('active');
            $('.panel').removeClass('active');
            $(this).addClass('active');
            $(tab_id).addClass('active');
        });

        var container = $('#bulkboost_container');

        // Badge label options shown in the per-block dropdown
        var BADGE_LABEL_OPTIONS = [
            { value: 'none', text: 'No label badge' },
            { value: 'hot', text: 'HOT' },
            { value: 'popular', text: 'POPULAR' },
            { value: 'bestdeal', text: 'BEST DEAL' }
        ];

        function buildBadgeLabelSelect(index, selected) {
            var html = '<select id="_bulkboost_qd_badge_label_' + index + '" name="_bulkboost_qd_badge_label[' + index + ']">';
            BADGE_LABEL_OPTIONS.forEach(function (opt) {
                var isSelected = (opt.value === selected) ? 'selected' : '';
                html += '<option value="' + opt.value + '" ' + isSelected + '>' + opt.text + '</option>';
            });
            html += '</select>';
            return html;
        }

        function addQuantityDiscountBlock(index, quantity = '', price = '', label = '', description = '', badge_text = '', badgeLabel = 'none', badgeFreeShipping = 'no', badgeSaveEnabled = 'no', badgeSaveOverride = '') {
            var formattedPrice = formatPrice(price);
            var badgeLabelSelectHtml = buildBadgeLabelSelect(index, badgeLabel);
            var freeShippingChecked = (badgeFreeShipping === 'yes') ? 'checked' : '';
            var saveEnabledChecked = (badgeSaveEnabled === 'yes') ? 'checked' : '';

            var block = `
                <div class="quantity_discount_block">
                    <div class="field-blocks">
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Quantity</h4>
                            <input type="number" id="_bulkboost_qd_quantity_${index}" placeholder="Quantity" name="_bulkboost_qd_quantity[${index}]" min="0" value="${quantity}" />
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Price (${bulkboostQuantitySettings.currencySymbol}) (total amount)</h4>
                            <input type="number" id="_bulkboost_qd_price_${index}" placeholder="Price" name="_bulkboost_qd_price[${index}]" min="0" step="any" value="${price}" />
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Label</h4>
                            <input type="text" id="_bulkboost_qd_label_${index}" placeholder="Label" name="_bulkboost_qd_label[${index}]" value="${label}" />
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Description</h4>
                            <textarea id="_bulkboost_qd_description_${index}" placeholder="Description" name="_bulkboost_qd_description[${index}]">${description}</textarea>
                        </div>
                    </div>
                    <div class="badge-fields-row">
                        <div class="badge-field-block">
                            <h4 style="margin:0; padding:0;">Label Badge</h4>
                            ${badgeLabelSelectHtml}
                        </div>
                        <div class="badge-field-block">
                            <h4 style="margin:0; padding:0;">Free Shipping Badge</h4>
                            <label class="badge-checkbox-label">
                                <input type="checkbox" id="_bulkboost_qd_badge_free_shipping_${index}" name="_bulkboost_qd_badge_free_shipping[${index}]" value="yes" ${freeShippingChecked}>
                                Show "Free Shipping"
                            </label>
                        </div>
                        <div class="badge-field-block">
                            <h4 style="margin:0; padding:0;">Save % Badge</h4>
                            <label class="badge-checkbox-label">
                                <input type="checkbox" class="badge-save-enabled" id="_bulkboost_qd_badge_save_enabled_${index}" name="_bulkboost_qd_badge_save_enabled[${index}]" value="yes" ${saveEnabledChecked}>
                                Show savings badge
                            </label>
                            <input type="text" class="badge-save-override" id="_bulkboost_qd_badge_save_override_${index}" name="_bulkboost_qd_badge_save_override[${index}]" placeholder="Auto-calculated (or type e.g. 20%)" value="${badgeSaveOverride}">
                        </div>
                    </div>
                    <button type="button" class="delete_quantity_discount">Delete</button>
                </div>
            `;
            container.append(block);
        }

        function formatPrice(price, currency) {
            var priceParts = price.toString().split('.');
            var wholePart = priceParts[0];
            var fractionalPart = priceParts[1] || '00';

            switch (currency) {
                case 'EUR':
                    return '€' + wholePart + ',' + fractionalPart;
                case 'USD':
                default:
                    return '$' + wholePart + '.' + fractionalPart;
            }
        }

        $('#add_quantity_discount').on('click', function () {
            var index = container.children('.quantity_discount_block').length;
            addQuantityDiscountBlock(index);
            updateQuantityDiscountsPreview();
        });

        $('#bulkboost_container').on('click', '.delete_quantity_discount', function () {
            $(this).closest('.quantity_discount_block').remove();
            updateQuantityDiscountsPreview();
        });

        // Load existing quantity discounts
        if (typeof bulkboost_data !== 'undefined' && bulkboost_data.quantities.length > 0) {
            var quantities = bulkboost_data.quantities || [];
            var prices = bulkboost_data.prices || [];
            var labels = bulkboost_data.labels || [];
            var descriptions = bulkboost_data.descriptions || [];
            var badge_text = bulkboost_data.badge_text || [];
            var badgeLabels = bulkboost_data.badge_labels || [];
            var badgeFreeShipping = bulkboost_data.badge_free_shipping || [];
            var badgeSaveEnabled = bulkboost_data.badge_save_enabled || [];
            var badgeSaveOverride = bulkboost_data.badge_save_override || [];

            for (var i = 0; i < quantities.length; i++) {
                addQuantityDiscountBlock(
                    i,
                    quantities[i],
                    prices[i],
                    labels[i],
                    descriptions[i],
                    badge_text[i],
                    badgeLabels[i] || 'none',
                    badgeFreeShipping[i] || 'no',
                    badgeSaveEnabled[i] || 'no',
                    badgeSaveOverride[i] || ''
                );
            }
        } else if ($('#post_ID').val()) {
            var regularPrice = $('#_regular_price').val() || '';
            addQuantityDiscountBlock(0, 1, regularPrice);
        }

        updateQuantityDiscountsPreview();

        // Update preview when quantity pricing values change (inputs, textareas, selects, checkboxes)
        $('#bulkboost_container').on('input change', '.quantity_discount_block input, .quantity_discount_block textarea, .quantity_discount_block select', function () {
            updateQuantityDiscountsPreview();
        });

        /**
         * Builds the label-tab badge HTML (HOT / MOST POPULAR / BEST DEAL \ud83d\udd25)
         * that overlaps the top-left corner of the preview card.
         */
        function buildLabelTabHtml(badgeLabel) {
            var labelMap = {
                hot: { text: 'HOT', cls: 'bulkboost-tab-hot' },
                popular: { text: 'MOST POPULAR', cls: 'bulkboost-tab-popular' },
                bestdeal: { text: 'BEST DEAL \ud83d\udd25', cls: 'bulkboost-tab-bestdeal' }
            };

            if (!badgeLabel || badgeLabel === 'none' || !labelMap[badgeLabel]) {
                return '';
            }

            var info = labelMap[badgeLabel];
            return '<div class="bulkboost-label-tab ' + info.cls + '">' + info.text + '</div>';
        }

        /**
         * Builds the "Save X%" pill shown under the price, inside the card.
         */
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

        /**
         * Builds the full-width "+ FREE Shipping" banner shown directly below
         * the preview card.
         */
        function buildShippingBannerHtml(freeShippingChecked) {
            if (!freeShippingChecked) {
                return '';
            }
            return '<div class="bulkboost-shipping-banner"><span class="bulkboost-shipping-icon">\ud83d\ude9a</span> + FREE Shipping</div>';
        }

        function updateQuantityDiscountsPreview() {
            var container = $('#bulkboost_container');
            var previewContainer = $('#bulkboost_preview');
            previewContainer.empty(); // Clear the previous content

            var singleItemPrice = 0;

            container.find('.quantity_discount_block').each(function () {
                var quantity = $(this).find('input[name^="_bulkboost_qd_quantity"]').val();
                var price = $(this).find('input[name^="_bulkboost_qd_price"]').val();
                var label = $(this).find('input[name^="_bulkboost_qd_label"]').val();
                var description = $(this).find('textarea[name^="_bulkboost_qd_description"]').val();

                var badgeLabel = $(this).find('select[name^="_bulkboost_qd_badge_label"]').val();
                var freeShippingChecked = $(this).find('input[name^="_bulkboost_qd_badge_free_shipping"]').is(':checked');
                var saveEnabledChecked = $(this).find('input[name^="_bulkboost_qd_badge_save_enabled"]').is(':checked');
                var saveOverrideVal = $(this).find('input[name^="_bulkboost_qd_badge_save_override"]').val();

                if (quantity == 1) {
                    singleItemPrice = price;
                }

                var oldPrice = '';
                var autoSavePercent = null;
                if (quantity > 1 && singleItemPrice) {
                    var oldTotalPrice = singleItemPrice * quantity;
                    if (oldTotalPrice > price) {
                        oldPrice = `<span><s>${formatPrice(oldTotalPrice.toFixed(2))}</s></span>`;
                        autoSavePercent = Math.round(((oldTotalPrice - price) / oldTotalPrice) * 100);
                    }
                }

                var labelTabHtml = buildLabelTabHtml(badgeLabel);
                var saveBadgeHtml = buildSaveBadgeHtml(saveEnabledChecked, saveOverrideVal, autoSavePercent);
                var shippingBannerHtml = buildShippingBannerHtml(freeShippingChecked);
                var hasLabelTabClass = labelTabHtml ? ' has-label-tab' : '';

                var block = `
                    <div class="bulkboost-tier-wrap${hasLabelTabClass}">
                        ${labelTabHtml}
                        <span class="bulkboost-swatch" data-value="${quantity}">
                            <div class="bulkboost-inner">
                            <div class="one-block">
                                <div class="bulkboost-radio">
                                    <input value="${quantity}" type="radio">
                                    <span></span>
                                </div>
                            </div>
                            <div class="second-block">
                                <div class="bulkboost-middle">
                                    <div class="bulkboost-heading">${label}</div>
                                    <div class="bulkboost-subheading">${description}</div>
                                </div>
                                <div class="bulkboost-right">
                                    <div class="bulkboost-price-row">
                                        <div class="old-price">${oldPrice}</div>
                                        <span class="bulkboost-price">${formatPrice(price)}</span>
                                    </div>
                                    ${saveBadgeHtml}
                                </div>
                            </div>
                            </div>
                        </span>
                        ${shippingBannerHtml}
                    </div>
                `;
                previewContainer.append(block);
            });


            previewContainer.find('.bulkboost-swatch').first().addClass('active');
            previewContainer.find('.bulkboost-swatch').first().find('input[type="radio"]').prop('checked', true);
            // Event delegation to handle click event for dynamically added elements
            previewContainer.on('click', '.bulkboost-swatch', function () {
                $('.bulkboost-swatch').removeClass('active');
                $('.bulkboost-swatch').find('input[type="radio"]').prop('checked', false);
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            previewContainer.on('change', '.bulkboost-swatch input[type="radio"]', function () {
                $('.bulkboost-swatch').removeClass('active');
                $('.bulkboost-swatch').find('input[type="radio"]').prop('checked', false);
                $(this).closest('.bulkboost-swatch').addClass('active');
                $(this).prop('checked', true);
            });
        }
        
        if (typeof bulkboost_data !== 'undefined') {
            // Extracted values from the bulkboost_data object
            var quantityDiscountsEnabled = bulkboost_data.quantity_enabled;
            var minMaxOrdersEnabled = bulkboost_data.min_max_enabled;
            var minValue = bulkboost_data.min_value;
            var maxValue = bulkboost_data.max_value;
            var displayMethodValue = bulkboost_data.display_method;

            // HTML Elements
            var quantityDiscountsEnable = document.querySelector('input[name="_bulkboost_qd_quantity_enabled"][value="enable"]');
            var quantityDiscountsDisable = document.querySelector('input[name="_bulkboost_qd_quantity_enabled"][value="disable"]');
            var minMaxOrdersEnable = document.querySelector('input[name="_bulkboost_qd_min_max_enabled"][value="enable"]');
            var minMaxOrdersDisable = document.querySelector('input[name="_bulkboost_qd_min_max_enabled"][value="disable"]');
            var quantityPricingTab = document.querySelector('.quantity_pricing_tab');
            var previewTab = document.querySelector('.preview_tab');
            var minMaxValues = document.getElementById('min_max_values');
            var displayMethods = document.getElementById('display_method');
            var displayMethodDropdown = document.querySelector('input[name="_bulkboost_qd_display_method"][value="dropdown"]');
            var displayMethodButtons = document.querySelector('input[name="_bulkboost_qd_display_method"][value="buttons"]');

            // Set initial states based on the string values
            quantityDiscountsEnable.checked = (quantityDiscountsEnabled === 'enable');
            quantityDiscountsDisable.checked = (quantityDiscountsEnabled === 'disable');
            minMaxOrdersEnable.checked = (minMaxOrdersEnabled === 'enable');
            minMaxOrdersDisable.checked = (minMaxOrdersEnabled === 'disable');
            minMaxValues.style.display = (minMaxOrdersEnabled === 'enable') ? 'block' : 'none';
            displayMethodDropdown.checked = (displayMethodValue === 'dropdown');
            displayMethodButtons.checked = (displayMethodValue === 'buttons');
            minMaxValues.querySelector('input[name="_bulkboost_qd_min_value"]').value = minValue;
            minMaxValues.querySelector('input[name="_bulkboost_qd_max_value"]').value = maxValue;
            quantityPricingTab.style.display = 'none';
            previewTab.style.display = 'none';


            // Event Listeners
            quantityDiscountsEnable.addEventListener('change', handleQuantityDiscountsChange);
            quantityDiscountsDisable.addEventListener('change', handleQuantityDiscountsChange);
            minMaxOrdersEnable.addEventListener('change', handleMinMaxOrdersChange);
            minMaxOrdersDisable.addEventListener('change', handleMinMaxOrdersChange);

            // Function to handle changes in BulkBoost
            function handleQuantityDiscountsChange() {
                if (quantityDiscountsEnable.checked) {
                    minMaxOrdersEnable.disabled = false;
                    minMaxOrdersDisable.checked = true;
                    minMaxValues.style.display = 'none';
                    displayMethodDropdown.parentElement.style.display = 'none';
                    quantityPricingTab.style.display = 'inline-block';
                    previewTab.style.display = 'inline-block';
                    $('#bulkboost_preview').show();
                    $('#bulkboost_notice_customise').show();
                    $('#minmax_notice_customise').hide();
                    $('#min_max_preview').hide();
                } else {
                    $('#bulkboost_preview').hide();
                    $('#bulkboost_notice_customise').hide();
                    $('#minmax_notice_customise').show();
                    $('#min_max_preview').show();
                    minMaxOrdersEnable.disabled = false;
                    minMaxOrdersDisable.disabled = false;
                    quantityPricingTab.style.display = 'none';
                    previewTab.style.display = 'none';
                }
            }

            // Function to handle changes in Min-Max Orders
            function handleMinMaxOrdersChange() {
                if (minMaxOrdersEnable.checked) {
                    quantityDiscountsEnable.disabled = false;
                    quantityDiscountsDisable.checked = true;
                    minMaxValues.style.display = 'block';
                    displayMethodDropdown.parentElement.style.display = 'block';
                    quantityPricingTab.style.display = 'none';
                    previewTab.style.display = 'inline-block';
                    minMaxValues.style.display = 'inline-block';
                } else {
                    quantityDiscountsEnable.disabled = false;
                    quantityDiscountsDisable.disabled = false;
                    quantityPricingTab.style.display = 'none';
                    previewTab.style.display = 'none';
                    minMaxValues.style.display = 'none';
                    displayMethods.style.display = 'none';
                }
            }

            // Trigger initial change events to set up the UI correctly
            handleQuantityDiscountsChange();
            handleMinMaxOrdersChange();

            if (quantityDiscountsEnable.checked) {
                minMaxOrdersEnable.disabled = false;
                minMaxOrdersDisable.checked = true;
                minMaxValues.style.display = 'none';
                displayMethodDropdown.parentElement.style.display = 'none';
                quantityPricingTab.style.display = 'inline-block';
                previewTab.style.display = 'inline-block';
                $('#bulkboost_preview').show();
                $('#min_max_preview').hide();
            }

        }


        let minMaxValueMIN = 1;
        let minMaxValueMAX = 10;

        let previewArea = $('#minmax_preview');

        function createButtons() {
            previewArea.empty();
            for (let i = minMaxValueMIN; i <= minMaxValueMAX; i++) {
                const button = $('<span class="minmax-buttons"></span>');
                button.text(i);

                if (i === 3) {
                    button.addClass('active');
                }

                previewArea.append(button);
            }
        }

        createButtons();

    });

})(jQuery);
