<div class="wrap">
    <h2>BulkBoost Design Settings</h2>
    <form method="post" action="options.php">
        <?php

        settings_fields('bulkboost_settings'); ?>
        <hr>
        <p>🎨 Customize your BulkBoost design here! </p>
        <p>Once you've tailored it to perfection:</p>
        1️⃣ Head over to an <strong>Existing Product</strong> or spark creativity by adding a <strong>New
            Product</strong>.<br/>
        2️⃣ Scroll down to the <strong>Product Data</strong> section. <br>
        3️⃣ Spot the <strong>"BulkBoost"</strong> tab on the left menu. 👈 <br>
        4️⃣ Click to unleash amazing discounts for your products! 💸 <br>
        <p>Happy discounting! 🚀</p>
        <hr>
        <h2>Preview</h2>
        <div id="bulkboost_preview_preview">
            <span class="bulkboost-swatch active" data-value="1">
                <div class="bulkboost-inner">
                    <div class="one-block">
                        <label class="bulkboost-radio">
                            <input id="radio1" value="1" name="quantity" type="radio" checked>
                            <span></span> <!-- This span is styled as the radio button -->
                        </label>
                    </div>
                    <div class="second-block ">
                    <div class="bulkboost-middle">
                        <div class="bulkboost-heading">1 item (active)</div>
                        <div class="bulkboost-subheading">Get 1 item and enjoy our product</div>
                    </div>
                    <div class="bulkboost-right">
                        <span class="bulkboost-price"><?php
                            echo wc_price(100, array('decimals' => 0)); ?></span>
                        <div class="old-price"></div>
                    </div>
                </div>
                </div>
            </span>
            <span class="bulkboost-swatch " data-value="2">
                <div class="bulkboost-inner">
                <div class="one-block">
                    <label class="bulkboost-radio">
                        <input id="radio2" value="2" name="quantity" type="radio">
                        <span></span>
                    </label>
                </div>
                <div class="second-block">
                    <div class="bulkboost-middle">
                        <div class="bulkboost-heading">2 items (Inactive)</div>
                        <div class="bulkboost-subheading">Get 2 for better price only now!</div>
                    </div>
                    <div class="bulkboost-right">
                        <span class="bulkboost-price"><?php
                            echo wc_price(150, array('decimals' => 0)); ?></span>
                        <div class="old-price"><span><s><?php
                                    echo wc_price(200, array('decimals' => 0)); ?></s></span></div>
                    </div>
                </div>
                </div>
            </span>
        </div>
        <hr>
        <h2 class="nav-tab-wrapper">
            <a href="#design-settings" class="nav-tab nav-tab-active">Design Settings</a>
            <a href="#typographic" class="nav-tab">Typographic</a>
            <a href="#badge" class="nav-tab">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12">
                    <rect x="2" y="6" width="8" height="5" rx="1" fill="#F00"/>
                    <path fill="none" stroke="#F00" d="m3.5,6V4a2.5,2.5 0 0,1 5,0v2"/>
                </svg>
                Badge</a>
        </h2>
        <div id="design-settings" class="tab-content">
            <table class="form-table">
                <!-- Border Color -->
                <tr valign="top">
                    <th scope="row">Border Color</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Active (Selected)</strong><br>
                            <input type="text" id="border_color_active"
                                   name="bulkboost_settings[border_color_active]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['border_color_active']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>Inactive (Not Selected)</strong><br>
                            <input type="text" id="border_color_inactive"
                                   name="bulkboost_settings[border_color_inactive]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['border_color_inactive']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>On Hover (Mouse over)</strong><br>
                            <input type="text" id="border_color_hover"
                                   name="bulkboost_settings[border_color_hover]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['border_color_hover']); ?>"
                                   class="color-field"/>
                        </div>
                    </td>
                </tr>
                <!-- Background Color -->
                <tr valign="top" style="border-top:1px solid grey; border-bottom: 1px solid grey;">
                    <th scope="row">Background Color</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Active (Selected)</strong><br>
                            <input type="text" id="background_color_active"
                                   name="bulkboost_settings[background_color_active]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['background_color_active']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>Inactive (Not Selected)</strong><br>
                            <input type="text" id="background_color_inactive"
                                   name="bulkboost_settings[background_color_inactive]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['background_color_inactive']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>On Hover (Mouse over)</strong><br>
                            <input type="text" id="background_color_hover"
                                   name="bulkboost_settings[background_color_hover]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['background_color_hover']); ?>"
                                   class="color-field"/>
                        </div>
                    </td>
                </tr>
                <!-- Text Color -->
                <tr valign="top" style="border-bottom: 1px solid grey;">
                    <th scope="row">Text Color</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Active (Selected)</strong><br>
                            <input type="text" id="text_color_active"
                                   name="bulkboost_settings[text_color_active]"
                                   value="<?php
                                   echo esc_attr(get_option('bulkboost_settings')['text_color_active']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>Inactive (Not Selected)</strong><br>
                            <input type="text" id="text_color_inactive"
                                   name="bulkboost_settings[text_color_inactive]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['text_color_inactive']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>On Hover (Mouse over)</strong><br>
                            <input type="text" id="text_color_hover"
                                   name="bulkboost_settings[text_color_hover]"
                                   value="<?php
                                   echo esc_attr(get_option('bulkboost_settings')['text_color_hover']); ?>"
                                   class="color-field"/>
                        </div>
                    </td>
                </tr>
                <!-- Button Style -->
                <tr valign="top" style="border-bottom: 1px solid grey;">
                    <th scope="row">Radio Button Styling</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Active Background Color (Selected)</strong><br>
                            <input type="text" id="radio_bg_color_active"
                                   name="bulkboost_settings[radio_bg_color_active]"
                                   value="<?php
                                   echo esc_attr(
                                       get_option('bulkboost_settings')['radio_bg_color_active']
                                   ); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>Active Border Color (Selected)</strong><br>
                            <input type="text" id="radio_border_color_active"
                                   name="bulkboost_settings[radio_border_color_active]"
                                   value="<?php
                                   echo esc_attr(
                                       get_option('bulkboost_settings')['radio_border_color_active']
                                   ); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>Inactive Border Color (Not Selected)</strong><br>
                            <input type="text" id="radio_border_color_inactive"
                                   name="bulkboost_settings[radio_border_color_inactive]" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['radio_border_color_inactive']); ?>"
                                   class="color-field"/>
                        </div>
                        <div class="blocks_3">
                            <strong>Radio Button Size</strong><br>
                            <input type="number" id="radio_button_size"
                                   name="bulkboost_settings[radio_button_size]"
                                   value="<?php
                                   echo esc_attr(
                                       get_option('bulkboost_settings')['radio_button_size']
                                   ) ?: '20'; ?>"/>
                        </div>
                    </td>
                </tr>
                <!-- Border Style -->
                <tr valign="top">
                    <th scope="row">Border Style</th>
                    <td>
                        <select id="border_style" name="bulkboost_settings[border_style]">
                            <option value="none" <?php
                            selected(get_option('bulkboost_settings')['border_style'], 'none'); ?> >None
                            </option>
                            <option value="dashed" <?php
                            selected(get_option('bulkboost_settings')['border_style'], 'dashed'); ?> >Dashed
                            </option>
                            <option value="dotted" <?php
                            selected(get_option('bulkboost_settings')['border_style'], 'dotted'); ?> >Dotted
                            </option>
                            <option value="solid" <?php
                            selected(get_option('bulkboost_settings')['border_style'], 'solid'); ?> >Solid
                            </option>
                        </select>
                    </td>
                </tr>
                <!-- Box Corner Radius -->
                <tr valign="top">
                    <th scope="row">Box Corner Radius</th>
                    <td>
                        <input type="number" id="box_corner_radius"
                               name="bulkboost_settings[box_corner_radius]"
                               value="<?php
                               echo esc_attr(get_option('bulkboost_settings')['box_corner_radius']); ?>"/>
                    </td>
                </tr>
            </table>
        </div>
        <div id="badge" class="tab-content" style="display:none;">
            <p style="max-width:920px; margin:30px auto 5px auto;">
                Badges play a crucial role in guiding customer purchases, highlighting key product features or
                promotions.
            </p>
            <p style="max-width:920px; margin:5px auto;">
                Badges can positively influence buyer behavior, boosting sales and building trust.
            </p>
            <p style="max-width:920px; margin:5px auto;">
                In terms of pricing, badges that emphasize discounts or limited-time offers can create urgency, but it’s
                vital for businesses to use them responsibly to avoid badge fatigue and maintain credibility.</p>
            <p style="max-width:920px; margin:5px auto; text-align: center;">
                <strong><a href="" style="">Try Premium 14 days For FREE Now!</a></strong></p>
            <img src="<?php
            echo esc_url(plugin_dir_url(__FILE__) . 'img/badge_example.png'); ?>" alt=""
                 style="max-width:600px; display:block; margin:0 auto;">
        </div>
        <div id="typographic" class="tab-content" style="display:none;">
            <table class="form-table">
                <!-- Title Settings -->
                <tr valign="top">
                    <th scope="row">Label</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Font Weight</strong><br>
                            <select id="label_font_weight" name="bulkboost_settings[label_font_weight]">
                                <?php
                                $label_font_weight = get_option('bulkboost_settings')['label_font_weight'];
                                $current_value = $label_font_weight ? $label_font_weight : '300';
                                ?>
                                <option <?php
                                selected($current_value, '100'); ?> value="100">Thin
                                </option>
                                <option <?php
                                selected($current_value, '200'); ?> value="200">Extra Light (Ultra Light)
                                </option>
                                <option <?php
                                selected($current_value, '300'); ?> value="300">Light
                                </option>
                                <option <?php
                                selected($current_value, '400'); ?> value="400">Normal
                                </option>
                                <option <?php
                                selected($current_value, '500'); ?> value="500">Medium
                                </option>
                                <option <?php
                                selected($current_value, '600'); ?> value="600">Semi Bold (Demi Bold)
                                </option>
                                <option <?php
                                selected($current_value, '700'); ?> value="700">Bold
                                </option>
                                <option <?php
                                selected($current_value, '800'); ?> value="800"> Extra Bold (Ultra Bold)
                                </option>
                            </select>
                        </div>
                        <div class="blocks_3">
                            <strong>Font Size</strong><br>
                            <input type="number" id="label_font_size"
                                   name="bulkboost_settings[label_font_size]" min="5" max="60" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['label_font_size']) ?: '16'; ?>"/>
                        </div>
                    </td>
                </tr>
                <!-- Description Settings -->
                <tr valign="top">
                    <th scope="row">Description</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Font Weight</strong><br>
                            <select id="description_font_weight"
                                    name="bulkboost_settings[description_font_weight]">
                                <?php
                                $description_font_weight = get_option(
                                    'bulkboost_settings'
                                )['description_font_weight'];
                                $current_value = $description_font_weight ? $description_font_weight : '300';
                                ?>
                                <option <?php
                                selected($current_value, '100'); ?> value="100">Thin
                                </option>
                                <option <?php
                                selected($current_value, '200'); ?> value="200">Extra Light (Ultra Light)
                                </option>
                                <option <?php
                                selected($current_value, '300'); ?> value="300">Light
                                </option>
                                <option <?php
                                selected($current_value, '400'); ?> value="400">Normal
                                </option>
                                <option <?php
                                selected($current_value, '500'); ?> value="500">Medium
                                </option>
                                <option <?php
                                selected($current_value, '600'); ?> value="600">Semi Bold (Demi Bold)
                                </option>
                                <option <?php
                                selected($current_value, '700'); ?> value="700">Bold
                                </option>
                                <option <?php
                                selected($current_value, '800'); ?> value="800">Extra Bold (Ultra Bold)
                                </option>
                            </select>
                        </div>
                        <div class="blocks_3">
                            <strong>Font Size</strong><br>
                            <input type="number" id="description_font_size"
                                   name="bulkboost_settings[description_font_size]" min="5" max="60"
                                   value="<?php
                                   echo esc_attr(
                                       get_option('bulkboost_settings')['description_font_size']
                                   ) ?: '13'; ?>"/>
                        </div>
                    </td>
                </tr>

                <!-- Price Settings -->
                <tr valign="top">
                    <th scope="row">Price</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Font Weight</strong><br>
                            <select id="price_font_weight" name="bulkboost_settings[price_font_weight]">
                                <?php
                                $price_font_weight = get_option('bulkboost_settings')['price_font_weight'];
                                $current_value = $price_font_weight ? $price_font_weight : '300';
                                ?>
                                <option <?php
                                selected($current_value, '100'); ?> value="100">Thin
                                </option>
                                <option <?php
                                selected($current_value, '200'); ?> value="200">Extra Light (Ultra Light)
                                </option>
                                <option <?php
                                selected($current_value, '300'); ?> value="300">Light
                                </option>
                                <option <?php
                                selected($current_value, '400'); ?> value="400">Normal
                                </option>
                                <option <?php
                                selected($current_value, '500'); ?> value="500">Medium
                                </option>
                                <option <?php
                                selected($current_value, '600'); ?> value="600">Semi Bold (Demi Bold)
                                </option>
                                <option <?php
                                selected($current_value, '700'); ?> value="700">Bold
                                </option>
                                <option <?php
                                selected($current_value, '800'); ?> value="800">Extra Bold (Ultra Bold)
                                </option>
                            </select>
                        </div>
                        <div class="blocks_3">
                            <strong>Font Size</strong><br>
                            <input type="number" id="price_font_size"
                                   name="bulkboost_settings[price_font_size]" min="5" max="60" value="<?php
                            echo esc_attr(get_option('bulkboost_settings')['price_font_size']) ?: '16'; ?>"/>
                        </div>
                    </td>
                </tr>

                <!-- Old Price Settings -->
                <tr valign="top">
                    <th scope="row">Old Price</th>
                    <td>
                        <div class="blocks_3">
                            <strong>Show Old Price</strong><br>
                            <select id="show_old_price" name="bulkboost_settings[show_old_price]">
                                <option value="yes" <?php
                                selected(get_option('bulkboost_settings')['show_old_price'], 'yes'); ?>>Yes
                                </option>
                                <option value="no" <?php
                                selected(get_option('bulkboost_settings')['show_old_price'], 'no'); ?>>No
                                </option>
                            </select>
                        </div>
                        <div class="blocks_3 old_price_settings">
                            <strong>Font Weight</strong><br>
                            <select id="old_price_font_weight"
                                    name="bulkboost_settings[old_price_font_weight]">
                                <?php
                                $old_price_font_weight = get_option(
                                    'bulkboost_settings'
                                )['old_price_font_weight'];
                                $current_value = $old_price_font_weight ? $old_price_font_weight : '300';
                                ?>
                                <option <?php
                                selected($current_value, '100'); ?> value="100">Thin
                                </option>
                                <option <?php
                                selected($current_value, '200'); ?> value="200">Extra Light (Ultra Light)
                                </option>
                                <option <?php
                                selected($current_value, '300'); ?> value="300">Light
                                </option>
                                <option <?php
                                selected($current_value, '400'); ?> value="400">Normal
                                </option>
                                <option <?php
                                selected($current_value, '500'); ?> value="500">Medium
                                </option>
                                <option <?php
                                selected($current_value, '600'); ?> value="600">Semi Bold (Demi Bold)
                                </option>
                                <option <?php
                                selected($current_value, '700'); ?> value="700">Bold
                                </option>
                                <option <?php
                                selected($current_value, '800'); ?> value="800">Extra Bold (Ultra Bold)
                                </option>
                            </select>
                        </div>
                        <div class="blocks_3 old_price_settings">
                            <strong>Font Size</strong><br>
                            <input type="number" id="old_price_font_size"
                                   name="bulkboost_settings[old_price_font_size]" min="5" max="60" value="<?php
                            echo esc_attr(
                                get_option('bulkboost_settings')['old_price_font_size']
                            ) ?: '12'; ?>"/>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="general-settings" class="tab-content" style="display:none;">
            <!-- General Settings Content -->
        </div>
        <?php
        submit_button(); ?>
    </form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {

        $('.nav-tab').click(function () {
            var tab_id = $(this).attr('href');

            $('.nav-tab').removeClass('nav-tab-active');
            $('.tab-content').hide();

            $(this).addClass('nav-tab-active');
            $(tab_id).show();

            return false;
        });

        function updatePreview() {
            var borderColorActive = $('#border_color_active').val();
            var borderColorInactive = $('#border_color_inactive').val();
            var borderColorHover = $('#border_color_hover').val();
            var backgroundColorActive = $('#background_color_active').val();
            var backgroundColorInactive = $('#background_color_inactive').val();
            var backgroundColorHover = $('#background_color_hover').val();
            var textColorActive = $('#text_color_active').val();
            var textColorInactive = $('#text_color_inactive').val();
            var textColorHover = $('#text_color_hover').val();
            var borderStyle = $('#border_style').val();
            var boxCornerRadius = $('#box_corner_radius').val();

            // radio button
            var radio_bg_color_active = $('#radio_bg_color_active').val();
            var radio_bg_color_inactive = $('#radio_bg_color_inactive').val();
            var radio_bg_color_hover = $('#radio_bg_color_hover').val();
            var radio_border_color_active = $('#radio_border_color_active').val();
            var radio_border_color_inactive = $('#radio_border_color_inactive').val();

            var radio_border_color_hover = $('#radio_border_color_hover').val();

            // Typographic settings
            var labelFontSize = $('#label_font_size').val() + 'px';
            var labelFontWeight = $('#label_font_weight').val();
            var descriptionFontSize = $('#description_font_size').val() + 'px';
            var descriptionFontWeight = $('#description_font_weight').val();
            var priceFontSize = $('#price_font_size').val() + 'px';
            var priceFontWeight = $('#price_font_weight').val();
            var oldPriceFontSize = $('#old_price_font_size').val() + 'px';
            var oldPriceFontWeight = $('#old_price_font_weight').val();
            var radioButtonSize = $('#radio_button_size').val();

            var dynamicStyles = `
                .bulkboost-swatch.active {
                    border-color: ${borderColorActive};
                    background-color: ${backgroundColorActive};
                    color: ${textColorActive};
                    border-style: ${borderStyle};
                    border-radius: ${boxCornerRadius}px;
                }
                .bulkboost-radio span {
                    border-color: ${radio_border_color_inactive};
                }
                .bulkboost-radio input[type="radio"]:checked + span {
                    border-color: ${radio_border_color_active};
                }
                .bulkboost-swatch.active .bulkboost-radio span {
                    border-color: ${radio_border_color_active};
                }
                .bulkboost-swatch:not(.active) {
                    border-color: ${borderColorInactive};
                    background-color: ${backgroundColorInactive} !important;
                    color: ${textColorInactive};
                    border-style: ${borderStyle};
                    border-radius: ${boxCornerRadius}px;
                }
                .bulkboost-heading {
                    font-size: ${labelFontSize};
                    font-weight: ${labelFontWeight};
                }
                .bulkboost-subheading {
                    font-size: ${descriptionFontSize};
                    font-weight: ${descriptionFontWeight};
                }
                .bulkboost-right span {
                    font-size: ${priceFontSize};
                    font-weight: ${priceFontWeight};
                }
                .bulkboost-right .old-price span {
                    font-size: ${oldPriceFontSize};
                    font-weight: ${oldPriceFontWeight};
                }
                .bulkboost-radio input[type="radio"]:checked + span::before{
                    background-color: ${radio_bg_color_active}
                }
                .bulkboost-radio input[type="radio"] + span::before{
                    background-color: ${radio_bg_color_inactive}
                }
                .bulkboost-radio span {
                    display: inline-block;
                    height: ${radioButtonSize}px;
                    width: ${radioButtonSize}px;
                    border-width: 1px;
                    border-style: solid;
                    border-radius: 50%;
                    position: relative;
                    cursor: pointer;
                    vertical-align: middle;
                }

                .bulkboost-radio input[type="radio"]:checked + span::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    height: calc(${radioButtonSize}px - 5px);
                    width: calc(${radioButtonSize}px - 5px);
                    border-radius: 50%;
                }
            `;

            setDynamicStyles(dynamicStyles);

        }

        function setDynamicStyles(dynamicStyles) {
            var styleSheet = document.getElementById("dynamicStyles");

            // If it doesn't exist, create it and append it to the <head>
            if (!styleSheet) {
                styleSheet = document.createElement("style");
                styleSheet.id = "dynamicStyles";
                document.head.appendChild(styleSheet);
            }

            styleSheet.innerHTML = dynamicStyles;
        }

        function setActiveSwatch(swatch) {
            // Remove active class and uncheck radio button from all swatches
            $('.bulkboost-swatch').removeClass('active').find('input[type="radio"]').prop('checked', false);

            // Add active class and check radio button for the clicked swatch
            swatch.addClass('active').find('input[type="radio"]').prop('checked', true);

            // Update the preview
            updatePreview();
        }


        $('.bulkboost-swatch').on('click', function () {
            setActiveSwatch($(this));
        });

        $('.color-field').wpColorPicker({
            change: function (event, ui) {
                // This function is called whenever a color is selected in the color picker.
                var element = $(event.target);
                var color = ui.color.toString();
                element.val(color);
                updatePreview();

            },
            clear: function (event) {
                // This function is called whenever the "Clear" button is clicked in the color picker.
                var element = $(event.target).closest('.wp-picker-input-wrap').find('.wp-color-picker');
                element.val('');
                updatePreview();
            }
        });

        // Update preview on input change
        $('input#label_font_size, select#label_font_weight, input#description_font_size, input#radio_button_size, select#description_font_weight, input#price_font_size, select#price_font_weight, input#old_price_font_size, select#old_price_font_weight').on('input change', function () {
            updatePreview();
        });

        $('select#border_style, input#box_corner_radius').on('input change', function () {
            updatePreview();
        });

        function toggleOldPriceSettings() {
            $('#show_old_price').on('change', function () {
                if ($(this).val() === 'no') {
                    $('.old-price').hide();
                } else {
                    $('.old-price').show();
                }
            });
            if ($('#show_old_price').val() === 'no') {
                $('.old-price').hide();
            } else {
                $('.old-price').show();
            }
        }

        $('#show_old_price').on('change', function () {
            toggleOldPriceSettings();
        });

        // Initial toggle on page load
        toggleOldPriceSettings();

        updatePreview();

        setActiveSwatch($('.bulkboost-swatch.active'));

    });


</script>
<style>
    .selected {
        background-color: #f0f0f0;
    }
</style>
<style id="dynamicStyles"></style>