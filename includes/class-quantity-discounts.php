<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tallpro.com
 * @since      1.0.0
 *
 * @package    Quantity_Discounts
 * @subpackage Quantity_Discounts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quantity_Discounts
 * @subpackage Quantity_Discounts/includes
 * @author     WPIron <info@tallpro.com>
 */
class WIRNQDSQ_Quantity_Discounts
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Quantity_Discounts_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('WIRNQDSQ_QUANTITY_DISCOUNTS_VERSION')) {
            $this->version = WIRNQDSQ_QUANTITY_DISCOUNTS_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'quantity-discounts';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Quantity_Discounts_Loader. Orchestrates the hooks of the plugin.
     * - Quantity_Discounts_i18n. Defines internationalization functionality.
     * - Quantity_Discounts_Admin. Defines all hooks for the admin area.
     * - Quantity_Discounts_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-quantity-discounts-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-quantity-discounts-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-quantity-discounts-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-quantity-discounts-public.php';

        $this->loader = new Quantity_Discounts_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Quantity_Discounts_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new Quantity_Discounts_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new WIRNQDSQ_Quantity_Discounts_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_notices', $plugin_admin, 'WIRNQDSQ_update_notice');
        // Settings menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'WIRNQDSQ_quantity_discounts_admin_menu_page');

        // submit form
        $this->loader->add_action('admin_post_nopriv_submit_form', $plugin_admin, 'handle_form_submission');
        $this->loader->add_action('admin_post_submit_form', $plugin_admin, 'handle_form_submission');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'WIRNQDSQ_enqueue_quantity_breaks_scripts');

        // product panel
        $this->loader->add_filter(
            'woocommerce_product_data_tabs',
            $plugin_admin,
            'WIRNQDSQ_quantity_discounts_product_data_tabs__premium_only'
        );
        $this->loader->add_action('admin_head', $plugin_admin, 'quantity_breaks_icon_change');
        $this->loader->add_action(
            'woocommerce_product_data_panels',
            $plugin_admin,
            'WIRNQDSQ_quantity_breaks_product_data_panels__premium_only'
        );
        $this->loader->add_action('save_post', $plugin_admin, 'WIRNQDSQ_save_quantity_discounts');
        $this->loader->add_action('admin_init', $plugin_admin, 'WIRNQDSQ_ignore_notice_wpiron');
        $this->loader->add_action('wp_ajax_dismiss_admin_notice', $plugin_admin, 'WIRNQDSQ_dismiss_admin_notice');
        $this->loader->add_action('admin_notices', $plugin_admin, 'WIRNQDSQ_quantity_discounts_admin_notices');
        // settings page
        $this->loader->add_action(
            'admin_init',
            $plugin_admin,
            'WIRNQDSQ_quantity_discounts_register_settings__premium_only'
        );
        $this->loader->add_filter(
            'plugin_action_links_quantity-discounts/quantity-discounts.php',
            $plugin_admin,
            'links_to_menu'
        );

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks()
    {
        $plugin_public = new WIRNQDSQ_Quantity_Discounts_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_before_add_to_cart_button', $plugin_public, 'WIRNQDSQ_add_custom_quantity_block');
        $this->loader->add_action('wp_head', $plugin_public, 'output_custom_styles');
        $this->loader->add_filter(
            'woocommerce_add_cart_item_data',
            $plugin_public,
            'WIRNQDSQ_add_custom_product_data_to_cart',
            10,
            3
        );
        $this->loader->add_action(
            'woocommerce_before_calculate_totals',
            $plugin_public,
            'WIRNQDSQ_update_cart_item_price',
            20,
            1
        );
        $this->loader->add_action(
            'woocommerce_before_add_to_cart_button',
            $plugin_public,
            'WIRNQDSQ_add_custom_price_field_to_product_form'
        );

        $this->loader->add_action('wp_head', $plugin_public, 'WIRNQDSQ_remove_quantity_field_on_product_pages');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.0.0
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Quantity_Discounts_Loader    Orchestrates the hooks of the plugin.
     * @since     1.0.0
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.0.0
     */
    public function get_version()
    {
        return $this->version;
    }


}
