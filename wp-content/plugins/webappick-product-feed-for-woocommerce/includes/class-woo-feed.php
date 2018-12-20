<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://webappick.com
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
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
 * @package    Woo_Feed
 * @subpackage Woo_Feed/includes
 * @author     Wahid <wahid0003@gmail.com.com>
 */
class Woo_Feed
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Woo_Feed_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $woo_feed The string used to uniquely identify this plugin.
     */
    protected $woo_feed;

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

        $this->woo_feed = 'woo-feed';
        $this->version = '1.5.0';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
    }



    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woo_Feed_Loader. Orchestrates the hooks of the plugin.
     * - Woo_Feed_i18n. Defines internationalization functionality.
     * - Woo_Feed_Admin. Defines all hooks for the admin area.
     * - Woo_Feed_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-feed-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-woo-feed-i18n.php';

        /**
         * The class responsible for getting all product information
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-products.php';
        /**
         * The class responsible for processing feed
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-engine.php';

        /**
         * The class contain all merchants attribute dropdown
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-dropdown.php';

        /**
         * The class contain merchant attributes
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-default-attributes.php';

        /**
         * The class responsible for generating feed
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-generate.php';

        /**
         * The class is a FTP library
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-ftp.php';


        /**
         * The class responsible for save feed
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-savefile.php';

        /**
         * Merchant classes
         */
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-google.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-amazon.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-facebook.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-nextag.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-kelkoo.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-pricegrabber.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-shopzilla.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-shopmania.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-shopping.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-bing.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-become.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-connexity.php';
        require plugin_dir_path(dirname(__FILE__)) . 'includes/feeds/class-woo-feed-custom.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-feed-admin.php';

        /**
         * The class responsible for making list table
         */

        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/classes/class-woo-feed-list-table.php';


        /**
         * The class responsible for making feed list
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-woo-feed-manage-list.php';

        $this->loader = new Woo_Feed_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Woo_Feed_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Woo_Feed_i18n();
        $plugin_i18n->set_domain($this->get_woo_feed());

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

        $plugin_admin = new Woo_Feed_Admin($this->get_woo_feed(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'load_admin_pages');

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
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_woo_feed()
    {
        return $this->woo_feed;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Woo_Feed_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version()
    {
        return $this->version;
    }

}


