<?php

if ( function_exists( "register_field_group" ) ) {
	register_field_group( array(
		'id'         => 'acf_messeinfo',
		'title'      => 'messeinfo',
		'fields'     => array(
			array(
				'key'           => 'field_56f10de081430',
				'label'         => 'select',
				'name'          => 'select',
				'type'          => 'select',
				'choices'       => array(
					78 => 78,
					92 => 92,
				),
				'default_value' => '',
				'allow_null'    => 0,
				'multiple'      => 0,
			),
		),
		'location'   => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'messeinfo_post_type',
					'order_no' => 0,
					'group_no' => 0,
				),
			),
		),
		'options'    => array(
			'position'       => 'normal',
			'layout'         => 'no_box',
			'hide_on_screen' => array(),
		),
		'menu_order' => 0,
	) );
}