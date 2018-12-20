<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://webappick.com
 * @since             1.0.0
 * @package           Woo_Feed
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Product Feed
 * Plugin URI:        https://webappick.com/
 * Description:       This plugin generate woocommerce product feed for Shopping Engines like Google Shopping,Facebook Product Feed,eBay,Amazon,Idealo and many more..
 * Version:           2.0.15
 * Author:            WebAppick
 * Author URI:        https://webappick.com/
 * License:           GPL v2
 * License URI:       http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain:       woo-feed
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-woo-feed.php';


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-woo-feed-activator.php
 */

function activate_woo_feed()
{
    require plugin_dir_path(__FILE__) . 'includes/class-woo-feed-activator.php';
    Woo_Feed_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-woo-feed-deactivator.php
 */
function deactivate_woo_feed()
{
    require plugin_dir_path(__FILE__) . 'includes/class-woo-feed-deactivator.php';
    Woo_Feed_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_woo_feed');
register_deactivation_hook(__FILE__, 'deactivate_woo_feed');

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_woo_feed()
{
    $plugin = new Woo_Feed();
    $plugin->run();
}

run_woo_feed();

// Custom Cron Recurrences
function custom_cron_job_custom_recurrence($schedules)
{
    $interval = get_option('wf_schedule');
    $schedules['woo_feed_corn'] = array(
        'display' => __('Woo Feed Update Interval', 'woo-feed'),
        'interval' => $interval,
    );

    return $schedules;
}

# Update the schedule interval
add_filter('cron_schedules', 'custom_cron_job_custom_recurrence');
add_action('woo_feed_update', 'woo_feed_cron_update_feed');


# Load Feed Templates
add_action('wp_ajax_get_feed_merchant', 'feed_merchant_view');
function feed_merchant_view()
{
    check_ajax_referer('wpf_feed_nonce');
    $dropDown = new Woo_Feed_Dropdown();
    $product = new Woo_Feed_Products();
    $attributes=new Woo_Feed_Default_Attributes();
    $merchant = sanitize_text_field($_POST['merchant']);
    $provider = sanitize_text_field($_POST['merchant']);
    if(strpos($merchant,'amazon')!==false){
        include plugin_dir_path(__FILE__) . "admin/partials/amazon/add-feed.php";
    }else{
        include plugin_dir_path(__FILE__) . "admin/partials/$merchant/add-feed.php";
    }
    die();
}

/* Show Feed Making Progress Via AJAX
 *
 * @return array
 */
add_action('wp_ajax_feed_progress_info', 'woo_feed_feed_progress_info');
function woo_feed_feed_progress_info()
{
    $info = get_option('wpf_progress');
    $progress = "";
    if ($info != " ") {
        $progress = 'going';
    } else {
        $progress = 'complete';
    }
    sleep(2);
    $response = array('data' => $info, 'progress' => $progress);
    wp_send_json_success($response);
    die();
}

/* Make Feed Via AJAX
 *
 * @return array
 */
add_action('wp_ajax_feed_info_post', 'makeAjaxFeed');
function makeAjaxFeed()
{
    check_ajax_referer('wpf_feed_nonce');
    update_option('wpf_progress', 'Sanitizing Configuration Data');
    sleep(1);
    if (isset($_POST['data'])) {
        parse_str($_POST['data'], $output);
        $info = $output;
    } elseif (isset($_POST['feedname'])) {
        $options = unserialize(get_option(sanitize_text_field($_POST['feedname'])));
        $info = $options['feedrules'];
    }

    set_time_limit(0);
    if (count($info) && isset($info['provider'])) {

        # GEt Post data
        if ($info['provider'] == 'google') {
            $merchant = "Woo_Feed_Google";
        } elseif ($info['provider'] == 'facebook') {
            $merchant = "Woo_Feed_Facebook";
        }elseif (strpos($info['provider'],'amazon') !==FALSE) {
            $merchant = "Woo_Feed_Amazon";
        } else {
            $merchant = "Woo_Feed_Custom";
        }
        $feedService = sanitize_text_field($info['provider']);
        $fileName = str_replace(" ", "", sanitize_text_field($info['filename']));
        $type = sanitize_text_field($info['feedType']);
        $feedRules = $info;
        # FTP File Upload Info
        update_option('wpf_progress', 'Checking FTP Configuration');
        sleep(1);
        $ftphost = sanitize_text_field($info['ftphost']);
        $ftpuser = sanitize_text_field($info['ftpuser']);
        $ftppassword = sanitize_text_field($info['ftppassword']);
        $ftppath = sanitize_text_field($info['ftppath']);
        $ftpenabled = sanitize_text_field($info['ftpenabled']);


        # Get Feed info
        update_option('wpf_progress', 'Making Feed');
        sleep(1);
        $products = new Woo_Generate_Feed($merchant, $feedRules);
        $string = $products->getProducts();
        # Check If any products founds
        if ($string) {

            $upload_dir = wp_upload_dir();
            $base = $upload_dir['basedir'];

            # Save File
            $path = $base . "/woo-feed/" . $feedService . "/" . $type;
            $file = $path . "/" . $fileName . "." . $type;
            update_option('wpf_progress', 'Saving File');
            sleep(1);
            $save = new Woo_Feed_Savefile();
            if ($type == "csv") {
                $saveFile = $save->saveCSVFile($path, $file, $string, $info);
            } else {
                $saveFile = $save->saveFile($path, $file, $string);
            }


            # Save Info into database
            $url = $upload_dir['baseurl'] . "/woo-feed/" . $feedService . "/" . $type . "/" . $fileName . "." . $type;

            $feedInfo = array(
                'feedrules' => $feedRules,
                'url' => $url,
                'last_updated' => date("Y-m-d H:i:s")
            );

            update_option('wpf_progress', 'Saving Feed Info');
            sleep(1);
            $update = update_option('wf_feed_' . $fileName, serialize($feedInfo));
            update_option('wpf_progress', 'complete');
            sleep(1);
            update_option('wpf_progress', ' ');
            sleep(5);
            if ($saveFile) {
                $getInfo = get_option('wf_feed_' . $fileName);
                $response = array('success' => true, 'message' => unserialize($getInfo));
                wp_send_json_success($response);
            }
        }
    }
    update_option('wpf_progress', ' ');
    sleep(2);
    $response = array('success' => false);
    wp_send_json_success($response);
    wp_die();
}

/*
 * Update Feed Information
 */
function woo_feed_add_update($info = "", $name = "")
{
    set_time_limit(0);
    if (count($info) && isset($info['provider'])) {
        # GEt Post data
        if ($info['provider'] == 'google') {
            $merchant = "Woo_Feed_Google";
        } elseif ($info['provider'] == 'facebook') {
            $merchant = "Woo_Feed_Facebook";
        }elseif (strpos($info['provider'],'amazon') !==FALSE) {
            $merchant = "Woo_Feed_Amazon";
        }  else {
            $merchant = "Woo_Feed_Custom";
        }



        $feedService = sanitize_text_field($info['provider']);
        $fileName = str_replace(" ", "", sanitize_text_field($info['filename']));
        $type = sanitize_text_field($info['feedType']);

        $feedRules = $info;

        # FTP File Upload Info
        $ftphost = sanitize_text_field($info['ftphost']);
        $ftpuser = sanitize_text_field($info['ftpuser']);
        $ftppassword = sanitize_text_field($info['ftppassword']);
        $ftppath = sanitize_text_field($info['ftppath']);
        $ftpenabled = sanitize_text_field($info['ftpenabled']);

        # Get Feed info
        $products = new Woo_Generate_Feed($merchant, $feedRules);
        $string = $products->getProducts();

        # Check If any products founds
        if ($string) {

            $upload_dir = wp_upload_dir();
            $base = $upload_dir['basedir'];

            # Save File
            $path = $base . "/woo-feed/" . $feedService . "/" . $type;
            $file = $path . "/" . $fileName . "." . $type;
            $save = new Woo_Feed_Savefile();
            if ($type == "csv") {
                $saveFile = $save->saveCSVFile($path, $file, $string, $info);
            } else {
                $saveFile = $save->saveFile($path, $file, $string);
            }

            # Upload file to ftp server
            if ($ftpenabled) {
                $ftp = new FTPClient();
                $ftp->connect($ftphost, $ftpuser, $ftppassword);
                $ftp->uploadFile($file, $ftppath . "/" . $fileName . "." . $type);
                $ftp->getMessages();
            }

            # Save Info into database
            $url = $upload_dir['baseurl'] . "/woo-feed/" . $feedService . "/" . $type . "/" . $fileName . "." . $type;
            $feedInfo = array(
                'feedrules' => $feedRules,
                'url' => $url,
                'last_updated' => date("Y-m-d H:i:s")
            );

            if (!empty($name) && $name != "wf_feed_" . $fileName) {
                delete_option($name);
            }

            $update = update_option('wf_feed_' . $fileName, serialize($feedInfo));
            if ($saveFile) {
                $getInfo = unserialize(get_option('wf_feed_' . $fileName));
                $url = $getInfo['url'];
                return $url;
            } else {
                return false;
            }
        }
    }

    return false;
}

/**
 * Sanitize array post
 *
 * @param $array
 *
 * @return array
 */
function woo_feed_array_sanitize($array)
{
    $newArray = array();
    if (count($array)) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $key2 => $value2) {
                    if (is_array($value2)) {
                        foreach ($value2 as $key3 => $value3) {
                            $newArray[$key][$key2][$key3] = sanitize_text_field($value3);
                        }
                    } else {
                        $newArray[$key][$key2] = sanitize_text_field($value2);
                    }
                }
            } else {
                $newArray[$key] = sanitize_text_field($value);
            }
        }
    }
    return $newArray;
}


