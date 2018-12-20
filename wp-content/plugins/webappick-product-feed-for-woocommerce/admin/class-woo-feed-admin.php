<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin
 * @author     Ohidul Islam <wahid@webappick.com>
 */
class Woo_Feed_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $woo_feed The ID of this plugin.
     */
    private $woo_feed;

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
     * @since    1.0.0
     *
     * @param      string $woo_feed The name of this plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($woo_feed, $version)
    {

        $this->woo_feed = $woo_feed;
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
         * defined in woo_feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The woo_feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_register_style($this->woo_feed, plugin_dir_url(__FILE__) . 'css/woo-feed-admin.css', array(), $this->version, 'all');
        wp_enqueue_style($this->woo_feed);

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
         * defined in Woo_Feed_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The woo_feed_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_register_script($this->woo_feed . "_typeahead", plugin_dir_url(__FILE__) . 'js/typeahead.js', array(), $this->version, false);
        wp_enqueue_script($this->woo_feed . "_typeahead");
        wp_register_script($this->woo_feed . "_jvalidate", plugin_dir_url(__FILE__) . 'js/jquery.validate.min.js', array(), $this->version, false);
        wp_enqueue_script($this->woo_feed . "_jvalidate");
        wp_register_script($this->woo_feed . "_jvalidateadition", plugin_dir_url(__FILE__) . 'js/additional-methods.min.js', array(), $this->version, false);
        wp_enqueue_script($this->woo_feed . "_jvalidateadition");

        wp_register_script($this->woo_feed . "_shortable", plugin_dir_url(__FILE__) . 'js/jquery-sortable.js', array(), $this->version, false);
        wp_enqueue_script($this->woo_feed . "_shortable");

        wp_register_script($this->woo_feed, plugin_dir_url(__FILE__) . 'js/woo-feed-admin.js', array('jquery'), $this->version, false);

        $wpf_feed_nonce = wp_create_nonce('wpf_feed_nonce');
        wp_localize_script($this->woo_feed, 'wpf_ajax_obj', array(
            'wpf_ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => $wpf_feed_nonce,
        ));

        wp_enqueue_script($this->woo_feed);

    }

    /**
     * Register the Plugin's Admin Pages for the admin area.
     *
     * @since    1.0.0
     */
    public function load_admin_pages()
    {
        /**
         * This function is provided for making admin pages into admin area.
         *
         * An instance of this class should be passed to the run() function
         * defined in WOO_FEED_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The WOO_FEED_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        if (function_exists('add_options_page')) {

            add_menu_page(__('Woo Feed', 'woo-feed'), __('Woo Feed', 'woo-feed'), 'manage_options', __FILE__, 'woo_feed_generate_feed', 'dashicons-rss');
            add_submenu_page(__FILE__, __('Make Feed', 'woo-feed'), __('Make Feed', 'woo-feed'), 'manage_options', __FILE__, 'woo_feed_generate_feed');
            add_submenu_page(__FILE__, __('Manage Feeds', 'woo-feed'), __('Manage Feeds', 'woo-feed'), 'manage_options', 'woo_feed_manage_feed', 'woo_feed_manage_feed');
            add_submenu_page(__FILE__, __('Premium', 'woo-feed'), __('Premium', 'woo-feed'), 'manage_options', 'woo_feed_pro_vs_free', 'woo_feed_pro_vs_free');
        }
    }

}
