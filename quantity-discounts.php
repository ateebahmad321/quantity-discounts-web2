<?php

/**
 * @link              https://tallpro.com
 * @since             1.0.0
 * @package           Quantity_Discounts
 *
 * @wordpress-plugin
 * Plugin Name:       Quantity Discounts For WooCommerce
 * Plugin URI:        https://wpiron.com
 * Description:       The "Quantity Discounts" plugin integrates with WooCommerce to offer bulk purchase discounts, encouraging customers to buy more.
 * Version:           2.6.10
 * Author:            WP Iron
 * Author URI:        https://wpiron.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quantity-discounts
 * Domain Path:       /languages
 * Requires Plugins: woocommerce
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('WIRNQDSQ_QUANTITY_DISCOUNTS_VERSION', '2.6.10');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-quantity-discounts-activator.php
 */
function WIRNQDSQ_activate_quantity_discounts()
{
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
        require_once plugin_dir_path(__FILE__) . 'includes/class-quantity-discounts-activator.php';
        Quantity_Discounts_Activator::activate();
        $defaults = [
            'border_style' => 'solid',
            'box_corner_radius' => '0',
            'border_color_inactive' => '#0a0a0a',
            'background_color_inactive' => '#ffffff',
            'text_color_inactive' => '#0a0101',
            'border_color_active' => '#0a0a0a',
            'background_color_active' => '#0a0707',
            'text_color_active' => '#ffffff',
            'border_color_hover' => '',
            'background_color_hover' => '',
            'text_color_hover' => '#0a0505',
            'radio_bg_color_active' => '#0a0505',
            'radio_bg_color_inactive' => '',
            'radio_bg_color_hover' => '',
            'radio_border_color_active' => '#0a0505',
            'radio_border_color_inactive' => '',
            'radio_border_color_hover' => '',
            'radio_button_size' => '15',
            'label_font_weight' => '400',
            'label_font_size' => '17',
            'description_font_weight' => '300',
            'description_font_size' => '12',
            'price_font_weight' => '400',
            'price_font_size' => '17',
            'old_price_font_weight' => '300',
            'old_price_font_size' => '13',
            'show_old_price' => 'yes',
        ];

        $defaultsMinMax = [
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

        $options = get_option('quantity_discounts_settings');
        $optionsMinMax = get_option('min_max_quantity_discounts_settings');

        if (false === $options) {
            update_option('quantity_discounts_settings', $defaults);
        } else {
            $updated_options = wp_parse_args($options, $defaults);
            update_option('quantity_discounts_settings', $updated_options);
        }

        if (false === $optionsMinMax) {
            update_option('min_max_quantity_discounts_settings', $defaultsMinMax);
        } else {
            $updated_options = wp_parse_args($options, $defaultsMinMax);
            update_option('min_max_quantity_discounts_settings', $updated_options);
        }
    } else {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('Quantity Discounts for WooCommerce requires WooCommerce to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
    }

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-quantity-discounts-deactivator.php
 */
function WIRNQDSQ_deactivate_quantity_discounts()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-quantity-discounts-deactivator.php';
    Quantity_Discounts_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'WIRNQDSQ_activate_quantity_discounts');
register_deactivation_hook(__FILE__, 'WIRNQDSQ_deactivate_quantity_discounts');

function uninstall_quantity_discounts()
{
    // Delete the plugin settings from the database
    delete_option('quantity_discounts_settings');
    delete_option('min_max_quantity_discounts_settings');
}

register_uninstall_hook(__FILE__, 'uninstall_quantity_discounts');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-quantity-discounts.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_quantity_discounts()
{
    $plugin = new WIRNQDSQ_Quantity_Discounts();
    $plugin->run();
}

run_quantity_discounts();