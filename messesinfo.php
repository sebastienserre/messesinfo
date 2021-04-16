<?php
/*
  Plugin Name: Messesinfo
  Plugin URI: https://paroisse-catholique.fr
  Description: Affichez vos horaires de messes facilement sur votre site.
  Version: 1.5.7
  Author: paroisse-catholique.fr
  Stable tag: 1.5.7
  Tested up to: 5.7
  Author URI: https://paroisse-catholique.fr/
  Text Domain: messesinfo
 */
// don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

// Plugin constants
define( 'THFO_MESSINFO_VERSION', '1.5.7' );
define( 'THFO_MESSINFO_FOLDER', 'messesinfo' );
define( 'THFO_MESSINFO_URL', plugin_dir_url( __FILE__ ) );
define( 'THFO_MESSINFO_DIR', plugin_dir_path( __FILE__ ) );

add_action( 'plugins_loaded', 'messesinfo_load_file' );
function messesinfo_load_file() {
	require_once THFO_MESSINFO_DIR . 'classes/shortcode.php';
}

add_action( 'admin_print_styles', 'messesinfo_load_admin_style' );
function messesinfo_load_admin_style() {
	wp_enqueue_style( 'admin-messesinfo', THFO_MESSINFO_URL . 'assets/css/admin.css' );
}

add_action( 'plugins_loaded', 'init_thfo_messinfo_plugin' );
function init_thfo_messinfo_plugin() {
	// Load client
	new thfo_messeinfo_shortcode();

}


add_action( 'wp_enqueue_scripts', 'thfo_add_style' );
function thfo_add_style() {
	wp_enqueue_style( 'messeinfocss', plugins_url( 'assets/css/messeinfo.css', __FILE__ ) );
}
