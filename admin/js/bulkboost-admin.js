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

        function addQuantityDiscountBlock(index, quantity = '', price = '', label = '', description = '') {
            // Optional per-block extra fields are provided by an add-on when
            // present; the core block never contains them.
            var extraFieldsHtml = (window.bulkboostAdminBlockExtras && typeof window.bulkboostAdminBlockExtras.fieldsRow === 'function')
                ? window.bulkboostAdminBlockExtras.fieldsRow(index)
                : '';

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
                    ${extraFieldsHtml}
                    <button type="button" class="delete_quantity_discount">Delete</button>
                </div>
            `;
            container.append(block);

            // Let the add-on populate its own fields if present.
            if (window.bulkboostAdminBlockExtras && typeof window.bulkboostAdminBlockExtras.applyValues === 'function') {
                window.bulkboostAdminBlockExtras.applyValues(index);
            }
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

            for (var i = 0; i < quantities.length; i++) {
                addQuantityDiscountBlock(
                    i,
                    quantities[i],
                    prices[i],
                    labels[i],
                    descriptions[i]
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

                // Optional per-tier extras come from an add-on when present.
                var extras = (window.bulkboostAdminBlockExtras && typeof window.bulkboostAdminBlockExtras.previewFor === 'function')
                    ? window.bulkboostAdminBlockExtras.previewFor($(this), autoSavePercent)
                    : { top: '', mid: '', bottom: '' };
                var topHtml = extras.top || '';
                var midHtml = extras.mid || '';
                var bottomHtml = extras.bottom || '';
                var hasTopClass = topHtml ? ' has-label-tab' : '';

                var block = `
                    <div class="bulkboost-tier-wrap${hasTopClass}">
                        ${topHtml}
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
                                    ${midHtml}
                                </div>
                            </div>
                            </div>
                        </span>
                        ${bottomHtml}
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
