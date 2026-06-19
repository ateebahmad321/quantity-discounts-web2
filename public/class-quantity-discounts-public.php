<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://tallpro.com
 * @since      1.0.0
 *
 * @package    Quantity_Discounts
 * @subpackage Quantity_Discounts/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quantity_Discounts
 * @subpackage Quantity_Discounts/public
 * @author     WPIron <info@tallpro.com>
 */
class WIRNQDSQ_Quantity_Discounts_Public
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
     * @param string $plugin_name The name of the plugin.
     * @param string $version The version of this plugin.
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
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
            plugin_dir_url(__FILE__) . 'css/quantity-discounts-public.min.css',
            array(),
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
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

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/quantity-discounts-public.min.js',
            array('jquery'),
            time(),
            false
        );
    }

    function WIRNQDSQ_add_custom_quantity_block()
    {
        $this->display_quantity_discounts();
    }

    function output_custom_styles()
    {
        $quantity_discounts_settings = get_option('quantity_discounts_settings');
        $min_max_settings = get_option('min_max_quantity_discounts_settings');

        // min max
        $min_max_background_color_active = esc_html($min_max_settings['min_max_background_color_active']);
        $min_max_background_color_inactive = esc_html($min_max_settings['min_max_background_color_inactive']);
        $min_max_background_color_hover = esc_html($min_max_settings['min_max_background_color_hover']);
        $min_max_text_color_active = esc_html($min_max_settings['min_max_text_color_active']);
        $min_max_text_color_inactive = esc_html($min_max_settings['min_max_text_color_inactive']);
        $min_max_text_color_hover = esc_html($min_max_settings['min_max_text_color_hover']);
        $min_max_border_color_active = esc_html($min_max_settings['min_max_border_color_active']);
        $min_max_border_color_inactive = esc_html($min_max_settings['min_max_border_color_inactive']);
        $min_max_border_color_hover = esc_html($min_max_settings['min_max_border_color_hover']);
        $min_max_size = esc_html($min_max_settings['min_max_size']);

        // quantity design
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

        $halfPadding = $min_max_size / 2;
        $button_size = $radio_button_size - 5;

        echo "
    <style>
    #quantity-buttons{
        margin-bottom: 20px;
    }
    #quantity-buttons .quantity-button{
        padding: " . esc_attr($halfPadding) . "px " . esc_attr($min_max_size) . "px;
        margin: 2px;
        line-height:1.3em;
        display: inline-block;
        background-color: " . esc_attr($min_max_background_color_inactive) . ";
        color: " . esc_attr($min_max_text_color_inactive) . ";
        border: 1px solid " . esc_attr($min_max_border_color_inactive) . ";
        cursor: pointer;
    }
    #quantity-buttons .quantity-button.active{
        padding: " . esc_attr($halfPadding) . "px " . esc_attr($min_max_size) . "px;
        margin: 2px;
        display: inline-block;
        background-color: " . esc_attr($min_max_background_color_active) . ";
        color: " . esc_attr($min_max_text_color_active) . ";
        border: 1px solid " . esc_attr($min_max_border_color_active) . ";
        cursor: pointer;
    }
    
    #quantity-buttons .quantity-button:hover{
        padding: " . esc_attr($halfPadding) . "px " . esc_attr($min_max_size) . "px;
        margin: 2px;
        display: inline-block;
        background-color: " . esc_attr($min_max_background_color_hover) . ";
        color: " . esc_attr($min_max_text_color_hover) . ";
        border: 1px solid " . esc_attr($min_max_border_color_hover) . ";
        cursor: pointer;
    }
    
    #quantity-buttons{
    margin-bottom: 20px;
    }
    
    .wpiqd-swatch.active {
        border-color: " . esc_attr($border_color_active) . ";
        background-color: " . esc_attr($background_color_active) . ";
        color: " . esc_attr($text_color_active) . ";
        border-style: " . esc_attr($border_style) . ";
        border-radius: " . esc_attr($box_corner_radius) . "px;
    }

    .wpiqd-radio span {
        border-color: " . esc_attr($radio_border_color_inactive) . ";
    }
    .wpiqd-radio input[type='radio']:checked + span {
        border-color: " . esc_attr($radio_border_color_active) . ";
    }
    .wpiqd-swatch.active .wpiqd-radio span {
        border-color: " . esc_attr($radio_border_color_active) . ";
    }
    .wpiqd-swatch:not(.active) {
        border-color: " . esc_attr($border_color_inactive) . ";
        background-color: " . esc_attr($background_color_inactive) . " !important;
        color: " . esc_attr($text_color_inactive) . ";
        border-style: " . esc_attr($border_style) . ";
        border-radius: " . esc_attr($box_corner_radius) . "px;
    }
    .wpiqd-swatch:not(.active):hover {
        border-color: " . esc_attr($border_color_hover) . ";
        background-color: " . esc_attr($background_color_hover) . " !important;
        color: " . esc_attr($text_color_hover) . ";
        border-style: " . esc_attr($border_style) . ";
        border-radius: " . esc_attr($box_corner_radius) . "px;
    }
    .wpiqd-heading {
        font-size: " . esc_attr($labelFontSize) . "px;
        font-weight: " . esc_attr($labelFontWeight) . ";
    }
    .wpiqd-subheading {
        font-size: " . esc_attr($descriptionFontSize) . "px;
        font-weight: " . esc_attr($descriptionFontWeight) . ";
    }
    .wpiqd-right span {
        font-size: " . esc_attr($priceFontSize) . "px;
        font-weight: " . esc_attr($priceFontWeight) . ";
    }
    .wpiqd-right .old-price span {
        font-size: " . esc_attr($oldPriceFontSize) . "px;
        font-weight: " . esc_attr($oldPriceFontWeight) . ";
    }
    .wpiqd-radio input[type='radio']:checked + span::before{
        background-color: " . esc_attr($radio_bg_color_active) . "
    }
    .wpiqd-radio input[type='radio'] + span::before{
        background-color: " . esc_attr($radio_bg_color_inactive) . "
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
    </style>
    ";
    }


    function display_quantity_discounts($post_id = null)
    {
        if (!$post_id) {
            $post_id = get_the_ID(); // Get current post ID if not provided
        }

        $quantityDicsountsEnabled = get_post_meta($post_id, '_wpiron_qd_quantity_enabled', true);
        $minMaxEnabled = get_post_meta($post_id, '_wpiron_qd_min_max_enabled', true);

        $fields = [
            '_wpiron_qd_quantity',
            '_wpiron_qd_price',
            '_wpiron_qd_label',
            '_wpiron_qd_description',
            '_wpiron_qd_badge_text'
        ];

        $data = [];
        foreach ($fields as $field) {
            $value = get_post_meta($post_id, $field, true);
            if (!empty($value) && is_array($value)) {
                $data[$field] = $value;
            }
        }

        if (isset($data['_wpiron_qd_quantity']) && $data['_wpiron_qd_quantity'][0] && $quantityDicsountsEnabled === 'enable' && $minMaxEnabled === 'disable') {
            if (empty($data)) {
                return;
            }

            echo '<div class="custom-quantity-block">';
            $count = count(current($data)); // Get the number of sets
            $quantity_one_price = isset($data['_wpiron_qd_price'][0]) ? $data['_wpiron_qd_price'][0] : 0;
            for ($i = 0; $i < $count; $i++) {
                $active_class = $i === 0 ? 'active' : '';
                $quantity = esc_attr($data['_wpiron_qd_quantity'][$i]);
                $price = esc_attr($data['_wpiron_qd_price'][$i]);

                $old_price = '';
                if ($quantity > 1) {
                    $old_price = wc_price($quantity_one_price * $quantity);
                }
                echo '<span class="wpiqd-swatch ' . $active_class . '" data-value="' . esc_attr(
                        $data['_wpiron_qd_quantity'][$i]
                    ) . '" data-price="' . esc_attr($data['_wpiron_qd_price'][$i]) . '">';
                echo '<div class="wpiqd-inner">';
                echo '<div class="one-block">';
                echo '<div class="wpiqd-radio">';
                echo '<label class="wpiqd-radio">';
                echo '<input value="' . esc_attr(
                        $data['_wpiron_qd_quantity'][$i]
                    ) . '" type="radio" name="custom-quantity" ' . ($i === 0 ? 'checked' : '') . '>';
                echo '<span></span>';
                echo '</label>';
                echo '</div>';
                echo '</div>';
                echo '<div class="second-block ">';
                echo '<div class="wpiqd-middle">';
                echo '<div class="wpiqd-heading">' . esc_html($data['_wpiron_qd_label'][$i]) . '</div>';
                echo '<div class="wpiqd-subheading">' . esc_html($data['_wpiron_qd_description'][$i]) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="third-block">';
                echo '<div class="wpiqd-right">';
                echo '<span class="wpiqd-price">' . wc_price(esc_html($data['_wpiron_qd_price'][$i])) . '</span>';
                echo '<div class="old-price"><s>' . $old_price . '</s></div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</span>';
            }
            echo '</div>';
        } elseif ($minMaxEnabled === 'enable') {
            $minValue = get_post_meta($post_id, '_wpiron_qd_min_value')[0];
            $maxValue = get_post_meta($post_id, '_wpiron_qd_max_value')[0];

            echo '<div id="quantity-buttons">';
            for ($i = $minValue; $i <= $maxValue; $i++) {
                $activeClass = ($i == $minValue) ? 'active' : ''; // Add 'active' class to the first button
                echo '<div class="quantity-button ' . $activeClass . '" data-quantity="' . $i . '">' . $i . '</div>';
            }
            echo '</div>';
        }
    }

    function WIRNQDSQ_add_custom_product_data_to_cart($cart_item_data, $product_id, $variation_id)
    {
        if (isset($_POST['wpi_custom_quantity']) && isset($_POST['wpi_custom_price'])) {
            $cart_item_data['wpi_custom_quantity'] = sanitize_text_field($_POST['wpi_custom_quantity']);
            $cart_item_data['wpi_custom_price'] = sanitize_text_field($_POST['wpi_custom_price']);
        }
        return $cart_item_data;
    }

    function WIRNQDSQ_update_cart_item_price($cart)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity_in_cart = $cart_item['quantity'];

            // Get custom pricing rules from product metadata
            $custom_quantities = get_post_meta($product_id, '_wpiron_qd_quantity', true);
            $custom_prices = get_post_meta($product_id, '_wpiron_qd_price', true);

            $matched_price = null;

            if ($custom_quantities && $custom_prices) {
                $custom_pricing_rules = array_combine($custom_quantities, $custom_prices);
                ksort($custom_pricing_rules); // Sort by quantity in ascending order

                foreach ($custom_pricing_rules as $quantity => $price) {
                    if ($quantity_in_cart == $quantity) {
                        $matched_price = $price / $quantity;
                        break;
                    }
                }
            }

            if ($matched_price !== null) {
                $cart_item['data']->set_price($matched_price);
            } else {
                // If no custom pricing rule matched, fall back to sale price or regular price
                $sale_price = get_post_meta($product_id, '_sale_price', true);
                if ($sale_price !== '' && $sale_price !== false) {
                    $cart_item['data']->set_price($sale_price);
                } else {
                    $regular_price = get_post_meta($product_id, '_regular_price', true);
                    if ($regular_price !== '' && $regular_price !== false) {
                        $cart_item['data']->set_price($regular_price);
                    }
                }
            }
        }
    }

    function WIRNQDSQ_add_custom_price_field_to_product_form()
    {
        echo '<input type="hidden" name="wpi_custom_price" id="wpi_custom_price" value="">';
        echo '<input type="hidden" name="wpi_custom_quantity" id="wpi_custom_quantity" value="">';
    }

    function WIRNQDSQ_remove_quantity_field_on_product_pages()
    {
        if (is_product()) {
            global $post;
            $post_id = $post->ID;

            // Alternatively, you can use: $post_id = get_the_ID();

            $fields = [
                '_wpiron_qd_quantity',
                '_wpiron_qd_price',
                '_wpiron_qd_label',
                '_wpiron_qd_description',
                '_wpiron_qd_badge_text'
            ];

            $minMaxEnabled = get_post_meta($post_id, '_wpiron_qd_min_max_enabled', true);
            $quantityDicsountsEnabled = get_post_meta($post_id, '_wpiron_qd_quantity_enabled', true);

            $data = [];
            foreach ($fields as $field) {
                $value = get_post_meta($post_id, $field, true);
                if (!empty($value) && is_array($value)) {
                    $data[$field] = $value;
                }
            }

            if ($quantityDicsountsEnabled === 'enable' || $minMaxEnabled === 'enable') {
                echo '<style>.single-product .quantity { display: none !important; }</style>';
            }
        }
    }

}
