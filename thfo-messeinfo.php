<?php
/*
  Plugin Name: Thfo - Messinfo
  Version: 1.0
  Plugin URI: http://www.Thivinfo.com
  Description: Affichage des messes
  Author: Thivinfo
  Author URI: http://www.thivinfo.com
  Domain Path: languages
  Network: false
  Text Domain: thfo-messinfo
  Depends:

  --------------

 */
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

add_action( 'widgets_init', function () {
	register_widget( 'widget' );
} );

// Plugin constants
define( 'THFO_MESSINFO_VERSION', '1.0' );
define( 'THFO_MESSINFO_CPT_NAME', 'marque' );
define( 'THFO_MESSINFO_FOLDER', 'thfo-messinfo' );
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
_thfo_messinfo_load_files( THFO_MESSINFO_DIR . 'classes/', array( 'widget', 'shortcode','visual_composer' ) );


if( is_admin() ){
	_thfo_messinfo_load_files( THFO_MESSINFO_DIR . 'classes/admin/', array( 'settings' ) );
}

add_action( 'plugins_loaded', 'init_thfo_messinfo_plugin' );
function init_thfo_messinfo_plugin() {
	// Load client
	new widget();
	new thfo_messeinfo_shortcode();
	new visual_composer();

	 if( is_admin() ){
		new settings();
	}
}

add_action( 'wp_enqueue_scripts', 'thfo_add_script' );
function thfo_add_script(){
	wp_enqueue_script('messeinfo','http://egliseinfo.catholique.fr/Widget/Widget.nocache.js');
}

add_action('wp_enqueue_scripts', 'thfo_add_style') ;
function thfo_add_style(){
	wp_enqueue_style('messeinfo', plugins_url('thfo-messeinfo/assets/messeinfo.css'));
}