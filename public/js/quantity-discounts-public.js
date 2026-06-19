(function ($) {
    'use strict';

    document.addEventListener('DOMContentLoaded', function () {

        // min max
        var quantityButtons = document.querySelectorAll('.quantity-button');
        var quantityField = document.querySelector('input[name="quantity"]'); // Target the existing quantity field
        var firstButton = quantityButtons[0];

        // Set the quantity field value to the first button's data-quantity by default
        if (firstButton) {
            quantityField.value = firstButton.getAttribute('data-quantity');
        }

        quantityButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                // Update the value of the existing quantity field
                quantityField.value = this.getAttribute('data-quantity');

                // Remove 'active' class from all buttons
                quantityButtons.forEach(function (btn) {
                    btn.classList.remove('active');
                });

                // Add 'active' class to the clicked button
                this.classList.add('active');
            });
        });

        // quantity discounts
        var swatches = document.querySelectorAll('.wpiqd-swatch');
        var activeSwatch = document.querySelector('.wpiqd-swatch.active');
        var customQuantityInput = document.getElementById('wpi_custom_quantity');
        var customPriceInput = document.getElementById('wpi_custom_price');

        if (activeSwatch) {
            var activeRadio = activeSwatch.querySelector('input[type="radio"]');
            if (activeRadio) {
                customQuantityInput.value = activeRadio.value;
                customPriceInput.value = activeSwatch.getAttribute('data-price');
            }
        }

        swatches.forEach(function (swatch) {
            swatch.addEventListener('click', function () {
                // Remove active class and uncheck all radio buttons
                swatches.forEach(function (s) {
                    s.classList.remove('active');
                    var radio = s.querySelector('input[type="radio"]');
                    if (radio) {
                        radio.checked = false;
                    }
                });

                // Add active class and check the clicked radio button
                swatch.classList.add('active');
                var radio = swatch.querySelector('input[type="radio"]');
                if (radio) {
                    radio.checked = true;
                }

                var quantityInput = document.querySelector('.quantity input.qty');
                if (quantityInput) {
                    quantityInput.value = radio.value;
                }

                var price = swatch.getAttribute('data-price');
                var priceContainer = document.querySelector('.product-price-selector'); // Update this selector to match your theme
                if (priceContainer) {
                    priceContainer.textContent = price;
                }

                customQuantityInput.value = radio.value;
                customPriceInput.value = price;
            });
        });
    });
})(jQuery);
