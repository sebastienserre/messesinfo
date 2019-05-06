<?php
/*
  Plugin Name: Messesinfo
  Plugin URI: https://paroisse-catholique.fr
  Description: Display mass schedule on your website!
  Version: 1.4.0
  Author: thivinfo.com
  Stable tag: 1.4.0
Tested up to: 5.1
  Author URI: https://thivinfo.com/
  Text Domain: messesinfo
 */
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'THFO_MESSINFO_VERSION', '1.4.0' );
define( 'THFO_MESSINFO_FOLDER', 'messesinfo' );
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

add_action( 'plugins_loaded', 'messesinfo_load_file' );
function messesinfo_load_file(){
	require_once THFO_MESSINFO_DIR . 'classes/class-messesinfo.php';
	require_once THFO_MESSINFO_DIR . 'classes/shortcode.php';
}


if( is_admin() ){
	_thfo_messinfo_load_files( THFO_MESSINFO_DIR . 'classes/admin/', array( 'settings' ) );
}

add_action( 'plugins_loaded', 'init_thfo_messinfo_plugin' );
function init_thfo_messinfo_plugin() {
	// Load client
	new thfo_messeinfo_shortcode();

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
