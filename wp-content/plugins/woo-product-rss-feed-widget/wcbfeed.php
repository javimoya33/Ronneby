<?php
add_action( 'admin_init', 'wcbfeed_admin_init' );

// create custom plugin settings menu
add_action( 'admin_menu', 'wcbfeed_create_menu' );


function wcbfeed_admin_init() {
	wp_register_style( 'wcbfeedAdminStylesheet', plugins_url('css/style.css', __FILE__) );
}

function wcbfeed_create_menu() {
	add_options_page('WooCommerce Better Feeds', 'WooCommerce RSS Feed OutPut', 'administrator', __FILE__, 'wcbfeed_settings_page');
	add_action('admin_init', 'register_wcbfeedsettings');
	wp_enqueue_style( 'wcbfeedAdminStylesheet' );
}

function register_wcbfeedsettings() {
	register_setting('wcbfeed-settings-group', 'wcbfeed_rss_image_size');
	register_setting('wcbfeed-settings-group', 'wcbfeed_rss_export_image');
	register_setting('wcbfeed-settings-group', 'wcbfeed_rss_export_price');
}

function wcbfeed_settings_page() {
	$wcbfeed_rss_image_size 			= get_option('wcbfeed_rss_image_size');
	if (empty($wcbfeed_rss_image_size)){
		update_option('wcbfeed_rss_image_size', 'thumbnail');
		$wcbfeed_rss_image_size	= get_option('wcbfeed_rss_image_size');
	}
	include('includes/wcbfeed-header.php');
	include('includes/wcbfeed-option-page.php');
	include('includes/wcbfeed-footer.php');
}