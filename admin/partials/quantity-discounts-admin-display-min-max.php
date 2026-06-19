<div class="wrap">
    <h2>Min Max Design Settings</h2>
    <form method="post" action="options.php">
        <?php
        settings_fields('min_max_quantity_discounts_settings');
        // Default values
        $defaults = [
            'min_max_background_color_active' => '#000000',
            'min_max_background_color_inactive' => '#FFFFFF',
            'min_max_background_color_hover' => '#DDDDDD',
            'min_max_text_color_active' => '#FFFFFF',
            'min_max_text_color_inactive' => '#000000',
            'min_max_text_color_hover' => '#333333',
            'min_max_border_color_active' => '#000000',
            'min_max_border_color_inactive' => '#FFFFFF',
            'min_max_border_color_hover' => '#333333',
            'min_max_size' => '16',
        ];
        $options = get_option('min_max_quantity_discounts_settings', $defaults);

        ?>
        <hr>
        <p>🎨 Customize your Min Max Quantity design here! </p>
        <p>Once you've tailored it to perfection:</p>
        1️⃣ Head over to an <strong>Existing Product</strong> or spark creativity by adding a <strong>New
            Product</strong>.<br/>
        2️⃣ Scroll down to the <strong>Product Data</strong> section. <br>
        3️⃣ Spot the <strong>"Quantity Discounts"</strong> tab on the left menu. 👈 <br>
        4️⃣ Click to unleash amazing discounts for your products! 💸 <br>
        <p>Happy discounting! 🚀</p>
        <hr>
        <h2>Preview</h2>
        <div id="quantity_discounts_preview_preview">

        </div>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="#design-settings" class="nav-tab nav-tab-active">Design Settings</a>
        </h2>
        <div id="design-settings" class="tab-content">
            <table class="form-table">
                <!-- Background Color -->
                <tr valign="top">
                    <th scope="row">Background Color</th>
                    <td>
                        <input type="text" id="min_max_background_color_active"
                               name="min_max_quantity_discounts_settings[min_max_background_color_active]"
                               value="<?php echo esc_attr($options['min_max_background_color_active']); ?>"
                               class="color-field"/>
                        <input type="text" id="min_max_background_color_inactive"
                               name="min_max_quantity_discounts_settings[min_max_background_color_inactive]"
                               value="<?php echo esc_attr($options['min_max_background_color_inactive']); ?>"
                               class="color-field"/>
                        <input type="text" id="min_max_background_color_hover"
                               name="min_max_quantity_discounts_settings[min_max_background_color_hover]"
                               value="<?php echo esc_attr($options['min_max_background_color_hover']); ?>"
                               class="color-field"/>
                    </td>
                </tr>

                <!-- Text Color -->
                <tr valign="top">
                    <th scope="row">Text Color</th>
                    <td>
                        <input type="text" id="min_max_text_color_active"
                               name="min_max_quantity_discounts_settings[min_max_text_color_active]"
                               value="<?php echo esc_attr($options['min_max_text_color_active']); ?>"
                               class="color-field"/>
                        <input type="text" id="min_max_text_color_inactive"
                               name="min_max_quantity_discounts_settings[min_max_text_color_inactive]"
                               value="<?php echo esc_attr($options['min_max_text_color_inactive']); ?>"
                               class="color-field"/>
                        <input type="text" id="min_max_text_color_hover"
                               name="min_max_quantity_discounts_settings[min_max_text_color_hover]"
                               value="<?php echo esc_attr($options['min_max_text_color_hover']); ?>"
                               class="color-field"/>
                    </td>
                </tr>

                <!-- Border Color -->
                <tr valign="top">
                    <th scope="row">Border Color</th>
                    <td>
                        <input type="text" id="min_max_border_color_active"
                               name="min_max_quantity_discounts_settings[min_max_border_color_active]"
                               value="<?php echo esc_attr($options['min_max_border_color_active']); ?>"
                               class="color-field"/>
                        <input type="text" id="min_max_border_color_inactive"
                               name="min_max_quantity_discounts_settings[min_max_border_color_inactive]"
                               value="<?php echo esc_attr($options['min_max_border_color_inactive']); ?>"
                               class="color-field"/>
                        <input type="text" id="min_max_border_color_hover"
                               name="min_max_quantity_discounts_settings[min_max_border_color_hover]"
                               value="<?php echo esc_attr($options['min_max_border_color_hover']); ?>"
                               class="color-field"/>
                    </td>
                </tr>

                <!-- min_max_size -->
                <tr valign="top">
                    <th scope="row">Button Size</th>
                    <td>
                        <input type="number" id="min_max_size"
                               name="min_max_quantity_discounts_settings[min_max_size]"
                               value="<?php echo esc_attr($options['min_max_size']); ?>"/>
                    </td>
                </tr>
            </table>
        </div>
        <?php
        submit_button(); ?>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        $('.color-field').wpColorPicker({
            change: function (event, ui) {
                // Update the preview when a color is selected
                var element = $(event.target);
                var color = ui.color.toString();
                element.val(color);
                updatePreview();
            },
            clear: function (event) {
                // Update the preview when the color is cleared
                var element = $(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker');
                element.val('');
                updatePreview();
            }
        });

        // Selectors for the input fields
        const backgroundColorActive = $('#min_max_background_color_active');
        const backgroundColorInactive = $('#min_max_background_color_inactive');
        const backgroundColorHover = $('#min_max_background_color_hover');
        const textColorActive = $('#min_max_text_color_active');
        const textColorInactive = $('#min_max_text_color_inactive');
        const textColorHover = $('#min_max_text_color_hover');
        const borderColorActive = $('#min_max_border_color_active');
        const borderColorInactive = $('#min_max_border_color_inactive');
        const borderColorHover = $('#min_max_border_color_hover');
        const min_max_size = $('#min_max_size');

        const previewArea = $('#quantity_discounts_preview_preview');

        // Function to create buttons
        function createButtons() {
            previewArea.empty();
            for (let i = 1; i <= 10; i++) { // Adjust the number of buttons as needed
                const button = $('<span></span>');
                button.text(i);
                button.css({
                    'padding': `${min_max_size.val() / 2}px ${min_max_size.val()}px`,
                    'margin': '2px',
                    'display': 'inline-block',
                    'backgroundColor': backgroundColorInactive.val(),
                    'color': textColorInactive.val(),
                    'border': `1px solid ${borderColorInactive.val()}`,
                    'fontmin_max_size': min_max_size.val() + 'px',
                    'cursor': 'pointer'
                });

                // Check if it's the third button
                if (i === 3) {
                    button.addClass('quantity-button-active').css({
                        'backgroundColor': backgroundColorActive.val(),
                        'color': textColorActive.val(),
                        'borderColor': borderColorActive.val()
                    });
                }

                // Hover effect
                button.hover(
                    function () {
                        $(this).css({
                            'backgroundColor': backgroundColorHover.val(),
                            'color': textColorHover.val(),
                            'borderColor': borderColorHover.val()
                        });
                    }, function () {
                        if (!$(this).hasClass('quantity-button-active')) {
                            $(this).css({
                                'backgroundColor': backgroundColorInactive.val(),
                                'color': textColorInactive.val(),
                                'borderColor': borderColorInactive.val()
                            });
                        }
                    }
                );

                // Exclusive active effect
                button.click(function () {
                    // Remove active class and reset style for all buttons
                    $('.quantity-button-active').removeClass('quantity-button-active').css({
                        'backgroundColor': backgroundColorInactive.val(),
                        'color': textColorInactive.val(),
                        'borderColor': borderColorInactive.val()
                    });

                    // Add active class and set active style for clicked button
                    $(this).addClass('quantity-button-active').css({
                        'backgroundColor': backgroundColorActive.val(),
                        'color': textColorActive.val(),
                        'borderColor': borderColorActive.val()
                    });
                });

                previewArea.append(button);
            }
        }


        // Initial creation of buttons
        createButtons();

        // Update buttons on input change
        $('.color-field, #min_max_size').on('change', function () {
            createButtons();
        });

        // Function to update the preview based on the current settings
        function updatePreview() {
            createButtons();
        }

    });

</script>
<style id="dynamicStyles"></style>