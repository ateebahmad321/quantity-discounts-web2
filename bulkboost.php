<?php

/**
 * @link              https://bulkboost.com
 * @since             1.0.0
 * @package           BulkBoost
 *
 * @wordpress-plugin
 * Plugin Name:       BulkBoost – Quantity Discounts & Bundles
 * Plugin URI:        https://bulkboost.com
 * Description:       Boost average order value with tiered quantity discounts, breaks and bundle offers for WooCommerce products.
 * Version:           1.0.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            BulkBoost
 * Author URI:        https://bulkboost.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       bulkboost
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
define('BLKBST_VERSION', '1.0.0');

/**
 * Declare compatibility with WooCommerce High-Performance Order Storage (HPOS).
 */
add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-bulkboost-activator.php
 */
function BLKBST_activate_bulkboost()
{
    if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')), true)) {
        require_once plugin_dir_path(__FILE__) . 'includes/class-bulkboost-activator.php';
        BulkBoost_Activator::activate();
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

        $options = get_option('bulkboost_settings');
        $optionsMinMax = get_option('min_max_bulkboost_settings');

        if (false === $options) {
            update_option('bulkboost_settings', $defaults);
        } else {
            $updated_options = wp_parse_args($options, $defaults);
            update_option('bulkboost_settings', $updated_options);
        }

        if (false === $optionsMinMax) {
            update_option('min_max_bulkboost_settings', $defaultsMinMax);
        } else {
            $updated_options = wp_parse_args($options, $defaultsMinMax);
            update_option('min_max_bulkboost_settings', $updated_options);
        }
    } else {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die('BulkBoost for WooCommerce requires WooCommerce to be installed and active. <br><a href="' . admin_url('plugins.php') . '">&laquo; Return to Plugins</a>');
    }

}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-bulkboost-deactivator.php
 */
function BLKBST_deactivate_bulkboost()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-bulkboost-deactivator.php';
    BulkBoost_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'BLKBST_activate_bulkboost');
register_deactivation_hook(__FILE__, 'BLKBST_deactivate_bulkboost');

function uninstall_bulkboost()
{
    // Delete the plugin settings from the database
    delete_option('bulkboost_settings');
    delete_option('min_max_bulkboost_settings');
}

register_uninstall_hook(__FILE__, 'uninstall_bulkboost');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-bulkboost.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_bulkboost()
{
    $plugin = new BLKBST_BulkBoost();
    $plugin->run();
}

run_bulkboost();