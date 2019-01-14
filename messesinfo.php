<?php
/*
  Plugin Name: Messesinfo
  Plugin URI: https://5pains-et-2poissons.fr/extensions-wordpress-messesinfos/
  Description: Display mass schedule on your website!
  Version: 1.4.0
  Author: 5P2P
  Stable tag: 1.4.0
Tested up to: 4.8
  Author URI: http://5pains-et-2poissons.fr/
  Text Domain: messesinfo
Domain path: /languages
 */
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}


add_action( 'widgets_init', function () {
	register_widget( 'messesinfos_widget' );
} );

// Plugin constants
define( 'THFO_MESSINFO_VERSION', '1.4.0' );
define( 'THFO_MESSINFO_FOLDER', 'messinfo' );
define( 'THFO_MESSINFO_URL', plugin_dir_url( __FILE__ ) );
define( 'THFO_MESSINFO_DIR', plugin_dir_path( __FILE__ ) );

// Function for easy load files
function _thfo_messinfo_load_files( $dir, $files, $prefix = '' ) {
	foreach ( $files as $file ) {
		if ( is_file( $dir . $prefix . $file . ".php" ) ) {
			require_once( $dir . $prefix . $file . ".php" );
		}
	}
}

// Plugin client classes
_thfo_messinfo_load_files( THFO_MESSINFO_DIR . 'classes/', array( 'shortcode','visual_composer','widget' ) );


if( is_admin() ){
	_thfo_messinfo_load_files( THFO_MESSINFO_DIR . 'classes/admin/', array( 'settings' ) );
}

add_action( 'plugins_loaded', 'init_thfo_messinfo_plugin' );
function init_thfo_messinfo_plugin() {
	// Load client
	new messesinfos_widget();
	new thfo_messeinfo_shortcode();
	//new visual_composer();

	 if( is_admin() ){
		new settings();
	}
}


add_action('wp_enqueue_scripts', 'thfo_add_style') ;
function thfo_add_style(){
    wp_enqueue_style('messeinfo', plugins_url('assets/css/messeinfo.css', __FILE__ ));
}

add_action( 'plugins_loaded', 'thfo_load_textdomain');
function thfo_load_textdomain() {
	load_plugin_textdomain( 'messesinfo', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}

function messesinfos_add_scripts() {
	$api = get_option('messesinfos_gmap_key');
	wp_enqueue_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key='.$api, array(), '3', true );
	wp_enqueue_script( 'google-map-init', plugins_url('assets/js/messesinfos-map.js', __FILE__ ), array('google-map', 'jquery'), '0.1', true );
}

add_action( 'wp_enqueue_scripts', 'messesinfos_add_scripts' );