<?php 
/*
Plugin Name: Woo Product RSS Feed Widget
Plugin URI: http://calicotek.com/wp-plugins/woocommerce-product-rss-widget
Description: 2 in 1 Woocommerce compatible RSS Feed Widget with #1: customizable Product rss slider Widget. With Feed title, description, excerpt, image and many other things you can control. #2: Fix for woocommerce / wordpress rss feed core adds product image and price fields to the rss field output so the widget shows images and prices this plugin can be added to that source website.
Version: 1.2
Author: ModManMatt, Steve .A
Author URI: http://calicotek.com
License: GPL3
*/ 
        
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    include('functions.php');  // adds main function
        
	include('woo-better-feeds.php');  // adds woocommerce rss better feeds addon to add image and price to feed output site.

    function register_rfw_scripts() {
            	
			wp_enqueue_script(
				'rfw-script',
				plugins_url('functions.js', __FILE__),
				array( 'jquery' )
			);
			
			wp_enqueue_script(
				'rfw-slider-script',
				plugins_url('jquery.bxslider.js', __FILE__),
				array( 'jquery' )
			);			

            wp_register_style('rfw-style', plugins_url('style.css', __FILE__));
			wp_register_style('rfw-slider-style', plugins_url('jquery.bxslider.css', __FILE__));
			
			wp_enqueue_style( 'rfw-style' );
			wp_enqueue_style( 'rfw-slider-style' );
 
        }
	   
	add_action( 'wp_enqueue_scripts', 'register_rfw_scripts' );

	add_action( 'widgets_init', 'rfw_init');