/**
 * Generate Feed
 */

function woo_feed_generate_feed()
{
    if (isset($_POST['provider'])) {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $process = woo_feed_add_update($_POST);

        if ($process) {
            $message = "<b>Feed Making Complete. Feed URL: <a style='font-weight: bold;color:green;' target='_blank' href=$process>$process</a></b>";
            update_option('wpf_message', $message);
            wp_redirect(admin_url("admin.php?page=woo_feed_manage_feed&wpf_message=success"));
        } else {
            update_option('wpf_message', 'Failed To Make Feed');
            wp_redirect(admin_url("admin.php?page=woo_feed_manage_feed&wpf_message=error"));
        }
    } else {
        echo "<div class='notice notice-warning is-dismissible'><p>" . __("You are awesome for using <b>WooCommerce Product Feed</b>. Free version works great for up to <b>2000 products including variations.</b>", 'woo-feed') . "</p></div>";
        require plugin_dir_path(__FILE__) . 'admin/partials/woo-feed-admin-display.php';
    }
}


/**
 * Manage Feeds
 */
function woo_feed_manage_feed()
{
    // Manage action for category mapping
    if (isset($_GET['action']) && $_GET['action'] == 'edit-feed') {
        $fname = sanitize_text_field($_GET['feed']);
        if (count($_POST) && isset($_POST['provider']) && isset($_POST['edit-feed'])) {
            // if (woo_feed_add_update($_POST, $_GET['feed'])) {
            $process = woo_feed_add_update($_POST, $fname);
            if ($process) {
                $message = "<b>Feed Updated Successfully. Feed URL: <a style='font-weight: bold;color:green;' target='_blank' href=$process>$process</a></b>";
                update_option('wpf_message', $message);
                wp_redirect(admin_url("admin.php?page=woo_feed_manage_feed&wpf_message=success"));
            } else {
                update_option('wpf_message', 'Failed To Update Feed');
                wp_redirect(admin_url("admin.php?page=woo_feed_manage_feed&wpf_message=error"));
            }
        }

        if (isset($fname)) {
            $feedInfo = unserialize(get_option($fname));
            $provider = strtolower($feedInfo['feedrules']['provider']);
            $feedRules = $feedInfo['feedrules'];
            if ($provider == "custom" || $provider == "amazon" || $provider == "adwords") {
                require plugin_dir_path(__FILE__) . "admin/partials/custom/edit-feed.php";
            } else {
                require plugin_dir_path(__FILE__) . "admin/partials/woo-feed-edit-template.php";
            }
        }
    } else {
        # Update Interval
        if (isset($_POST['wf_schedule'])) {
            if (update_option('wf_schedule', sanitize_text_field($_POST['wf_schedule']))) {
                wp_clear_scheduled_hook('woo_feed_update');
                add_filter('cron_schedules', 'custom_cron_job_custom_recurrence');
                wp_schedule_event(time(), 'woo_feed_corn', 'woo_feed_update');
            }
        }
        require plugin_dir_path(__FILE__) . 'admin/partials/woo-feed-manage-list.php';
    }
}

/**
 * Difference between free and premium plugin
 */
function woo_feed_pro_vs_free(){
    require plugin_dir_path(__FILE__) . 'admin/partials/woo-feed-pro-vs-free.php';
}

/**
 * Difference between free and premium plugin
 */
function woo_feed_feed_optimization(){
    require plugin_dir_path(__FILE__) . 'admin/partials/woo-feed-pro-vs-free.php';
}


/*
 * Scheduled Action Hook
 */
function woo_feed_cron_update_feed()
{
    global $wpdb;
    $var = "wf_feed_";
    $query = $wpdb->prepare("SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", $var . "%");
    $result = $wpdb->get_results($query, 'ARRAY_A');
    foreach ($result as $key => $value) {
        $feedInfo = unserialize(get_option($value['option_name']));
        woo_feed_add_update($feedInfo['feedrules']);
    }
}


