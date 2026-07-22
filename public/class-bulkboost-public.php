<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://profiles.wordpress.org/ateebahamd
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
            $this->version,
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
        $min_max_settings = get_option('bulkboost_min_max_settings');

        // Guard against a missing or partial option so the storefront never
        // fatals doing math on empty values (e.g. a fresh site, or before the
        // Min/Max settings have been saved).
        if (!is_array($bulkboost_settings)) {
            $bulkboost_settings = array();
        }
        $min_max_settings = wp_parse_args(
            is_array($min_max_settings) ? $min_max_settings : array(),
            array(
                'min_max_background_color_active'   => '#000000',
                'min_max_background_color_inactive' => '#FFFFFF',
                'min_max_background_color_hover'    => '#DDDDDD',
                'min_max_text_color_active'         => '#FFFFFF',
                'min_max_text_color_inactive'       => '#000000',
                'min_max_text_color_hover'          => '#333333',
                'min_max_border_color_active'       => '#000000',
                'min_max_border_color_inactive'     => '#FFFFFF',
                'min_max_border_color_hover'        => '#333333',
                'min_max_size'                      => '16',
            )
        );

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
        $border_width = esc_html($bulkboost_settings['border_width'] ?? '1.5');
        $card_gap = esc_html($bulkboost_settings['card_gap'] ?? '12');

        // This "if" block will be auto removed from the Free version.
        if (blkbst_fs()->can_use_premium_code__premium_only()) {
            $label_hot_bg        = sanitize_hex_color($bulkboost_settings['label_hot_bg'] ?? '') ?: '#e53935';
            $label_hot_text      = sanitize_hex_color($bulkboost_settings['label_hot_text'] ?? '') ?: '#ffffff';
            $label_popular_bg    = sanitize_hex_color($bulkboost_settings['label_popular_bg'] ?? '') ?: '#7b3fd1';
            $label_popular_text  = sanitize_hex_color($bulkboost_settings['label_popular_text'] ?? '') ?: '#ffffff';
            $label_bestdeal_bg   = sanitize_hex_color($bulkboost_settings['label_bestdeal_bg'] ?? '') ?: '#16a34a';
            $label_bestdeal_text = sanitize_hex_color($bulkboost_settings['label_bestdeal_text'] ?? '') ?: '#ffffff';
            $save_badge_bg       = sanitize_hex_color($bulkboost_settings['save_badge_bg'] ?? '') ?: '#10976a';
            $save_badge_text     = sanitize_hex_color($bulkboost_settings['save_badge_text'] ?? '') ?: '#ffffff';
            $shipping_badge_bg   = sanitize_hex_color($bulkboost_settings['shipping_badge_bg'] ?? '') ?: '#1b1c18';
            $shipping_badge_text = sanitize_hex_color($bulkboost_settings['shipping_badge_text'] ?? '') ?: '#ffffff';

            $blkbst_badge_css =
                '.bulkboost-label-tab.bulkboost-tab-hot{background:' . $label_hot_bg . ';color:' . $label_hot_text . ';}'
                . '.bulkboost-label-tab.bulkboost-tab-popular{background:' . $label_popular_bg . ';color:' . $label_popular_text . ';}'
                . '.bulkboost-label-tab.bulkboost-tab-bestdeal{background:' . $label_bestdeal_bg . ';color:' . $label_bestdeal_text . ';}'
                . '.bulkboost-badge-save{background-color:' . $save_badge_bg . ';color:' . $save_badge_text . ';}'
                . '.bulkboost-shipping-banner{background-color:' . $shipping_badge_bg . ';color:' . $shipping_badge_text . ';}'
                . '.bulkboost-shipping-banner .bulkboost-shipping-icon{color:inherit;}';
        }
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

        $css = "
    #quantity-buttons{margin-bottom:20px;}
    #quantity-buttons .quantity-button{padding:{$halfPadding}px {$min_max_size}px;margin:2px;line-height:1.3em;display:inline-block;background-color:{$min_max_background_color_inactive};color:{$min_max_text_color_inactive};border:1px solid {$min_max_border_color_inactive};cursor:pointer;}
    #quantity-buttons .quantity-button.active{background-color:{$min_max_background_color_active};color:{$min_max_text_color_active};border:1px solid {$min_max_border_color_active};}
    #quantity-buttons .quantity-button:hover{background-color:{$min_max_background_color_hover};color:{$min_max_text_color_hover};border:1px solid {$min_max_border_color_hover};}
    .bulkboost-swatch.active{border-color:{$border_color_active};background-color:{$background_color_active};color:{$text_color_active};border-style:{$border_style};border-width:{$border_width}px;border-radius:{$box_corner_radius}px;}
    .bulkboost-radio span{border-color:{$radio_border_color_inactive};}
    .bulkboost-radio input[type='radio']:checked + span{border-color:{$radio_border_color_active};}
    .bulkboost-swatch.active .bulkboost-radio span{border-color:{$radio_border_color_active};}
    .bulkboost-swatch:not(.active){border-color:{$border_color_inactive};background-color:{$background_color_inactive} !important;color:{$text_color_inactive};border-style:{$border_style};border-width:{$border_width}px;border-radius:{$box_corner_radius}px;}
    .bulkboost-swatch:not(.active):hover{border-color:{$border_color_hover};background-color:{$background_color_hover} !important;color:{$text_color_hover};border-style:{$border_style};border-radius:{$box_corner_radius}px;}
    .bulkboost-heading{font-size:{$labelFontSize}px;font-weight:{$labelFontWeight};}
    .bulkboost-subheading{font-size:{$descriptionFontSize}px;font-weight:{$descriptionFontWeight};}
    .bulkboost-right span{font-size:{$priceFontSize}px;font-weight:{$priceFontWeight};}
    .bulkboost-right .old-price span{font-size:{$oldPriceFontSize}px;font-weight:{$oldPriceFontWeight};}
    .bulkboost-radio input[type='radio']:checked + span::before{background-color:{$radio_bg_color_active};}
    .bulkboost-radio input[type='radio'] + span::before{background-color:{$radio_bg_color_inactive};}
    .bulkboost-radio span{display:inline-block;height:{$radio_button_size}px;width:{$radio_button_size}px;border-width:1px;border-style:solid;border-radius:50%;position:relative;cursor:pointer;vertical-align:middle;}
    .bulkboost-radio input[type='radio']:checked + span::before{content:'';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);height:{$button_size}px;width:{$button_size}px;border-radius:50%;}
    .custom-quantity-block .bulkboost-tier-wrap{margin-bottom:{$card_gap}px;}
    .custom-quantity-block.bb-selector-checkbox .bulkboost-radio span{border-radius:4px;}
    .custom-quantity-block.bb-selector-checkbox .bulkboost-radio input[type='radio']:checked + span::before{border-radius:2px;}
    .custom-quantity-block.bb-selector-none .bulkboost-radio{display:none;}
    ";

        // This "if" block will be auto removed from the Free version.
        if (blkbst_fs()->can_use_premium_code__premium_only()) {
            $css .= $blkbst_badge_css;
        }

        wp_add_inline_style($this->plugin_name, $css);
    }

    /**
     * Calculates the savings percentage for a quantity-discount block compared
     * to buying that same quantity at the base (qty = 1) unit price.
     *
     * @param float $quantity_one_price Price for buying a single unit.
     * @param int   $quantity           Quantity in this block.
     * @param float $price              Total price for this block's quantity.
     * @return int|null Rounded percentage saved, or null if it can't be calculated.
     *
     * This whole function will be auto removed from the Free version.
     */
    private function calculate_save_percent__premium_only($quantity_one_price, $quantity, $price)
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
    private function render_label_tab__premium_only($badge_label)
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
    private function render_save_badge__premium_only($save_enabled, $save_override, $auto_percent)
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
            /* translators: %d: discount percentage saved on this tier. */
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
    private function render_free_shipping_banner__premium_only($free_shipping)
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

        // Global design settings (selector style: radio | checkbox | none).
        $design_settings = get_option('bulkboost_settings', array());
        $selector_style = isset($design_settings['selector_style']) ? $design_settings['selector_style'] : 'radio';

        $fields = [
            '_bulkboost_qd_quantity',
            '_bulkboost_qd_price',
            '_bulkboost_qd_label',
            '_bulkboost_qd_description',
        ];

        // This "if" block will be auto removed from the Free version.
        if (blkbst_fs()->can_use_premium_code__premium_only()) {
            $fields = array_merge($fields, BLKBST_BulkBoost_Admin::badge_meta_fields__premium_only());
        }

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

            echo '<div class="custom-quantity-block bb-selector-' . esc_attr($selector_style) . '">';
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

                $wrap_class = 'bulkboost-tier-wrap';

                // This "if" block will be auto removed from the Free version.
                if (blkbst_fs()->can_use_premium_code__premium_only()) {
                    $label_tab_html = $this->render_label_tab__premium_only(
                        $data['_bulkboost_qd_badge_label'][$i] ?? 'none'
                    );
                    if (!empty($label_tab_html)) {
                        $wrap_class .= ' has-label-tab';
                    }
                }

                // Wrapper holds the card itself (plus, in the Pro version, the label
                // tab overlapping top-left and the free-shipping strip below).
                echo '<div class="' . esc_attr($wrap_class) . '">';

                // This "if" block will be auto removed from the Free version.
                if (blkbst_fs()->can_use_premium_code__premium_only()) {
                    echo wp_kses_post($label_tab_html);
                }

                echo '<span class="bulkboost-swatch ' . esc_attr($active_class) . '" data-value="' . esc_attr(
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
                echo '<div class="old-price"><s>' . wp_kses_post($old_price) . '</s></div>';
                echo '<span class="bulkboost-price">' . wp_kses_post(wc_price($data['_bulkboost_qd_price'][$i])) . '</span>';
                echo '</div>';
                // This "if" block will be auto removed from the Free version.
                if (blkbst_fs()->can_use_premium_code__premium_only()) {
                    echo wp_kses_post($this->render_save_badge__premium_only(
                        $data['_bulkboost_qd_badge_save_enabled'][$i] ?? 'no',
                        $data['_bulkboost_qd_badge_save_override'][$i] ?? '',
                        $this->calculate_save_percent__premium_only($quantity_one_price, $quantity, $price)
                    ));
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</span>';

                // This "if" block will be auto removed from the Free version.
                if (blkbst_fs()->can_use_premium_code__premium_only()) {
                    echo wp_kses_post($this->render_free_shipping_banner__premium_only(
                        $data['_bulkboost_qd_badge_free_shipping'][$i] ?? 'no'
                    ));
                }
                echo '</div>'; // .bulkboost-tier-wrap
            }
            echo '</div>';
        } elseif ($minMaxEnabled === 'enable') {
            $minValue = get_post_meta($post_id, '_bulkboost_qd_min_value')[0];
            $maxValue = get_post_meta($post_id, '_bulkboost_qd_max_value')[0];

            echo '<div id="quantity-buttons">';
            for ($i = $minValue; $i <= $maxValue; $i++) {
                $activeClass = ($i == $minValue) ? 'active' : ''; // Add 'active' class to the first button
                echo '<div class="quantity-button ' . esc_attr($activeClass) . '" data-quantity="' . esc_attr($i) . '">' . esc_html($i) . '</div>';
            }
            echo '</div>';
        }
    }

    function BLKBST_add_custom_product_data_to_cart($cart_item_data, $product_id, $variation_id)
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Missing -- Runs on WooCommerce's own add-to-cart request, which has no nonce of its own; values are sanitized and only stored as cart item data.
        if (isset($_POST['wpi_custom_quantity']) && isset($_POST['wpi_custom_price'])) {
            // phpcs:disable WordPress.Security.NonceVerification.Missing
            $cart_item_data['wpi_custom_quantity'] = sanitize_text_field(wp_unslash($_POST['wpi_custom_quantity']));
            $cart_item_data['wpi_custom_price'] = sanitize_text_field(wp_unslash($_POST['wpi_custom_price']));
            // phpcs:enable WordPress.Security.NonceVerification.Missing
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

            $minMaxEnabled = get_post_meta($post_id, '_bulkboost_qd_min_max_enabled', true);
            $quantityDicsountsEnabled = get_post_meta($post_id, '_bulkboost_qd_quantity_enabled', true);

            if ($quantityDicsountsEnabled === 'enable' || $minMaxEnabled === 'enable') {
                wp_add_inline_style($this->plugin_name, '.single-product .quantity { display: none !important; }');
            }
        }
    }

    /**
     * Locks the quantity column in the cart when "Quantity field in Cart" is
     * disabled in General Settings (Pro). Replaces the editable input with a
     * read-only number so customers keep the quantity chosen on the product page.
     *
     * @param string $product_quantity Existing quantity HTML.
     * @param string $cart_item_key    Cart item key.
     * @param array  $cart_item        Cart item data.
     * @return string
     */
    /**
     * Whether a product uses a BulkBoost solution (quantity breaks or min/max).
     * Only these products' quantities are locked in the cart.
     */
    private function is_bulkboost_product($product_id)
    {
        if (!$product_id) {
            return false;
        }
        return get_post_meta($product_id, '_bulkboost_qd_quantity_enabled', true) === 'enable'
            || get_post_meta($product_id, '_bulkboost_qd_min_max_enabled', true) === 'enable';
    }

    public function BLKBST_lock_cart_quantity__premium_only($product_quantity, $cart_item_key, $cart_item)
    {
        $general = get_option('bulkboost_general_settings', array());
        if (($general['disable_quantity_cart'] ?? 'enabled') !== 'disabled') {
            return $product_quantity;
        }
        $product_id = isset($cart_item['product_id']) ? $cart_item['product_id'] : 0;
        if (!$this->is_bulkboost_product($product_id)) {
            return $product_quantity;
        }
        $qty = isset($cart_item['quantity']) ? (int) $cart_item['quantity'] : 1;
        return '<span class="bulkboost-locked-qty">' . esc_html($qty) . '</span>';
    }

    /**
     * Hides quantity controls on the checkout page when "Quantity field in
     * Checkout" is disabled in General Settings (Pro). Classic checkout is
     * already read-only; this also covers block-based checkout steppers.
     */
    public function BLKBST_lock_checkout_quantity__premium_only()
    {
        if (!function_exists('is_checkout') || !is_checkout()) {
            return;
        }
        $general = get_option('bulkboost_general_settings', array());
        if (($general['disable_quantity_checkout'] ?? 'enabled') !== 'disabled') {
            return;
        }
        wp_add_inline_style($this->plugin_name, '.woocommerce-checkout .quantity, .wc-block-components-quantity-selector { pointer-events: none; }');
    }

    /**
     * Locks the quantity in the block-based Cart & Checkout (Store API).
     * The classic `woocommerce_cart_item_quantity` filter doesn't apply to the
     * React Cart/Checkout blocks; returning false here renders the quantity as
     * read-only text instead of an editable stepper.
     *
     * @param bool       $editable   Whether the quantity is editable.
     * @param WC_Product $product    The product (unused).
     * @param array      $cart_item  Cart item (unused).
     * @return bool
     */
    public function BLKBST_lock_block_quantity__premium_only($editable, $product = null, $cart_item = null)
    {
        $general = get_option('bulkboost_general_settings', array());
        $cart_locked     = (($general['disable_quantity_cart'] ?? 'enabled') === 'disabled');
        $checkout_locked = (($general['disable_quantity_checkout'] ?? 'enabled') === 'disabled');
        if (!$cart_locked && !$checkout_locked) {
            return $editable;
        }

        $product_id = $product ? $product->get_id() : (isset($cart_item['product_id']) ? $cart_item['product_id'] : 0);
        if (!$this->is_bulkboost_product($product_id)) {
            return $editable;
        }

        return false;
    }

}
