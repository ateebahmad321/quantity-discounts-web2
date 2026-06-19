<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://tallpro.com
 * @since      1.0.0
 *
 * @package    Quantity_Discounts
 * @subpackage Quantity_Discounts/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quantity_Discounts
 * @subpackage Quantity_Discounts/admin
 * @author     WPIron <info@tallpro.com>
 */
class WIRNQDSQ_Quantity_Discounts_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quantity_Discounts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quantity_Discounts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/quantity-discounts-admin.css',
            array(),
            time(),
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Quantity_Discounts_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Quantity_Discounts_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/quantity-discounts-admin.min.js',
            array('jquery'),
            time(),
            false
        );
        if (class_exists('WooCommerce')) {
            $currency_symbol = get_woocommerce_currency_symbol();

            $quantity_discounts_settings = get_option('quantity_discounts_settings', array());
            $qualityDiscountsMinMaxSettings = get_option('min_max_quantity_discounts_settings', []);

            $formatted_price_placeholder = str_replace('0', '%s', wc_price(0));

            $quantity_discounts_settings['currencySymbol'] = $currency_symbol;
            $quantity_discounts_settings['formattedPricePlaceholder'] = $formatted_price_placeholder;
            $quantity_discounts_settings['min_max'] = $qualityDiscountsMinMaxSettings;
            wp_localize_script($this->plugin_name, 'quantityDiscountsSettings', $quantity_discounts_settings);
        }
    }

    public function WIRNQDSQ_quantity_discounts_admin_menu_page()
    {
        add_menu_page(
            'Design settings',
            'Quantity Discounts',
            'administrator',
            'wpiron-' . $this->plugin_name,
            array($this, 'displayPluginAdminDashboard'),
            'dashicons-money-alt',
            26
        );

        add_submenu_page(
            'wpiron-' . $this->plugin_name, // Parent slug
            'Bundle Settings',
            'Bundle Settings',
            'administrator',
            'wpiron-quantity-design',
            array($this, 'displayPluginAdminQuantityDesign')
        );

        add_submenu_page(
            'wpiron-' . $this->plugin_name, // Parent slug
            'Min-Max Settings',
            'Min-Max Settings',
            'administrator',
            'wpiron-quantity-min-max',
            array($this, 'displayPluginAdminMinMax')
        );

        add_submenu_page(
            'wpiron-' . $this->plugin_name, // Parent slug
            'Earnings',
            'Earnings',
            'administrator',
            'wpiron-earnings',
            array($this, 'displayPluginAdminEarnings')
        );

	    add_submenu_page(
		    'wpiron-' . $this->plugin_name, // Parent slug
		    'General Settings',
		    'General Settings',
		    'administrator',
		    'wpiron-settings',
		    array( $this, 'displayPluginAdminSettings' )
	    );
    }

    public function submenuPageCallback()
    {
        // Add your submenu page content here
        echo '<div class="wrap"><h2>Submenu Page Title</h2><p>This is the content of the submenu page.</p></div>';
    }

    public function displayPluginAdminGift()
    {
        require_once 'partials/' . $this->plugin_name . '-admin-display-gift.php';
    }

	public function displayPluginAdminSettings() {
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
        $apiKey = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiN2E5ZDc4MGMyNDFmYWU0ODAyNDhmYmZjMjgwMGMxYzI3NzI0M2YwNzAzN2FkOTNmNmQ4YmQ5Y2I4YjlmMGE4NzExZjRiYTgzOGFiYzQ3MWYiLCJpYXQiOjE3MDYyOTU1MDMuNTI3OTIxLCJuYmYiOjE3MDYyOTU1MDMuNTI3OTI0LCJleHAiOjQ4NjE5NjkxMDMuNTI0MTAxLCJzdWIiOiI3NTcyNTUiLCJzY29wZXMiOltdfQ.bM1NS9FYoZ3mwk2k2xcrOwmXic09RDaX5MNAufeB-jRHn7Vp60_0xBdUpfZJwHxR_OcqZQCHTo0qY7f4SasEUi1O-1G01oSl1K9GgIenCPEcb3XJ-wTFLSZcFkS_EumYNTII3x0yn0LcrezZo8Cyc4PPa8UxqQ_I3WrtMS6Kuccjyetra1nR-7k9nWMZsJC-4f054DTd8t_p_1PA26D_j7N54whPBc_nphBUDpgNpxLb9nqpd5jxxOoVvwW-pjBidAcUXeUtBicvK9QAkOzcir5pBI7JCIMOIsy9F2kaUX8koFlG0PyunVihAY_vHTdo-974ZG-N7AuZfnnXx-D8D5E-LGmvlJuJKGj7xpE-qA4vcprCA_EAXF-KUuU3o3hIFYYtZ2XFuCB3NTkCrguxe-sEg7_7-1FReaXOkCj4R-Nkhw0O2kCVPoLZKzgf2AjrGC5JAVpb9cfg9r-54KGIIV51KrKTToqjNHj5tusY4funqThAWyUyKTz3y3k051DefqHH68pwIkdphT0Iu3R_ZMvp8-T9U-amaw05neiWbICqiT5_cCYveuV2nsqd_gQjP9YuH3l4wvdh7F1RCJvcsnKtlsG3FQQGMeYdCMYZBy9PFi1tZPztphmeAMenVliipJk9kzuSVZkvu5qvGs_Ric0izJ2mZS50ewdh4UcC7aE'; // Replace with your MailerLite API key

        $subscriberData = wp_json_encode([
            'email' => $email,
            'name' => $name
        ]);

        // cURL
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
            // Successfully added subscriber
            wp_redirect(add_query_arg('success', '1', wp_get_referer()));
        } else {
            wp_die('An error occurred: ' . esc_html($response));
        }

        exit;
    }

    public function WIRNQDSQ_quantity_discounts_product_data_panels__premium_only()
    {
        ?>
        <div id="quantity_breaks" class="panel woocommerce_options_panel hidden"><?php

        ?></div><?php
    }

    public function WIRNQDSQ_quantity_discounts_product_data_tabs__premium_only($tabs)
    {
        $tabs['quantity_discounts'] = [
            'label' => __('Quantity Discounts', 'quantity-discounts-wpiron'),
            'target' => 'quantity_discounts',
            'class' => ['hide_if_variable', 'hide_if_external', 'hide_if_grouped'],
            'priority' => 70
        ];
        return $tabs;
    }


    public function quantity_breaks_icon_change()
    {
        echo '<style>
                    #woocommerce-product-data ul.wc-tabs li.quantity_discounts_options a::before {
                        content: "\f18e";
                    } 
                </style>';
    }

    public function WIRNQDSQ_quantity_breaks_product_data_panels__premium_only($post_id)
    {
        $this->output_custom_styles();
        ?>
        <div id="quantity_discounts" class="panel woocommerce_options_panel hidden">

            <div id="quantity_discounts_notice" class="inline notice woocommerce-message is-dismissible" style="width:90%; margin:10px auto; position:realtive; display: inline-blockl;">
                <p style="margin:0;">
                    Want to try Premium? Start <strong>Free Trial</strong>!
                    <a href="https://wpiron.com/products/quantity-breaks-and-discounts/" target="_blank">Click here to try it now</a>.
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
                    <!-- Quantity Discounts Setting -->
                    <div>
                        <h4 style="margin:0;">Quantity Discounts Blocks:</h4>
                        <input type="radio" name="_wpiron_qd_quantity_enabled" value="enable"> Enable
                        <input type="radio" name="_wpiron_qd_quantity_enabled" value="disable"> Disable
                    </div>
                    <hr>
                    <!-- Min-Max Quantity Selection -->
                    <div>
                        <h4 style="margin:0;">Min-Max Quantity selection:</h4>
                        <input type="radio" name="_wpiron_qd_min_max_enabled" value="enable"> Enable
                        <input type="radio" name="_wpiron_qd_min_max_enabled" value="disable"> Disable
                    </div>
                    <hr>
                    <!-- Min Max Value Fields -->
                    <div id="min_max_values" style="display:none;">
                        <h4 style="margin:0;">Min-Max Quantity values:</h4>
                        <input type="number" name="_wpiron_qd_min_value" placeholder="Minimum Value" value="">
                        <input type="number" name="_wpiron_qd_max_value" placeholder="Maximum Value" value="">
                    </div>
                    <br>
                    <!-- Display Method -->
                    <div id="display_method" style="display:none">
                        <br>
                        <input type="radio" style="display: none;" name="_wpiron_qd_display_method" disabled
                               value="dropdown">
                        <input type="radio" style="display: none;" name="_wpiron_qd_display_method" value="buttons">
                    </div>
                </div>
            </div>
            <div id="quantity_pricing" class="panel">
                <div id="quantity_discounts_container"></div>
                <div style="padding:20px">
                    <button type="button" id="add_quantity_discount" class="button">Add Quantity Discount</button>
                </div>
            </div>
            <div id="preview" class="panel hidden">
                <div style="padding:0 10px 0 10px; margin-top:0;">
                    <div id="quantity_discounts_notice" class="inline notice woocommerce-message is-dismissible">
                        <p style="margin:0;">This is how it will look like on your product page!</p>
                    </div>
                    <div id="quantity_discounts_notice_customise"
                         class="inline notice woocommerce-message is-dismissible">
                        <p style="margin:0;">You can edit the design of this view! <a
                                    href="admin.php?page=wpiron-quantity-design">Click here to customise it!</a>.
                        </p>
                    </div>
                    <div id="minmax_notice_customise" class="inline notice woocommerce-message is-dismissible">
                        <p style="margin:0;">You can edit the design of this view! <a
                                    href="admin.php?page=wpiron-quantity-min-max">Click here to customise it!</a>.
                        </p>
                    </div>
                </div>
                <div class="preview-block">
                    <div id="quantity_discounts_preview"></div>
                    <div id="min_max_preview">
                        <div id="minmax_preview"></div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }

    function WIRNQDSQ_save_quantity_discounts($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $fields = [
            '_wpiron_qd_quantity',
            '_wpiron_qd_price',
            '_wpiron_qd_label',
            '_wpiron_qd_description',
            '_wpiron_qd_quantity_enabled',
            '_wpiron_qd_min_max_enabled',
            '_wpiron_qd_min_value',
            '_wpiron_qd_max_value',
            '_wpiron_qd_badge_text'
        ];
        $error = false;

        if(!$_POST['_wpiron_qd_quantity']) {
            $error = true;
        }

        if ($error) {
		    set_transient('quantity_discounts_error', 'Quantity and Price fields cannot be empty.', 45);
		    return;
	    }

        foreach ($_POST['_wpiron_qd_quantity'] as $index => $quantity) {
            $price = $_POST['_wpiron_qd_price'][$index];
            if (empty($quantity) || empty($price)) {
                $error = true;
                break;
            }
        }

	    if ($error) {
		    set_transient('quantity_discounts_error', 'Quantity and Price fields cannot be empty.', 45);
		    return;
	    }

        // If no error, save the fields
        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $value = $_POST[$field];

                // Sanitize and validate specific fields if needed
                if (in_array($field, ['_wpiron_qd_min_value', '_wpiron_qd_max_value'])) {
                    $value = (int)$value;
                }

                update_post_meta($post_id, $field, $value);
            }
        }
    }


    function WIRNQDSQ_quantity_discounts_admin_notices()
    {
        if ($message = get_transient('quantity_discounts_error')) {
            echo '<div class="notice notice-error is-dismissible"><h3>Quantity Discounts</h3><p>' . esc_html(
                    $message
                ) . '</p></div>';
            delete_transient('quantity_discounts_error');
        }
    }

    public function links_to_menu($links)
    {
        $url = "https://wpiron.com/products/quantity-breaks-and-discounts/#pricing";
        $url2 = "admin.php?page=wpiron-quantity-discounts";
        $url3 = "https://wordpress.org/support/plugin/quantity-discounts/reviews/#new-post";

        $settings_link = "<a href='$url2' ><b>" . __('Settings 🚀') . '</b></a>';
        $settings_link .= "| <a href='$url3' target='_blank'><strong style='display:inline;'>" . __('Review us') . '</strong></a>';
        $settings_link .= " | <a href='$url' style='font-weight: bold; color: green;' target='_blank'>" . __(
                'Get Premium'
            ) . '</a>';

        $links[] = $settings_link;
        return $links;
    }

    function WIRNQDSQ_enqueue_quantity_breaks_scripts($hook)
    {
        if ('post.php' !== $hook && 'post-new.php' !== $hook) {
            return;
        }
        global $post;

        $quantity_discounts_extra = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('my-nonce')
        );


        if ($post) {
            $post_id = $post->ID;
            $quantity_discounts = array(
                'quantities' => get_post_meta($post_id, '_wpiron_qd_quantity', true),
                'prices' => get_post_meta($post_id, '_wpiron_qd_price', true),
                'labels' => get_post_meta($post_id, '_wpiron_qd_label', true),
                'descriptions' => get_post_meta($post_id, '_wpiron_qd_description', true),
                'quantity_enabled' => get_post_meta($post_id, '_wpiron_qd_quantity_enabled', true),
                'display_method' => get_post_meta($post_id, '_wpiron_qd_display_method', true),
                'min_max_enabled' => get_post_meta($post_id, '_wpiron_qd_min_max_enabled', true),
                'min_value' => get_post_meta($post_id, '_wpiron_qd_min_value', true),
                'max_value' => get_post_meta($post_id, '_wpiron_qd_max_value', true),
            );

            wp_localize_script($this->plugin_name, 'quantity_discounts_data', $quantity_discounts);
        }

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/quantity-discounts-admin.js',
            array('jquery'),
            $this->version,
            false
        );
        wp_localize_script($this->plugin_name, 'quantity_discounts_data_extra', $quantity_discounts_extra);
    }

    function WIRNQDSQ_quantity_discounts_register_settings__premium_only()
    {
        register_setting(
            'quantity_discounts_settings', // Option group
            'quantity_discounts_settings', // Option name
            'quantity_discounts_settings_validate',
        );

        register_setting(
            'min_max_quantity_discounts_settings', // Option group
            'min_max_quantity_discounts_settings', // Option name
            'min_max_quantity_discounts_settings_validate',
        );
    }

    function quantity_discounts_settings_validate($input)
    {
        // Sanitize and validate the input here
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

    function min_max_quantity_discounts_settings_validate($input)
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
        $quantity_discounts_settings = get_option('quantity_discounts_settings');
        $minMaxQuantitySettings = get_option('min_max_quantity_discounts_settings');

        $border_style = esc_html($quantity_discounts_settings['border_style']);
        $box_corner_radius = esc_html($quantity_discounts_settings['box_corner_radius']);
        $border_color_inactive = esc_html($quantity_discounts_settings['border_color_inactive']);
        $background_color_inactive = esc_html($quantity_discounts_settings['background_color_inactive']);
        $text_color_inactive = esc_html($quantity_discounts_settings['text_color_inactive']);
        $border_color_active = esc_html($quantity_discounts_settings['border_color_active']);
        $background_color_active = esc_html($quantity_discounts_settings['background_color_active']);
        $text_color_active = esc_html($quantity_discounts_settings['text_color_active']);
        $border_color_hover = esc_html($quantity_discounts_settings['border_color_hover']);
        $background_color_hover = esc_html($quantity_discounts_settings['background_color_hover']);
        $text_color_hover = esc_html($quantity_discounts_settings['text_color_hover']);

        $radio_bg_color_active = esc_html($quantity_discounts_settings['radio_bg_color_active']);
        $radio_bg_color_inactive = esc_html($quantity_discounts_settings['radio_bg_color_inactive'] ?? '');
        $radio_bg_color_hover = esc_html($quantity_discounts_settings['radio_bg_color_hover'] ?? '');
        $radio_border_color_active = esc_html($quantity_discounts_settings['radio_border_color_active'] ?? '');
        $radio_border_color_inactive = esc_html($quantity_discounts_settings['radio_border_color_inactive']);
        $radio_border_color_hover = esc_html($quantity_discounts_settings['radio_border_color_hover'] ?? '');
        $radio_button_size = esc_html($quantity_discounts_settings['radio_button_size']);

        $labelFontWeight = esc_html($quantity_discounts_settings['label_font_weight']);
        $labelFontSize = esc_html($quantity_discounts_settings['label_font_size']);
        $descriptionFontWeight = esc_html($quantity_discounts_settings['description_font_weight']);
        $descriptionFontSize = esc_html($quantity_discounts_settings['description_font_size']);
        $priceFontWeight = esc_html($quantity_discounts_settings['price_font_weight']);
        $priceFontSize = esc_html($quantity_discounts_settings['price_font_size']);
        $oldPriceFontWeight = esc_html($quantity_discounts_settings['old_price_font_weight']);
        $oldPriceFontSize = esc_html($quantity_discounts_settings['old_price_font_size']);
        $showOldPrice = esc_html($quantity_discounts_settings['show_old_price']);

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
        .wpiqd-swatch {
            border-style: " . esc_attr($border_style) . ";
            border-radius: " . esc_attr($box_corner_radius) . "px;
            border-color: " . esc_attr($border_color_inactive) . ";
            background-color: " . esc_attr($background_color_inactive) . ";
            color: " . esc_attr($text_color_inactive) . ";
            transition: all 0.3s ease;
        }
        .wpiqd-radio span {
            display: inline-block;
            height: 20px;
            width: 20px;
            border: 2px solid " . esc_attr($radio_border_color_inactive) . "; /* Inactive border color */
            border-radius: 50%; /* Circular border */
            position: relative;
            cursor: pointer;
            vertical-align: middle; /* Align with the text */
        }
        
        /* Style for the custom radio button when it's active/checked */
        .wpiqd-radio input[type='radio']:checked + span::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            height: 12px;
            width: 12px;
            border-radius: 50%;
            background: " . esc_attr($radio_bg_color_active) . "; /* Active inner circle color */
        }
        
        /* Change border color when radio is checked */
        .wpiqd-radio input[type='radio']:checked + span {
            border-color: " . esc_attr($radio_bg_color_active) . "; /* Active border color */
        }
        
        /* Hover styles for the custom radio */
        .wpiqd-radio span:hover {
            border-color: " . esc_attr($radio_border_color_hover) . "; /* Hover border color */
        }
        
        /* Active class styles */
        .wpiqd-swatch.active .wpiqd-radio span {
            border-color: green; /* Active state border color */
        }
        .wpiqd-heading{
            font-size: " . esc_attr($labelFontSize) . "px;
            font-weight: " . esc_attr($labelFontWeight) . ";
        }
        .wpiqd-subheading{
            font-size: " . esc_attr($descriptionFontSize) . "px;
            font-weight: " . esc_attr($descriptionFontWeight) . ";
        }
        .wpiqd-price{
            font-size: " . esc_attr($priceFontSize) . "px;
            font-weight: " . esc_attr($priceFontWeight) . ";
        }
        .wpiqd-right .old-price{
            font-size: " . esc_attr($oldPriceFontSize) . "px;
            font-weight: $oldPriceFontWeight;
            " . ($showOldPrice === 'no' ? 'display: none;' : '') . "
        }
        .wpiqd-swatch.active {
            border-color: " . esc_attr($border_color_active) . ";
            background-color: " . esc_attr($background_color_active) . ";
            color: " . esc_attr($text_color_active) . ";
        }
        .wpiqd-swatch:hover {
            border-color: " . esc_attr($border_color_hover) . ";
            background-color: " . esc_attr($background_color_hover) . ";
            color: " . esc_attr($text_color_hover) . ";
        }
        .wpiqd-radio span {
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

        .wpiqd-radio input[type='radio']:checked + span::before {
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

    function WIRNQDSQ_update_notice()
    {
        global $current_user;

        $siteUrl = site_url();
        $uniqueUserId = md5($siteUrl);

        $api_url = 'https://uwozfs6rgi.execute-api.us-east-1.amazonaws.com/prod/notifications';
        $body = wp_json_encode([
            'pluginName' => 'wpiron-quantity-breaks-free',
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
                $message_id = $dataEncoded['message_id']; // Get the message ID

                ?>
                <div class="notice notice-success is-dismissible">
                    <?php
                    echo $content; ?>
                    <hr>
                    <a style="margin-bottom: 10px; position: relative; display: block;" href="?wpiron-quantity-breaks_-notice&message_id=<?php echo urlencode($message_id); ?>"><b>Dismiss this notice</b></a>
                </div>
                <?php
            }
        }
    }

    public function WIRNQDSQ_ignore_notice_wpiron()
    {
        global $current_user;

        $siteUrl = site_url();
        $uniqueUserId = md5($siteUrl);

        if (isset($_GET['wpiron-quantity-breaks_-notice'])) {
            $message_id = $_GET['message_id'];
            $apiRequestBody = wp_json_encode(array(
                'user_id' => $uniqueUserId,
                'plugin_name' => 'wpiron-quantity-breaks-free',
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
