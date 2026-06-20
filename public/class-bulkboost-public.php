<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://bulkboost.com
 * @since      1.0.0
 *
 * @package    BulkBoost
 * @subpackage BulkBoost/public
 */
class BLKBST_BulkBoost_Public
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
            plugin_dir_url(__FILE__) . 'css/bulkboost-public.min.css',
            array(),
            $this->version,
            'all'
        );
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/bulkboost-public.min.js',
            array('jquery'),
            time(),
            false
        );
    }

    function BLKBST_add_custom_quantity_block()
    {
        $this->display_bulkboost();
    }

    function output_custom_styles()
    {
        $bulkboost_settings = get_option('bulkboost_settings');
        $min_max_settings = get_option('min_max_bulkboost_settings');

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
    
    .bulkboost-swatch.active {
        border-color: " . esc_attr($border_color_active) . ";
        background-color: " . esc_attr($background_color_active) . ";
        color: " . esc_attr($text_color_active) . ";
        border-style: " . esc_attr($border_style) . ";
        border-radius: " . esc_attr($box_corner_radius) . "px;
    }

    .bulkboost-radio span {
        border-color: " . esc_attr($radio_border_color_inactive) . ";
    }
    .bulkboost-radio input[type='radio']:checked + span {
        border-color: " . esc_attr($radio_border_color_active) . ";
    }
    .bulkboost-swatch.active .bulkboost-radio span {
        border-color: " . esc_attr($radio_border_color_active) . ";
    }
    .bulkboost-swatch:not(.active) {
        border-color: " . esc_attr($border_color_inactive) . ";
        background-color: " . esc_attr($background_color_inactive) . " !important;
        color: " . esc_attr($text_color_inactive) . ";
        border-style: " . esc_attr($border_style) . ";
        border-radius: " . esc_attr($box_corner_radius) . "px;
    }
    .bulkboost-swatch:not(.active):hover {
        border-color: " . esc_attr($border_color_hover) . ";
        background-color: " . esc_attr($background_color_hover) . " !important;
        color: " . esc_attr($text_color_hover) . ";
        border-style: " . esc_attr($border_style) . ";
        border-radius: " . esc_attr($box_corner_radius) . "px;
    }
    .bulkboost-heading {
        font-size: " . esc_attr($labelFontSize) . "px;
        font-weight: " . esc_attr($labelFontWeight) . ";
    }
    .bulkboost-subheading {
        font-size: " . esc_attr($descriptionFontSize) . "px;
        font-weight: " . esc_attr($descriptionFontWeight) . ";
    }
    .bulkboost-right span {
        font-size: " . esc_attr($priceFontSize) . "px;
        font-weight: " . esc_attr($priceFontWeight) . ";
    }
    .bulkboost-right .old-price span {
        font-size: " . esc_attr($oldPriceFontSize) . "px;
        font-weight: " . esc_attr($oldPriceFontWeight) . ";
    }
    .bulkboost-radio input[type='radio']:checked + span::before{
        background-color: " . esc_attr($radio_bg_color_active) . "
    }
    .bulkboost-radio input[type='radio'] + span::before{
        background-color: " . esc_attr($radio_bg_color_inactive) . "
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
    </style>
    ";
    }

    /**
     * Calculates the savings percentage for a quantity-discount block compared
     * to buying that same quantity at the base (qty = 1) unit price.
     *
     * @param float $quantity_one_price Price for buying a single unit.
     * @param int   $quantity           Quantity in this block.
     * @param float $price              Total price for this block's quantity.
     * @return int|null Rounded percentage saved, or null if it can't be calculated.
     */
    private function calculate_save_percent($quantity_one_price, $quantity, $price)
    {
        if (!$quantity_one_price || $quantity <= 1) {
            return null;
        }

        $old_total_price = $quantity_one_price * $quantity;

        if ($old_total_price <= $price) {
            return null;
        }

        return (int)round((($old_total_price - $price) / $old_total_price) * 100);
    }

    /**
     * Builds the label-tab badge (HOT / MOST POPULAR / BEST DEAL \ud83d\udd25) that sits
     * overlapping the top-left corner of the card.
     *
     * @param string $badge_label none|hot|popular|bestdeal
     * @return string HTML for the label tab, or empty string if none selected.
     */
    private function render_label_tab($badge_label)
    {
        $label_map = [
            'hot' => ['text' => 'HOT', 'class' => 'bulkboost-tab-hot'],
            'popular' => ['text' => 'MOST POPULAR', 'class' => 'bulkboost-tab-popular'],
            'bestdeal' => ['text' => 'BEST DEAL 🔥', 'class' => 'bulkboost-tab-bestdeal'],
        ];

        if (empty($badge_label) || $badge_label === 'none' || !isset($label_map[$badge_label])) {
            return '';
        }

        $info = $label_map[$badge_label];

        return '<div class="bulkboost-label-tab ' . esc_attr($info['class']) . '">' . esc_html($info['text']) . '</div>';
    }

    /**
     * Builds the "Save X%" pill shown inside the card, under the price.
     *
     * @param string   $save_enabled  yes|no
     * @param string   $save_override Manual override text, blank = auto-calc
     * @param int|null $auto_percent  Auto-calculated savings percent
     * @return string HTML for the save badge, or empty string if not applicable.
     */
    private function render_save_badge($save_enabled, $save_override, $auto_percent)
    {
        if ($save_enabled !== 'yes') {
            return '';
        }

        $save_text = '';
        if (!empty($save_override)) {
            $save_text = $save_override;
            if (strpos($save_text, '%') === false && is_numeric(trim($save_text))) {
                $save_text = 'Save ' . trim($save_text) . '%';
            }
        } elseif ($auto_percent !== null && $auto_percent > 0) {
            $save_text = sprintf(__('Save %d%%', 'bulkboost'), $auto_percent);
        }

        if (empty($save_text)) {
            return '';
        }

        return '<span class="bulkboost-badge bulkboost-badge-save">' . esc_html($save_text) . '</span>';
    }

    /**
     * Builds the full-width "+ FREE Shipping" banner shown directly below the
     * card. Only rendered when free shipping is enabled for this tier.
     *
     * @param string $free_shipping yes|no
     * @return string HTML for the banner, or empty string if disabled.
     */
    private function render_free_shipping_banner($free_shipping)
    {
        if ($free_shipping !== 'yes') {
            return '';
        }

        return '<div class="bulkboost-shipping-banner"><span class="bulkboost-shipping-icon" aria-hidden="true">🚚</span> + ' . esc_html__('FREE Shipping', 'bulkboost') . '</div>';
    }

    function display_bulkboost($post_id = null)
    {
        if (!$post_id) {
            $post_id = get_the_ID(); // Get current post ID if not provided
        }

        $quantityDicsountsEnabled = get_post_meta($post_id, '_bulkboost_qd_quantity_enabled', true);
        $minMaxEnabled = get_post_meta($post_id, '_bulkboost_qd_min_max_enabled', true);

        $fields = [
            '_bulkboost_qd_quantity',
            '_bulkboost_qd_price',
            '_bulkboost_qd_label',
            '_bulkboost_qd_description',
            '_bulkboost_qd_badge_text',
            // --- Badge fields ---
            '_bulkboost_qd_badge_label',
            '_bulkboost_qd_badge_free_shipping',
            '_bulkboost_qd_badge_save_enabled',
            '_bulkboost_qd_badge_save_override',
        ];

        $data = [];
        foreach ($fields as $field) {
            $value = get_post_meta($post_id, $field, true);
            if (!empty($value) && is_array($value)) {
                $data[$field] = $value;
            }
        }

        if (isset($data['_bulkboost_qd_quantity']) && $data['_bulkboost_qd_quantity'][0] && $quantityDicsountsEnabled === 'enable' && $minMaxEnabled === 'disable') {
            if (empty($data)) {
                return;
            }

            echo '<div class="custom-quantity-block">';
            $count = count($data['_bulkboost_qd_quantity']); // Get the number of sets
            $quantity_one_price = isset($data['_bulkboost_qd_price'][0]) ? $data['_bulkboost_qd_price'][0] : 0;
            for ($i = 0; $i < $count; $i++) {
                $active_class = $i === 0 ? 'active' : '';
                $quantity = esc_attr($data['_bulkboost_qd_quantity'][$i]);
                $price = esc_attr($data['_bulkboost_qd_price'][$i]);

                $old_price = '';
                if ($quantity > 1) {
                    $old_price = wc_price($quantity_one_price * $quantity);
                }

                // --- Build badges for this tier ---
                $badge_label = $data['_bulkboost_qd_badge_label'][$i] ?? 'none';
                $free_shipping = $data['_bulkboost_qd_badge_free_shipping'][$i] ?? 'no';
                $save_enabled = $data['_bulkboost_qd_badge_save_enabled'][$i] ?? 'no';
                $save_override = $data['_bulkboost_qd_badge_save_override'][$i] ?? '';
                $auto_percent = $this->calculate_save_percent($quantity_one_price, $quantity, $price);

                $label_tab_html = $this->render_label_tab($badge_label);
                $save_badge_html = $this->render_save_badge($save_enabled, $save_override, $auto_percent);
                $shipping_banner_html = $this->render_free_shipping_banner($free_shipping);

                $has_label_tab = !empty($label_tab_html);

                // Wrapper holds the label tab (overlapping top-left), the card itself,
                // and the free-shipping banner (full-width strip below the card).
                echo '<div class="bulkboost-tier-wrap' . ($has_label_tab ? ' has-label-tab' : '') . '">';
                echo $label_tab_html;

                echo '<span class="bulkboost-swatch ' . $active_class . '" data-value="' . esc_attr(
                        $data['_bulkboost_qd_quantity'][$i]
                    ) . '" data-price="' . esc_attr($data['_bulkboost_qd_price'][$i]) . '">';
                echo '<div class="bulkboost-inner">';
                echo '<div class="one-block">';
                echo '<div class="bulkboost-radio">';
                echo '<label class="bulkboost-radio">';
                echo '<input value="' . esc_attr(
                        $data['_bulkboost_qd_quantity'][$i]
                    ) . '" type="radio" name="custom-quantity" ' . ($i === 0 ? 'checked' : '') . '>';
                echo '<span></span>';
                echo '</label>';
                echo '</div>';
                echo '</div>';
                echo '<div class="second-block ">';
                echo '<div class="bulkboost-middle">';
                echo '<div class="bulkboost-heading">' . esc_html($data['_bulkboost_qd_label'][$i]) . '</div>';
                echo '<div class="bulkboost-subheading">' . esc_html($data['_bulkboost_qd_description'][$i]) . '</div>';
                echo '</div>';
                echo '</div>';
                echo '<div class="third-block">';
                echo '<div class="bulkboost-right">';
                echo '<div class="bulkboost-price-row">';
                echo '<div class="old-price"><s>' . $old_price . '</s></div>';
                echo '<span class="bulkboost-price">' . wc_price(esc_html($data['_bulkboost_qd_price'][$i])) . '</span>';
                echo '</div>';
                echo $save_badge_html;
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</span>';

                echo $shipping_banner_html;
                echo '</div>'; // .bulkboost-tier-wrap
            }
            echo '</div>';
        } elseif ($minMaxEnabled === 'enable') {
            $minValue = get_post_meta($post_id, '_bulkboost_qd_min_value')[0];
            $maxValue = get_post_meta($post_id, '_bulkboost_qd_max_value')[0];

            echo '<div id="quantity-buttons">';
            for ($i = $minValue; $i <= $maxValue; $i++) {
                $activeClass = ($i == $minValue) ? 'active' : ''; // Add 'active' class to the first button
                echo '<div class="quantity-button ' . $activeClass . '" data-quantity="' . $i . '">' . $i . '</div>';
            }
            echo '</div>';
        }
    }

    function BLKBST_add_custom_product_data_to_cart($cart_item_data, $product_id, $variation_id)
    {
        if (isset($_POST['wpi_custom_quantity']) && isset($_POST['wpi_custom_price'])) {
            $cart_item_data['wpi_custom_quantity'] = sanitize_text_field($_POST['wpi_custom_quantity']);
            $cart_item_data['wpi_custom_price'] = sanitize_text_field($_POST['wpi_custom_price']);
        }
        return $cart_item_data;
    }

    function BLKBST_update_cart_item_price($cart)
    {
        if (is_admin() && !defined('DOING_AJAX')) {
            return;
        }

        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            $product_id = $cart_item['product_id'];
            $quantity_in_cart = $cart_item['quantity'];

            // Get custom pricing rules from product metadata
            $custom_quantities = get_post_meta($product_id, '_bulkboost_qd_quantity', true);
            $custom_prices = get_post_meta($product_id, '_bulkboost_qd_price', true);

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

    function BLKBST_add_custom_price_field_to_product_form()
    {
        echo '<input type="hidden" name="wpi_custom_price" id="wpi_custom_price" value="">';
        echo '<input type="hidden" name="wpi_custom_quantity" id="wpi_custom_quantity" value="">';
    }

    function BLKBST_remove_quantity_field_on_product_pages()
    {
        if (is_product()) {
            global $post;
            $post_id = $post->ID;

            $fields = [
                '_bulkboost_qd_quantity',
                '_bulkboost_qd_price',
                '_bulkboost_qd_label',
                '_bulkboost_qd_description',
                '_bulkboost_qd_badge_text'
            ];

            $minMaxEnabled = get_post_meta($post_id, '_bulkboost_qd_min_max_enabled', true);
            $quantityDicsountsEnabled = get_post_meta($post_id, '_bulkboost_qd_quantity_enabled', true);

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
