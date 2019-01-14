<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 15/02/16
 * Time: 15:06
 */
class visual_composer {

public static $department = array();

	public function __construct() {


		add_action('vc_before_init', array($this,'thfo_vc_widget'));
		//add_action('admin_init', array($this,'thfo_city'));
	}

	public function thfo_vc_widget(){
		if ( class_exists( 'Vc_Manager' ) ) {
			$this->thfo_get_dept();
			vc_map( array(
				'name'                    => 'MessesInfo',
				'base'                    => 'messeinfo',
				'show_settings_on_create' => true,
				'description'             => __( 'Add Messeinfo in your Website', 'messesinfo' ),
				'params'                  => array(
					array(
						"type"        => "textfield",
						"holder"      => "div",
						"class"       => "",
						"heading"     => __( "City", "messesinfo" ),
						"param_name"  => "city",
						"value"       => __( "City Name", "messesinfo" ),
						"description" => __( "Entrez votre commune.", "messesinfo" ),
					),
						array(
							'type' => 'dropdown',
							'heading' => __('Choose your department', "messesinfo"),
							'param_name' => 'dept',
							//'value' => array($this, 'thfo_get_dept' ),
							'value' => self::$department,
							"description" => __( "Choose text color", "my-text-domain" )
						),
				)
			)
			);
			//vc_map( $params );
		}
	}

	public static function thfo_get_dept() {
		$url   = file_get_contents( "http://www.messes.info/api/v2/departements?format=JSON" );
		$depts = json_decode( $url );
		//var_dump($depts);
			foreach ( $depts as $dept ) {
					self::$department[] .= $dept->query;
			}
		return self::$department;

	}



}