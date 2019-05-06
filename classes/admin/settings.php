<?php

use MESSESINFO\API\Messesinfo;

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 12/02/16
 * Time: 12:52
 */
class settings {
	public function __construct() {
		add_action('admin_menu', array($this, 'add_admin_menu'));
		add_action('admin_init', array($this, 'register_settings'));
		add_action( 'admin_init', array( $this, 'search_localityId' ) );
	}

	public function add_admin_menu()

	{
		add_menu_page('MessesInfo', 'MessesInfo', 'manage_options', 'messeinfo', array($this, 'menu_html'),THFO_MESSINFO_URL . 'assets/img/icon.png');
		add_submenu_page('messeinfo',__('Options','messesinfo'),__('Options','messesinfo'),'manage_options', 'option_menu', array($this, 'promote_html'));
		add_submenu_page('messeinfo',__('Tools','messesinfo'),__('Tools','messesinfo'),'manage_options', 'tools', array($this, 'tools_html'));

	}

	public function register_settings(){
		add_settings_section('messeinfo_ads', __('Options','messesinfo'), array($this, 'options_html'), 'messeinfo_settings');
		register_setting('messeinfo_settings', 'thfo_ads');
	//	register_setting('messeinfo_settings', 'messesinfos_gmap_key');
		add_settings_field('thfo_ads', __('Add a promote link?', 'messesinfo'), array($this, 'ads_html'),'messeinfo_settings', 'messeinfo_ads');
	//	add_settings_field('messesinfos_gmap', __('Add a your Google Map API KEY', 'messesinfo'), array($this, 'messesinfos_gmap_key'),'messeinfo_settings', 'messeinfo_ads');

	}

	public function menu_html() {
		echo '<h1>' . get_admin_page_title() . '</h1>';
		echo '<h2>' . __( 'Shortcode') . '</h2>';
		echo '<p>' . __('By default the shortcode [messesinfo] comes without any arguments.','messesinfo'). '</p>';
		echo '<p>' . __('Add the city name or the paroisse name with city as follow to get the info you want:','messesinfo') . ' <strong>' . __('Plaisir 78 or Chapelle Saint Bernard Paris','messesinfo'). '</strong></p>';
		echo '<p>' . __('As you do on <a href="http://egliseinfo.catholique.fr">http://egliseinfo.catholique.fr</a>','messesinfo') .'</p>';
		echo '<p>' . __('Shortcode example: ','messesinfo') .'[messesinfo localityId="LocalityID" result=25]'. '</p>';
	}

	public function tools_html(){
	    ?>
	    <h2><?php _e('Tools', 'messesinfo'); ?></h2>
        <form method="post" action="admin.php?page=tools">
		<label for="localityId"><?php  _e('Find a LocalityId: ','messesinfo') ?></label><input name="localityId" type="text">
	        <?php submit_button(__('Search')); ?>
        </form>
    <?php
        echo $this->search_localityId();
        }

	public function search_localityId(){
		if ( !empty( $_POST['localityId'] ) ){
		    $search = esc_attr( $_POST['localityId'] );
		    $localityID = Messesinfo::get_localityId( $search );
		    add_action( 'messesinfos_localityId_answers', array( $this, 'localityId_answer' ) );
        }
		return $localityID;
    }

    public function localityId_answer( $localityID ){
	    echo $localityID;
    }

	public function options_html(){
		echo '<h2>Options</h2>';
	}

	public function promote_html(){
		?>
		<form method="post" action="options.php">
			<?php settings_fields('messeinfo_settings') ?>
			<?php do_settings_sections('messeinfo_settings') ?>
			<?php submit_button(__('Save')); ?>

		</form>

		<?php
	}



	public function ads_html(){
		$promote = get_option('thfo_ads');?>
		<input type="radio" value="1" name="thfo_ads" <?php if($promote == '1'){ echo "checked"; }?>> <?php _e('yes','messesinfo')?>
		<input type="radio" value="0" name="thfo_ads" <?php if($promote == '0'){ echo "checked"; }?>> <?php _e('no','messesinfo')?>
	<?php }

	public function messesinfos_gmap_key(){
	    $api = get_option('messesinfos_gmap_key');
	    ?>
        <input type="text" name="messesinfos_gmap_key" value="<?php if ($api){ echo $api; } ?>">

    <?php }

}