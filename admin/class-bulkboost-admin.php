<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://bulkboost.com
 * @since      1.0.0
 *
 * @package    BulkBoost
 * @subpackage BulkBoost/admin
 */
class BLKBST_BulkBoost_Admin
{

    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/bulkboost-admin.css',
            array(),
            time(),
            'all'
        );
    }

    public function enqueue_scripts()
    {
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/bulkboost-admin.min.js',
            array('jquery'),
            time(),
            false
        );
        if (class_exists('WooCommerce')) {
            $currency_symbol = get_woocommerce_currency_symbol();

            $bulkboost_settings = get_option('bulkboost_settings', array());
            $qualityDiscountsMinMaxSettings = get_option('min_max_bulkboost_settings', []);

            $formatted_price_placeholder = str_replace('0', '%s', wc_price(0));

            $bulkboost_settings['currencySymbol'] = $currency_symbol;
            $bulkboost_settings['formattedPricePlaceholder'] = $formatted_price_placeholder;
            $bulkboost_settings['min_max'] = $qualityDiscountsMinMaxSettings;
            wp_localize_script($this->plugin_name, 'quantityDiscountsSettings', $bulkboost_settings);
        }
    }

    public function BLKBST_bulkboost_admin_menu_page()
    {
        add_menu_page(
            'Design settings',
            'BulkBoost',
            'administrator',
            'bulkboost-' . $this->plugin_name,
            array($this, 'displayPluginAdminDashboard'),
            'dashicons-money-alt',
            26
        );

        add_submenu_page(
            'bulkboost-' . $this->plugin_name,
            'Bundle Settings',
            'Bundle Settings',
            'administrator',
            'bulkboost-quantity-design',
            array($this, 'displayPluginAdminQuantityDesign')
        );

        add_submenu_page(
            'bulkboost-' . $this->plugin_name,
            'Min-Max Settings',
            'Min-Max Settings',
            'administrator',
            'bulkboost-quantity-min-max',
            array($this, 'displayPluginAdminMinMax')
        );

        add_submenu_page(
            'bulkboost-' . $this->plugin_name,
            'Earnings',
            'Earnings',
            'administrator',
            'bulkboost-earnings',
            array($this, 'displayPluginAdminEarnings')
        );

        add_submenu_page(
            'bulkboost-' . $this->plugin_name,
            'General Settings',
            'General Settings',
            'administrator',
            'bulkboost-settings',
            array($this, 'displayPluginAdminSettings')
        );
    }

    public function submenuPageCallback()
    {
        echo '<div class="wrap"><h2>Submenu Page Title</h2><p>This is the content of the submenu page.</p></div>';
    }

    public function displayPluginAdminGift()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display-gift.php';
    }

    public function displayPluginAdminSettings()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display-settings.php';
    }

    public function displayPluginAdminDashboard()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display.php';
    }

    public function displayPluginAdminQuantityDesign()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display-quantity-design.php';
    }

    public function displayPluginAdminMinMax()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display-min-max.php';
    }

    public function displayPluginAdminEarnings()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display-earnings.php';
    }

    public function handle_form_submission()
    {
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);

        if (!is_email($email)) {
            wp_die('Invalid email address.');
        }

        $url = 'https://api.mailerlite.com/api/v2/groups/108373864349697182/subscribers';
        $apiKey = 'YOUR_MAILERLITE_API_KEY'; // Replace with your MailerLite API key

        $subscriberData = wp_json_encode([
            'email' => $email,
            'name' => $name
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $subscriberData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-MailerLite-ApiKey: ' . $apiKey
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode === 200) {
            wp_redirect(add_query_arg('success', '1', wp_get_referer()));
        } else {
            wp_die('An error occurred: ' . esc_html($response));
        }

        exit;
    }

    public function BLKBST_bulkboost_product_data_panels__premium_only()
    {
        ?>
        <div id="quantity_breaks" class="panel woocommerce_options_panel hidden"></div>
        <?php
    }

    public function BLKBST_bulkboost_product_data_tabs__premium_only($tabs)
    {
        $tabs['bulkboost'] = [
            'label' => __('BulkBoost', 'bulkboost-bulkboost'),
            'target' => 'bulkboost',
            'class' => ['hide_if_variable', 'hide_if_external', 'hide_if_grouped'],
            'priority' => 70
        ];
        return $tabs;
    }

    public function quantity_breaks_icon_change()
    {
        echo '<style>
                    #woocommerce-product-data ul.wc-tabs li.bulkboost_options a::before {
                        content: "\f18e";
                    } 
                </style>';
    }

    public function BLKBST_quantity_breaks_product_data_panels__premium_only($post_id)
    {
        $this->output_custom_styles();
        ?>
        <div id="bulkboost" class="panel woocommerce_options_panel hidden">

            <div id="bulkboost_notice" class="inline notice woocommerce-message is-dismissible" style="width:90%; margin:10px auto; position:realtive; display: inline-blockl;">
                <p style="margin:0;">
                    Want to try Premium? Start <strong>Free Trial</strong>!
                    <a href="https://bulkboost.com/products/quantity-breaks-and-discounts/" target="_blank">Click here to try it now</a>.
                </p>
            </div>

            <ul class="tabs" style="margin-bottom:10px;">
                <li class="quantity_settings_tab active">
                    <a href="#quantity_settings" class="active">Settings</a>
                </li>
                <li class="quantity_pricing_tab active">
                    <a href="#quantity_pricing">Quantity Pricing</a>
                </li>
                <li class="preview_tab">
                    <a href="#preview">Preview</a>
                </li>
            </ul>
            <div id="quantity_settings" class="panel active">
                <div style="padding:0 20px">
                    <!-- BulkBoost Setting -->
                    <div>
                        <h4 style="margin:0;">BulkBoost Blocks:</h4>
                        <input type="radio" name="_bulkboost_qd_quantity_enabled" value="enable"> Enable
                        <input type="radio" name="_bulkboost_qd_quantity_enabled" value="disable"> Disable
                    </div>
                    <hr>
                    <!-- Min-Max Quantity Selection -->
                    <div>
                        <h4 style="margin:0;">Min-Max Quantity selection:</h4>
                        <input type="radio" name="_bulkboost_qd_min_max_enabled" value="enable"> Enable
                        <input type="radio" name="_bulkboost_qd_min_max_enabled" value="disable"> Disable
                    </div>
                    <hr>
                    <!-- Min Max Value Fields -->
                    <div id="min_max_values" style="display:none;">
                        <h4 style="margin:0;">Min-Max Quantity values:</h4>
                        <input type="number" name="_bulkboost_qd_min_value" placeholder="Minimum Value" value="">
                        <input type="number" name="_bulkboost_qd_max_value" placeholder="Maximum Value" value="">
                    </div>
                    <br>
                    <!-- Display Method -->
                    <div id="display_method" style="display:none">
                        <br>
                        <input type="radio" style="display: none;" name="_bulkboost_qd_display_method" disabled
                               value="dropdown">
                        <input type="radio" style="display: none;" name="_bulkboost_qd_display_method" value="buttons">
                    </div>
                </div>
            </div>
            <div id="quantity_pricing" class="panel">
                <div id="bulkboost_container"></div>
                <div style="padding:20px">
                    <button type="button" id="add_quantity_discount" class="button">Add Quantity Discount</button>
                </div>
            </div>
            <div id="preview" class="panel hidden">
                <div style="padding:0 10px 0 10px; margin-top:0;">
                    <div id="bulkboost_notice" class="inline notice woocommerce-message is-dismissible">
                        <p style="margin:0;">This is how it will look like on your product page!</p>
                    </div>
                    <div id="bulkboost_notice_customise"
                         class="inline notice woocommerce-message is-dismissible">
                        <p style="margin:0;">You can edit the design of this view! <a
                                    href="admin.php?page=bulkboost-quantity-design">Click here to customise it!</a>.
                        </p>
                    </div>
                    <div id="minmax_notice_customise" class="inline notice woocommerce-message is-dismissible">
                        <p style="margin:0;">You can edit the design of this view! <a
                                    href="admin.php?page=bulkboost-quantity-min-max">Click here to customise it!</a>.
                        </p>
                    </div>
                </div>
                <div class="preview-block">
                    <div id="bulkboost_preview"></div>
                    <div id="min_max_preview">
                        <div id="minmax_preview"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function BLKBST_save_bulkboost($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = [
            '_bulkboost_qd_quantity',
            '_bulkboost_qd_price',
            '_bulkboost_qd_label',
            '_bulkboost_qd_description',
            '_bulkboost_qd_quantity_enabled',
            '_bulkboost_qd_min_max_enabled',
            '_bulkboost_qd_min_value',
            '_bulkboost_qd_max_value',
            '_bulkboost_qd_badge_text',
            // --- Badge fields (per quantity-discount block) ---
            '_bulkboost_qd_badge_label',          // none|hot|popular|bestdeal
            '_bulkboost_qd_badge_free_shipping',   // yes|no
            '_bulkboost_qd_badge_save_enabled',    // yes|no
            '_bulkboost_qd_badge_save_override',   // manual % text override, blank = auto-calc
        ];
        $error = false;

        if (!$_POST['_bulkboost_qd_quantity']) {
            $error = true;
        }

        if ($error) {
            set_transient('bulkboost_error', 'Quantity and Price fields cannot be empty.', 45);
            return;
        }

        foreach ($_POST['_bulkboost_qd_quantity'] as $index => $quantity) {
            $price = $_POST['_bulkboost_qd_price'][$index];
            if (empty($quantity) || empty($price)) {
                $error = true;
                break;
            }
        }

        if ($error) {
            set_transient('bulkboost_error', 'Quantity and Price fields cannot be empty.', 45);
            return;
        }

        $block_count = count($_POST['_bulkboost_qd_quantity']);

        // If no error, save the fields
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $_POST[$field];

                // Sanitize and validate specific fields if needed
                if (in_array($field, ['_bulkboost_qd_min_value', '_bulkboost_qd_max_value'])) {
                    $value = (int)$value;
                } elseif (in_array($field, [
                    '_bulkboost_qd_badge_label',
                    '_bulkboost_qd_badge_free_shipping',
                    '_bulkboost_qd_badge_save_enabled',
                    '_bulkboost_qd_badge_save_override',
                    '_bulkboost_qd_label',
                    '_bulkboost_qd_description',
                    '_bulkboost_qd_badge_text',
                ]) && is_array($value)) {
                    $value = array_map('sanitize_text_field', $value);
                }

                update_post_meta($post_id, $field, $value);
            } elseif (in_array($field, [
                '_bulkboost_qd_badge_label',
                '_bulkboost_qd_badge_free_shipping',
                '_bulkboost_qd_badge_save_enabled',
                '_bulkboost_qd_badge_save_override',
            ])) {
                // Checkboxes/selects that weren't submitted for some blocks still need
                // to be normalised to a value per block so indexes stay aligned with
                // quantity/price arrays. Default everything to "off"/"none"/empty.
                $default = ($field === '_bulkboost_qd_badge_label') ? 'none' : (
                    ($field === '_bulkboost_qd_badge_save_override') ? '' : 'no'
                );
                update_post_meta($post_id, $field, array_fill(0, $block_count, $default));
            }
        }
    }


    function BLKBST_bulkboost_admin_notices()
    {
        if ($message = get_transient('bulkboost_error')) {
            echo '<div class="notice notice-error is-dismissible"><h3>BulkBoost</h3><p>' . esc_html(
                    $message
                ) . '</p></div>';
            delete_transient('bulkboost_error');
        }
    }

    public function links_to_menu($links)
    {
        $url = "https://bulkboost.com/products/quantity-breaks-and-discounts/#pricing";
        $url2 = "admin.php?page=bulkboost-bulkboost";
        $url3 = "https://wordpress.org/support/plugin/bulkboost/reviews/#new-post";

        $settings_link = "<a href='$url2' ><b>" . __('Settings 🚀') . '</b></a>';
        $settings_link .= "| <a href='$url3' target='_blank'><strong style='display:inline;'>" . __('Review us') . '</strong></a>';
        $settings_link .= " | <a href='$url' style='font-weight: bold; color: green;' target='_blank'>" . __(
                'Get Premium'
            ) . '</a>';

        $links[] = $settings_link;
        return $links;
    }

    function BLKBST_enqueue_quantity_breaks_scripts($hook)
    {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        global $post;

        $bulkboost_extra = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('my-nonce')
        );


        if ($post) {
            $post_id = $post->ID;
            $bulkboost = array(
                'quantities' => get_post_meta($post_id, '_bulkboost_qd_quantity', true),
                'prices' => get_post_meta($post_id, '_bulkboost_qd_price', true),
                'labels' => get_post_meta($post_id, '_bulkboost_qd_label', true),
                'descriptions' => get_post_meta($post_id, '_bulkboost_qd_description', true),
                'quantity_enabled' => get_post_meta($post_id, '_bulkboost_qd_quantity_enabled', true),
                'display_method' => get_post_meta($post_id, '_bulkboost_qd_display_method', true),
                'min_max_enabled' => get_post_meta($post_id, '_bulkboost_qd_min_max_enabled', true),
                'min_value' => get_post_meta($post_id, '_bulkboost_qd_min_value', true),
                'max_value' => get_post_meta($post_id, '_bulkboost_qd_max_value', true),
                // --- Badge data passed to admin JS for the live preview ---
                'badge_labels' => get_post_meta($post_id, '_bulkboost_qd_badge_label', true),
                'badge_free_shipping' => get_post_meta($post_id, '_bulkboost_qd_badge_free_shipping', true),
                'badge_save_enabled' => get_post_meta($post_id, '_bulkboost_qd_badge_save_enabled', true),
                'badge_save_override' => get_post_meta($post_id, '_bulkboost_qd_badge_save_override', true),
            );

            wp_localize_script($this->plugin_name, 'bulkboost_data', $bulkboost);
        }

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/bulkboost-admin.js',
            array('jquery'),
            $this->version,
            false
        );
        wp_localize_script($this->plugin_name, 'bulkboost_data_extra', $bulkboost_extra);
    }

    function BLKBST_bulkboost_register_settings__premium_only()
    {
        register_setting(
            'bulkboost_settings',
            'bulkboost_settings',
            'bulkboost_settings_validate',
        );

        register_setting(
            'min_max_bulkboost_settings',
            'min_max_bulkboost_settings',
            'min_max_bulkboost_settings_validate',
        );
    }

    function bulkboost_settings_validate($input)
    {
        $input['border_color_active'] = sanitize_hex_color($input['border_color_active']);
        $input['border_color_inactive'] = sanitize_hex_color($input['border_color_inactive']);
        $input['border_color_hover'] = sanitize_hex_color($input['border_color_hover']);
        $input['background_color_active'] = sanitize_hex_color($input['background_color_active']);
        $input['background_color_inactive'] = sanitize_hex_color($input['background_color_inactive']);
        $input['background_color_hover'] = sanitize_hex_color($input['background_color_hover']);
        $input['text_color_active'] = sanitize_hex_color($input['text_color_active']);
        $input['text_color_inactive'] = sanitize_hex_color($input['text_color_inactive']);
        $input['text_color_hover'] = sanitize_hex_color($input['text_color_hover']);

        $input['radio_bg_color_active'] = sanitize_hex_color($input['radio_bg_color_active']);
        $input['radio_bg_color_inactive'] = sanitize_hex_color($input['radio_bg_color_inactive']);
        $input['radio_bg_color_hover'] = sanitize_hex_color($input['radio_bg_color_hover']);
        $input['radio_border_color_active'] = sanitize_hex_color($input['radio_border_color_active']);
        $input['radio_border_color_inactive'] = sanitize_hex_color($input['radio_border_color_inactive']);
        $input['radio_button_size'] = sanitize_hex_color($input['radio_button_size']);
        $input['radio_border_color_hover'] = sanitize_hex_color($input['radio_border_color_hover']);


        $input['border_style'] = sanitize_text_field($input['border_style']);
        $input['box_corner_radius'] = absint($input['box_corner_radius']);
        $input['labelFontWeight'] = absint($input['labelFontWeight']);
        $input['labelFontSize'] = absint($input['labelFontSize']);
        $input['descriptionFontWeight'] = absint($input['descriptionFontWeight']);
        $input['descriptionFontSize'] = absint($input['descriptionFontSize']);
        $input['priceFontWeight'] = absint($input['priceFontWeight']);
        $input['priceFontSize'] = absint($input['priceFontSize']);
        $input['oldPriceFontWeight'] = absint($input['oldPriceFontWeight']);

        return $input;
    }

    function min_max_bulkboost_settings_validate($input)
    {
        $input['min_max_background_color_active'] = sanitize_text_field($input['min_max_background_color_active']);
        $input['min_max_background_color_inactive'] = sanitize_text_field($input['min_max_background_color_inactive']);
        $input['min_max_background_color_hover'] = sanitize_text_field($input['min_max_background_color_hover']);
        $input['min_max_text_color_active'] = sanitize_text_field($input['min_max_text_color_active']);
        $input['min_max_text_color_inactive'] = sanitize_text_field($input['min_max_text_color_inactive']);
        $input['min_max_text_color_hover'] = sanitize_text_field($input['min_max_text_color_hover']);
        $input['min_max_border_color_active'] = sanitize_text_field($input['min_max_border_color_active']);
        $input['min_max_border_color_inactive'] = sanitize_text_field($input['min_max_border_color_inactive']);
        $input['min_max_border_color_hover'] = sanitize_text_field($input['min_max_border_color_hover']);
        $input['min_max_size'] = sanitize_text_field($input['min_max_size']);

        return $input;
    }

    function output_custom_styles()
    {
        $bulkboost_settings = get_option('bulkboost_settings');
        $minMaxQuantitySettings = get_option('min_max_bulkboost_settings');

        $border_style = esc_html($bulkboost_settings['border_style']);
        $box_corner_radius = esc_html($bulkboost_settings['box_corner_radius']);
        $border_color_inactive = esc_html($bulkboost_settings['border_color_inactive']);
        $background_color_inactive = esc_html($bulkboost_settings['background_color_inactive']);
        $text_color_inactive = esc_html($bulkboost_settings['text_color_inactive']);
        $border_color_active = esc_html($bulkboost_settings['border_color_active']);
        $background_color_active = esc_html($bulkboost_settings['background_color_active']);
        $text_color_active = esc_html($bulkboost_settings['text_color_active']);
        $border_color_hover = esc_html($bulkboost_settings['border_color_hover']);
        $background_color_hover = esc_html($bulkboost_settings['background_color_hover']);
        $text_color_hover = esc_html($bulkboost_settings['text_color_hover']);

        $radio_bg_color_active = esc_html($bulkboost_settings['radio_bg_color_active']);
        $radio_bg_color_inactive = esc_html($bulkboost_settings['radio_bg_color_inactive'] ?? '');
        $radio_bg_color_hover = esc_html($bulkboost_settings['radio_bg_color_hover'] ?? '');
        $radio_border_color_active = esc_html($bulkboost_settings['radio_border_color_active'] ?? '');
        $radio_border_color_inactive = esc_html($bulkboost_settings['radio_border_color_inactive']);
        $radio_border_color_hover = esc_html($bulkboost_settings['radio_border_color_hover'] ?? '');
        $radio_button_size = esc_html($bulkboost_settings['radio_button_size']);

        $labelFontWeight = esc_html($bulkboost_settings['label_font_weight']);
        $labelFontSize = esc_html($bulkboost_settings['label_font_size']);
        $descriptionFontWeight = esc_html($bulkboost_settings['description_font_weight']);
        $descriptionFontSize = esc_html($bulkboost_settings['description_font_size']);
        $priceFontWeight = esc_html($bulkboost_settings['price_font_weight']);
        $priceFontSize = esc_html($bulkboost_settings['price_font_size']);
        $oldPriceFontWeight = esc_html($bulkboost_settings['old_price_font_weight']);
        $oldPriceFontSize = esc_html($bulkboost_settings['old_price_font_size']);
        $showOldPrice = esc_html($bulkboost_settings['show_old_price']);

        $minMaxBgColorActive = esc_html($minMaxQuantitySettings['min_max_background_color_active']);
        $minMaxBgColorInactive = esc_html($minMaxQuantitySettings['min_max_background_color_inactive']);
        $minMaxBgColorHover = esc_html($minMaxQuantitySettings['min_max_background_color_hover']);
        $minMaxTextColorActive = esc_html($minMaxQuantitySettings['min_max_text_color_active']);
        $minMaxTextColorInactive = esc_html($minMaxQuantitySettings['min_max_text_color_inactive']);
        $minMaxTextColorHover = esc_html($minMaxQuantitySettings['min_max_text_color_hover']);
        $minMaxBorderColorActive = esc_html($minMaxQuantitySettings['min_max_border_color_active']);
        $minMaxBorderColorInactive = esc_html($minMaxQuantitySettings['min_max_border_color_inactive']);
        $minMaxBorderColorHover = esc_html($minMaxQuantitySettings['min_max_border_color_hover']);
        $minMaxSize = esc_html($minMaxQuantitySettings['min_max_size']);
        $minMaxSizeHalf = esc_html($minMaxQuantitySettings['min_max_size']) / 2;

        $button_size = $radio_button_size - 5;

        echo "
    <style>
        .minmax-buttons{
            padding: " . esc_attr($minMaxSizeHalf) . "px " . esc_attr($minMaxSize) . "px;
            margin: 2px;
            display: inline-block;
            background-color: " . esc_attr($minMaxBgColorInactive) . ";
            color: " . esc_attr($minMaxTextColorInactive) . ";
            border: 1px solid " . esc_attr($minMaxBorderColorInactive) . ";
            font-size: 16px;
            cursor: pointer;
        }
        .minmax-buttons.active{
            background-color: " . esc_attr($minMaxBgColorActive) . ";
            color: " . esc_attr($minMaxTextColorActive) . ";
            borderColor: " . esc_attr($minMaxBorderColorActive) . ";
        }
        .minmax-buttons:hover{
            background-color: " . esc_attr($minMaxBgColorHover) . ";
            color: " . esc_attr($minMaxTextColorHover) . ";
            borderColor: " . esc_attr($minMaxBorderColorHover) . ";
        }
        .bulkboost-swatch {
            border-style: " . esc_attr($border_style) . ";
            border-radius: " . esc_attr($box_corner_radius) . "px;
            border-color: " . esc_attr($border_color_inactive) . ";
            background-color: " . esc_attr($background_color_inactive) . ";
            color: " . esc_attr($text_color_inactive) . ";
            transition: all 0.3s ease;
        }
        .bulkboost-radio span {
            display: inline-block;
            height: 20px;
            width: 20px;
            border: 2px solid " . esc_attr($radio_border_color_inactive) . ";
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            vertical-align: middle;
        }
        
        .bulkboost-radio input[type='radio']:checked + span::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 12px;
            width: 12px;
            border-radius: 50%;
            background: " . esc_attr($radio_bg_color_active) . ";
        }
        
        .bulkboost-radio input[type='radio']:checked + span {
            border-color: " . esc_attr($radio_bg_color_active) . ";
        }
        
        .bulkboost-radio span:hover {
            border-color: " . esc_attr($radio_border_color_hover) . ";
        }
        
        .bulkboost-swatch.active .bulkboost-radio span {
            border-color: green;
        }
        .bulkboost-heading{
            font-size: " . esc_attr($labelFontSize) . "px;
            font-weight: " . esc_attr($labelFontWeight) . ";
        }
        .bulkboost-subheading{
            font-size: " . esc_attr($descriptionFontSize) . "px;
            font-weight: " . esc_attr($descriptionFontWeight) . ";
        }
        .bulkboost-price{
            font-size: " . esc_attr($priceFontSize) . "px;
            font-weight: " . esc_attr($priceFontWeight) . ";
        }
        .bulkboost-right .old-price{
            font-size: " . esc_attr($oldPriceFontSize) . "px;
            font-weight: $oldPriceFontWeight;
            " . ($showOldPrice === 'no' ? 'display: none;' : '') . "
        }
        .bulkboost-swatch.active {
            border-color: " . esc_attr($border_color_active) . ";
            background-color: " . esc_attr($background_color_active) . ";
            color: " . esc_attr($text_color_active) . ";
        }
        .bulkboost-swatch:hover {
            border-color: " . esc_attr($border_color_hover) . ";
            background-color: " . esc_attr($background_color_hover) . ";
            color: " . esc_attr($text_color_hover) . ";
        }
        .bulkboost-radio span {
            display: inline-block;
            height: " . esc_attr($radio_button_size) . "px;
            width: " . esc_attr($radio_button_size) . "px;
            border-width: 1px;
            border-style: solid;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            vertical-align: middle;
        }

        .bulkboost-radio input[type='radio']:checked + span::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: " . esc_attr($button_size) . "px;
            width: " . esc_attr($button_size) . "px;
            border-radius: 50%;
        }
        /* Add other styles as needed */
    </style>
    ";
    }

    function BLKBST_update_notice()
    {
        global $current_user;

        $siteUrl = site_url();
        $uniqueUserId = md5($siteUrl);

        $api_url = 'https://uwozfs6rgi.execute-api.us-east-1.amazonaws.com/prod/notifications';
        $body = wp_json_encode([
            'pluginName' => 'bulkboost-quantity-breaks-free',
            'status' => true,
            'user_id' => $uniqueUserId
        ], JSON_THROW_ON_ERROR);

        $args = [
            'body' => $body,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'method' => 'POST',
            'data_format' => 'body',
        ];

        $response = wp_remote_post($api_url, $args);

        if (is_wp_error($response)) {
            $error_message = $response->get_error_message();
            return;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true, 512);
        $status_code = $data['statusCode'];

        if (!empty($data) && $status_code === 200 && $data['body'] !== '[]') {
            $dataEncoded = json_decode($data['body'], true)[0];
            if ($dataEncoded['content'] && $dataEncoded['dismissed'] === false) {
                $content = $dataEncoded['content'];
                $message_id = $dataEncoded['message_id'];

                ?>
                <div class="notice notice-success is-dismissible">
                    <?php
                    echo $content; ?>
                    <hr>
                    <a style="margin-bottom: 10px; position: relative; display: block;" href="?bulkboost-quantity-breaks_-notice&message_id=<?php echo urlencode($message_id); ?>"><b>Dismiss this notice</b></a>
                </div>
                <?php
            }
        }
    }

    public function BLKBST_ignore_notice_bulkboost()
    {
        global $current_user;

        $siteUrl = site_url();
        $uniqueUserId = md5($siteUrl);

        if (isset($_GET['bulkboost-quantity-breaks_-notice'])) {
            $message_id = $_GET['message_id'];
            $apiRequestBody = wp_json_encode(array(
                'user_id' => $uniqueUserId,
                'plugin_name' => 'bulkboost-quantity-breaks-free',
                'message_id' => $message_id,
            ), JSON_THROW_ON_ERROR);

            $apiResponse = wp_remote_post(
                'https://uwozfs6rgi.execute-api.us-east-1.amazonaws.com/prod/notifications',
                array(
                    'body' => $apiRequestBody,
                    'headers' => array(
                        'Content-Type' => 'application/json',
                    ),
                )
            );

            if (is_wp_error($apiResponse)) {
                $error_message = $apiResponse->get_error_message();
                return;
            }
        }
    }

}
