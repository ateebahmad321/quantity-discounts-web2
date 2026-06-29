<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://profiles.wordpress.org/ateebahamd
 * @since      1.0.0
 *
 * @package    BulkBoost
 * @subpackage BulkBoost/includes
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
 * @package    BulkBoost
 * @subpackage BulkBoost/includes
 * @author     BulkBoost <ateebahmad76@gmail.com>
 */
class BLKBST_BulkBoost
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      BulkBoost_Loader $loader Maintains and registers all hooks for the plugin.
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
        if (defined('BLKBST_VERSION')) {
            $this->version = BLKBST_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->plugin_name = 'bulkboost';

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
     * - BulkBoost_Loader. Orchestrates the hooks of the plugin.
     * - BulkBoost_i18n. Defines internationalization functionality.
     * - BulkBoost_Admin. Defines all hooks for the admin area.
     * - BulkBoost_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bulkboost-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-bulkboost-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-bulkboost-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-bulkboost-public.php';

        $this->loader = new BulkBoost_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the BulkBoost_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {
        $plugin_i18n = new BulkBoost_i18n();

        // load_plugin_textdomain() is not needed on WordPress.org since WP 4.6.
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
        $plugin_admin = new BLKBST_BulkBoost_Admin($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_dashboard_assets');
        $this->loader->add_filter('admin_body_class', $plugin_admin, 'bb_admin_body_class');
        $this->loader->add_action('wp_ajax_bulkboost_save_design', $plugin_admin, 'BLKBST_save_design_settings');
        // Settings menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'BLKBST_bulkboost_admin_menu_page');

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'BLKBST_enqueue_quantity_breaks_scripts');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'BLKBST_enqueue_min_max_scripts');

        // product panel
        $this->loader->add_filter(
            'woocommerce_product_data_tabs',
            $plugin_admin,
            'BLKBST_bulkboost_product_data_tabs'
        );
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'quantity_breaks_icon_change', 20);
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_product_panel_styles', 20);
        $this->loader->add_action(
            'woocommerce_product_data_panels',
            $plugin_admin,
            'BLKBST_quantity_breaks_product_data_panels'
        );
        $this->loader->add_action('save_post', $plugin_admin, 'BLKBST_save_bulkboost');
        $this->loader->add_action('admin_notices', $plugin_admin, 'BLKBST_bulkboost_admin_notices');
        // settings page
        $this->loader->add_action(
            'admin_init',
            $plugin_admin,
            'BLKBST_bulkboost_register_settings'
        );
        $this->loader->add_filter(
            'plugin_action_links_bulkboost/bulkboost.php',
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
        $plugin_public = new BLKBST_BulkBoost_Public($this->get_plugin_name(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('woocommerce_before_add_to_cart_button', $plugin_public, 'BLKBST_add_custom_quantity_block');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'output_custom_styles', 20);
        $this->loader->add_filter(
            'woocommerce_add_cart_item_data',
            $plugin_public,
            'BLKBST_add_custom_product_data_to_cart',
            10,
            3
        );
        $this->loader->add_action(
            'woocommerce_before_calculate_totals',
            $plugin_public,
            'BLKBST_update_cart_item_price',
            20,
            1
        );
        $this->loader->add_action(
            'woocommerce_before_add_to_cart_button',
            $plugin_public,
            'BLKBST_add_custom_price_field_to_product_form'
        );

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'BLKBST_remove_quantity_field_on_product_pages', 20);

        // General Settings (Pro): lock the quantity field in cart / checkout.
        $this->loader->add_filter('woocommerce_cart_item_quantity', $plugin_public, 'BLKBST_lock_cart_quantity', 10, 3);
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'BLKBST_lock_checkout_quantity', 20);
        // Block-based Cart & Checkout (Store API).
        $this->loader->add_filter('woocommerce_store_api_product_quantity_editable', $plugin_public, 'BLKBST_lock_block_quantity', 10, 3);
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
     * @return    BulkBoost_Loader    Orchestrates the hooks of the plugin.
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
