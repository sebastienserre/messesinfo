<?php

/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 02/02/16
 * Time: 12:08
 */
class messesinfos_widget extends WP_Widget {
	function __construct() {
		parent::__construct( 'thfo-messesinfo', __( 'Widget MesseInfos', 'messesinfo' ), array( 'description' => __( 'Form to add a widget with messinfo', 'messesinfo' ) ) );

	}

	public function widget( $args, $instance ) {


		echo $args['before_widget'];

		echo $args['before_title'];

		echo apply_filters( 'widget_title', $instance['title'] );

		echo $args['after_title'];

		$id = str_replace(' ', '%20', $instance['id']);

		/**
		 * Transient deletion if shortcode params changed
		 */

		$search_old = get_option('messeinfo_search_widget' );

		if ( ! $search_old || $search_old =! $instance['id'] ){
			update_option('messeinfo_search_widget', $atts['id'] );
		}

		if ($search_old != $instance['id']) { delete_transient('messeinfo_search_widget'); }
		/**
		 * Transient creation to store data 24h
		 */

		$mass = get_transient( 'messesinfo_data_widget' );
		if ( ! $mass ) {
			$url  = file_get_contents( "http://www.messes.info/api/v2/horaires/" . $id . "?userkey=test&format=json" );
			$mass = json_decode( $url, true );
			set_transient( 'messesinfo_data_widget', $mass, 86400 );
		}

		$i              = 1;
		$instance['nb'] = intval( $instance['nb'] );

		if ( isset( $mass['errorMessage'] ) ) {
			echo '<p>' . $mass['errorMessage'] . '</p>';
		} else {
			?>

            <div class="egliseinfo-container" xmlns="http://www.w3.org/1999/html">
			<?php
			if ( $i <= $instance['nb'] ) {
				foreach ( $mass as $m ) {
					if ( is_array( $m ) ) {
						foreach ( $m as $mess ) {
							//var_dump($mess);
							if ( $i <= $instance['nb'] ) {
								?>

                                <div class="widget-messesinfo messeinfo messeinfo-<?php echo $i ?>">

                                    <div class="mass-infos mass-infos-<?php echo $i ?>">
										<?php
										$newdate = date_timestamp_get( date_create( $mess['date'] ) ); ?>

                                        <p class="mass-date mass-date-<?php echo $i ?>">
                                            <strong><?php echo date_i18n( 'l d F Y', $newdate ) . ' - ' . $mess['time'] ?></strong>
                                        </p>
										<?php if ( $mess['timeType'] === 'SUNDAYMASS' ) {
											$type = __( 'Weekly Mass', 'messesinfo' );
											echo '<p>' . $type . '</p>';
										}; ?>

                                        <a href="http://egliseinfo.catholique.fr/lieu/<?php echo $mess['locality']['id']; ?>"
                                           target="_blank">
                                            <p><?php echo $mess['locality']['type'] . ' ' . $mess['locality']['name']; ?></p>
                                        </a>

                                        <p><?php echo $mess['locality']['address'] ?>
											<?php echo $mess['locality']['zipcode'] . ' ' . $mess['locality']['city'] ?></p>

                                        <a href="http://egliseinfo.catholique.fr/communaute/<?php echo $mess['communityId'] ?>"
                                           target="_blank">
                                            <p><?php echo $mess['locality']['sectorType'] . ' de ' . $mess['locality']['sector']; ?></p>
                                        </a>


                                    </div>
                                </div>
								<?php
								$i ++;

							}
						}


								echo $this->messesinfo_promote(); ?>

                                </div>

								<?php


					}
				}

			} else {
				echo '<p>' . __( 'You must specified a number of results.', 'messesinfo' ) . '</p>';

			}

			echo $args['after_widget'];
		}
	}


	public static function messesinfo_promote() {
		$promote = get_option( 'thfo_ads' );
		if ( $promote === '1' ) {
			return '<p class="promote">' . __( 'Find all Mass hours on: ', 'messesinfo' ) . ' <a	href="http://egliseinfo.catholique.fr/">egliseinfo.catholique.fr</a></p>';
		}
	}


	public function form( $instance ) {
		$title   = isset( $instance['title'] ) ? $instance['title'] : '';
		$id      = isset( $instance['id'] ) ? $instance['id'] : '';
		$nb      = isset( $instance['nb'] ) ? $instance['nb'] : '';
		$promote = isset( $instance['promote'] ) ? $instance['promote'] : '';

		?>
        <p>
            <label
                    for="<?php echo $this->get_field_name( 'title' ); ?>"> <?php _e( 'Title:', 'messesinfo' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>"
                   name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>"/>

            <label
                    for="<?php echo $this->get_field_name( 'id' ); ?>"> <?php _e( 'Your localityId:', 'messesinfo' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'city' ); ?>"
                   name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo $id; ?>"/>

            <label
                    for="<?php echo $this->get_field_name( 'nb' ); ?>"> <?php _e( 'Result number:', 'messesinfo' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'nb' ); ?>"
                   name="<?php echo $this->get_field_name( 'nb' ); ?>" type="number" value="<?php echo $nb; ?>"/>

        </p>

		<?php add_option( 'thfo-messeinfo-city', $id );
	}

}