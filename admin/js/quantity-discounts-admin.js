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

        $(document).on('click', '.notice.is-dismissible', function () {

            console.log('clicked');
            var data = {
                'action': 'WIRNQDSQ_dismiss_admin_notice',
                'nonce': wpironscripts.nonce
            };

            $.post(wpironscripts.ajaxurl, data, function (response) {
                console.log('Notice dismissed');
            });

        });

        var container = $('#quantity_discounts_container');

        function addQuantityDiscountBlock(index, quantity = '', price = '', label = '', description = '', badge_text = '') {
            var formattedPrice = formatPrice(price);
            var block = `
                <div class="quantity_discount_block">
                    <div class="field-blocks">
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Quantity</h4>
                            <input type="number" id="_wpiron_qd_quantity_${index}" placeholder="Quantity" name="_wpiron_qd_quantity[${index}]" min="0" value="${quantity}" />
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Price (${quantityDiscountsSettings.currencySymbol}) (total amount)</h4>
                            <input type="number" id="_wpiron_qd_price_${index}" placeholder="Price" name="_wpiron_qd_price[${index}]" min="0" step="any" value="${price}" />
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Label</h4>
                            <input type="text" id="_wpiron_qd_label_${index}" placeholder="Label" name="_wpiron_qd_label[${index}]" value="${label}" />
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Description</h4>
                            <textarea id="_wpiron_qd_description_${index}" placeholder="Description" name="_wpiron_qd_description[${index}]">${description}</textarea>
                        </div>
                        <div class="block">
                            <h4 style="margin:0; padding:0;">Badge Text</h4>
                            <a style="color:darkgreen; display:inline-block; font-weight:700;" target="_blank" href="https://wpiron.com/products/quantity-breaks-and-discounts/">Upgrade To
        Premium!</a>
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
        });

        $('#quantity_discounts_container').on('click', '.delete_quantity_discount', function () {
            $(this).closest('.quantity_discount_block').remove();
            updateQuantityDiscountsPreview();
        });

        // Load existing quantity discounts
        if (typeof quantity_discounts_data !== 'undefined' && quantity_discounts_data.quantities.length > 0) {
            var quantities = quantity_discounts_data.quantities || [];
            var prices = quantity_discounts_data.prices || [];
            var labels = quantity_discounts_data.labels || [];
            var descriptions = quantity_discounts_data.descriptions || [];
            var badge_text = quantity_discounts_data.badge_text || [];
            var badge = quantity_discounts_data.badge || [];

            for (var i = 0; i < quantities.length; i++) {
                addQuantityDiscountBlock(i, quantities[i], prices[i], labels[i], descriptions[i], badge_text[i]);
            }
        } else if ($('#post_ID').val()) {
            var regularPrice = $('#_regular_price').val() || '';
            addQuantityDiscountBlock(0, 1, regularPrice);
        }

        updateQuantityDiscountsPreview();

        // Update preview when quantity pricing values change
        $('#quantity_discounts_container').on('input', '.quantity_discount_block input, .quantity_discount_block textarea', function () {
            updateQuantityDiscountsPreview();
        });

        function updateQuantityDiscountsPreview() {
            var container = $('#quantity_discounts_container');
            var previewContainer = $('#quantity_discounts_preview');
            previewContainer.empty(); // Clear the previous content

            var singleItemPrice = 0;

            container.find('.quantity_discount_block').each(function () {
                var quantity = $(this).find('input[name^="_wpiron_qd_quantity"]').val();
                var price = $(this).find('input[name^="_wpiron_qd_price"]').val();
                var label = $(this).find('input[name^="_wpiron_qd_label"]').val();
                var description = $(this).find('textarea[name^="_wpiron_qd_description"]').val();
                var badge_text = $(this).find('textarea[name^="_wpiron_qd_badge_text"]').val();

                if (quantity == 1) {
                    singleItemPrice = price;
                }

                var oldPrice = '';
                if (quantity > 1) {
                    var oldTotalPrice = singleItemPrice * quantity;
                    if (oldTotalPrice > price) {
                        oldPrice = `<span><s>${formatPrice(oldTotalPrice.toFixed(2))}</s></span>`;
                    }
                }

                $('.badges-preview').removeClass('active');

                if (badge === 'badge1') {
                    $('.badge-price-star_preview').addClass('active');
                } else if (badge === 'badge2') {
                    $('.circle-badge_preview').addClass('active');
                } else if (badge === 'badge3') {
                    $('.rect-badge_preview').addClass('active');
                }

                let class_for_spacing = '';
                if(badge === 'badge1' || badge === 'badge2') {
                    class_for_spacing = 'badge-inside';
                }

                let html = '';
                if(badge_text && badge === 'badge1') {
                    html = `<div id="badge-container" class="badge-price-star_container"> 
                    <div class="badge-price-star_preview badge-price-star badges-preview"> 
                    <div class="price"> 
                    <span class="label">${badge_text}</span>
                    </div>
                    </div>
                    </div>`;
                }

                if(badge_text && badge === 'badge2') {
                    html = `<div id="badge-container" class="circle-badge_container"> 
                    <div class="circle-badge_preview circle-badge badges-preview active">${badge_text}</div>
                    </div>`;
                }

                if(badge_text && badge === 'badge3') {
                    html = `<div id="badge-container" class="rect-badge_container"> 
                    <div class="rect-badge_preview rect-badge badges-preview ">${badge_text}</div>
                    </div>`;
                }

                var block = `
                    <span class="wpiqd-swatch" data-value="${quantity}">
                        <div class="wpiqd-inner">
                        <div class="one-block">
                            <div class="wpiqd-radio">
                                <input value="${quantity}" type="radio">
                                <span></span>
                            </div>
                        </div>
                        <div class="second-block ${class_for_spacing}">
                            <div class="wpiqd-middle">
                                <div class="wpiqd-heading">${label}</div>
                                <div class="wpiqd-subheading">${description}</div>
                            </div>
                            <div class="wpiqd-right">
                                <span class="wpiqd-price">${formatPrice(price)}</span>
                                <div class="old-price">${oldPrice}</div>
                            </div>
                        </div>
                        </div>
                        ${html}
                    </span>
                `;
                previewContainer.append(block);
            });


            previewContainer.find('.wpiqd-swatch').first().addClass('active');
            previewContainer.find('.wpiqd-swatch').first().find('input[type="radio"]').prop('checked', true);
            // Event delegation to handle click event for dynamically added elements
            previewContainer.on('click', '.wpiqd-swatch', function () {
                $('.wpiqd-swatch').removeClass('active');
                $('.wpiqd-swatch').find('input[type="radio"]').prop('checked', false);
                $(this).addClass('active');
                $(this).find('input[type="radio"]').prop('checked', true);
            });

            previewContainer.on('change', '.wpiqd-swatch input[type="radio"]', function () {
                $('.wpiqd-swatch').removeClass('active');
                $('.wpiqd-swatch').find('input[type="radio"]').prop('checked', false);
                $(this).closest('.wpiqd-swatch').addClass('active');
                $(this).prop('checked', true);
            });
        }
        
        if (typeof quantity_discounts_data !== 'undefined') {
            // Extracted values from the quantity_discounts_data object
            var quantityDiscountsEnabled = quantity_discounts_data.quantity_enabled;
            var minMaxOrdersEnabled = quantity_discounts_data.min_max_enabled;
            var minValue = quantity_discounts_data.min_value;
            var maxValue = quantity_discounts_data.max_value;
            var displayMethodValue = quantity_discounts_data.display_method;

            // HTML Elements
            var quantityDiscountsEnable = document.querySelector('input[name="_wpiron_qd_quantity_enabled"][value="enable"]');
            var quantityDiscountsDisable = document.querySelector('input[name="_wpiron_qd_quantity_enabled"][value="disable"]');
            var minMaxOrdersEnable = document.querySelector('input[name="_wpiron_qd_min_max_enabled"][value="enable"]');
            var minMaxOrdersDisable = document.querySelector('input[name="_wpiron_qd_min_max_enabled"][value="disable"]');
            var quantityPricingTab = document.querySelector('.quantity_pricing_tab');
            var previewTab = document.querySelector('.preview_tab');
            var minMaxValues = document.getElementById('min_max_values');
            var displayMethods = document.getElementById('display_method');
            var displayMethodDropdown = document.querySelector('input[name="_wpiron_qd_display_method"][value="dropdown"]');
            var displayMethodButtons = document.querySelector('input[name="_wpiron_qd_display_method"][value="buttons"]');

            // Set initial states based on the string values
            quantityDiscountsEnable.checked = (quantityDiscountsEnabled === 'enable');
            quantityDiscountsDisable.checked = (quantityDiscountsEnabled === 'disable');
            minMaxOrdersEnable.checked = (minMaxOrdersEnabled === 'enable');
            minMaxOrdersDisable.checked = (minMaxOrdersEnabled === 'disable');
            minMaxValues.style.display = (minMaxOrdersEnabled === 'enable') ? 'block' : 'none';
            displayMethodDropdown.checked = (displayMethodValue === 'dropdown');
            displayMethodButtons.checked = (displayMethodValue === 'buttons');
            minMaxValues.querySelector('input[name="_wpiron_qd_min_value"]').value = minValue;
            minMaxValues.querySelector('input[name="_wpiron_qd_max_value"]').value = maxValue;
            quantityPricingTab.style.display = 'none';
            previewTab.style.display = 'none';


            // Event Listeners
            quantityDiscountsEnable.addEventListener('change', handleQuantityDiscountsChange);
            quantityDiscountsDisable.addEventListener('change', handleQuantityDiscountsChange);
            minMaxOrdersEnable.addEventListener('change', handleMinMaxOrdersChange);
            minMaxOrdersDisable.addEventListener('change', handleMinMaxOrdersChange);

            // Function to handle changes in Quantity Discounts
            function handleQuantityDiscountsChange() {
                if (quantityDiscountsEnable.checked) {
                    minMaxOrdersEnable.disabled = true;
                    minMaxOrdersDisable.checked = true;
                    minMaxValues.style.display = 'none';
                    displayMethodDropdown.parentElement.style.display = 'none';
                    quantityPricingTab.style.display = 'inline-block';
                    previewTab.style.display = 'inline-block';
                    $('#quantity_discounts_preview').show();
                    $('#quantity_discounts_notice_customise').show();
                    $('#minmax_notice_customise').hide();
                    $('#min_max_preview').hide();
                } else {
                    $('#quantity_discounts_preview').hide();
                    $('#quantity_discounts_notice_customise').hide();
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
                    quantityDiscountsEnable.disabled = true;
                    quantityDiscountsDisable.checked = true;
                    minMaxValues.style.display = 'block';
                    displayMethodDropdown.parentElement.style.display = 'block';
                    quantityPricingTab.style.display = 'none';
                    previewTab.style.display = 'inline-block';
                    minMaxValues.style.display = 'inline-block';
                    // displayMethods.style.display = 'inline-block';
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
                minMaxOrdersEnable.disabled = true;
                minMaxOrdersDisable.checked = true;
                minMaxValues.style.display = 'none';
                displayMethodDropdown.parentElement.style.display = 'none';
                quantityPricingTab.style.display = 'inline-block';
                previewTab.style.display = 'inline-block';
                $('#quantity_discounts_preview').show();
                $('#min_max_preview').hide();
            }

        } else {
            console.error("quantity_discounts_data is not defined");
        }


        let minMaxValueMIN = 1;
        let minMaxValueMAX = 10;

        let previewArea = $('#minmax_preview');

        function createButtons() {
            previewArea.empty();
            for (let i = minMaxValueMIN; i <= minMaxValueMAX; i++) {
                const button = $('<span class="minmax-buttons"></span>');
                button.text(i);

                // Check if it's the third button
                if (i === 3) {
                    button.addClass('active');
                }

                previewArea.append(button);
            }
        }

        createButtons();

    });

})(jQuery);
