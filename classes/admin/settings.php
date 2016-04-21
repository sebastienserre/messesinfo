<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 12/02/16
 * Time: 12:52
 */
class settings {
	public function __construct() {
		add_action('admin_menu', array($this, 'add_admin_menu'));
	}

	public function add_admin_menu()

	{

		add_options_page('Messe Info', 'Messe Info', 'manage_options', 'messeinfo', array($this, 'menu_html'));

	}

	public function menu_html() {
		echo '<h1>' . get_admin_page_title() . '</h1>';
		echo '<h2>' . __( 'Shortcode') . '</h2>';
		echo '<p>' . __('By default the shortcode [messeinfo] comes without any arguments and display mass schedule for Paris, France','thfo_messinfo'). '</p>';
		echo '<p>' . __('Add the city name as follow to get the info you want :','thfo_messinfo') . ' <strong>' . __('city=\'marseille\' ','thfo_messinfo'). '</strong></p>';
		echo '<p>' . __('Shortcode example: ','thfo_messinfo') .'[messeinfo city="lyon"]'. '</p>';

	}

}