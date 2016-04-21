<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 02/02/16
 * Time: 12:08
 */
class widget extends WP_Widget {
	function __construct() {
		parent::__construct( 'thfo-messinfo', __( 'Widget MesseInfos', 'thfo-messinfo' ), array( 'description' => __( 'Form to add a widget with messinfo', 'thfo-messinfo' ) ) );

	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		?>
		<div class="EgliseInfo-container">
			<div data-egliseinfo="horaires" data-search="<?php echo $instance['city']; ?>" data-limit="1" data-open-in-egliseinfo="true"
			     data-region="fr">
			</div>
			<p>Retrouvez tous les horaires des célébrations sur <a
					href="http://egliseinfo.catholique.fr/horaires/<?php echo $instance['city']; ?>?limit=1">egliseinfo.catholique.fr</a>
			</p>
		</div>

		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$dept = isset( $instance['dept'] ) ? $instance['dept'] : '';
		$city  = isset( $instance['city'] ) ? $instance['city'] : '';
		?>
		<p>
			<label
				for="<?php echo $this->get_field_name( 'title' ); ?>"> <?php _e( 'Title:', 'thfo-messeinfo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
			       name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>

			<label
				for="<?php echo $this->get_field_name( 'city' ); ?>"> <?php _e( 'Your city:', 'thfo-messeinfo' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'city' ); ?>"
			       name="<?php echo $this->get_field_name( 'city' ); ?>" type="text" value="<?php echo $city; ?>"/>
		</p>

		<?php add_option( 'thfo-messeinfo-city', $city );
	}

}