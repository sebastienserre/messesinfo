<?php


use MESSESINFO\API\Messesinfo;

class thfo_messeinfo_shortcode {
	function __construct() {
		add_shortcode( 'messesinfo', array( $this, 'messesinfo_shortcode' ) );
		add_shortcode( 'messesinfo_search', array( $this, 'messesinfo_shortcode_search' ) );
	}

	public function messesinfo_shortcode( $atts ) {

		shortcode_atts(
			array(
				'search',
				'localityId' => '78/plaisir/saint-pierre',
				'result'     => '5',
			),
			$atts
		);

		$localityId     = esc_html( $atts['localityid'] );
		$atts['result'] = intval( $atts['result'] );

		$mass = Messesinfo::get_mass_data( $localityId );

		$i = 1;
		$b = 1;

		ob_start();
			foreach ( $mass->celebrationsTime as $mess ) {
				if ( $i <= $atts['result'] ) {
					$newdate = date_timestamp_get( date_create( $mess->date ) );
					if ( $mess->timeType === 'SUNDAYMASS' ) {
						$type = __( 'Weekly Mass', 'messesinfo' );
					}
					$locality = Messesinfo::get_locality_info( $localityId );
					?>
                    <div class="messeinfo messeinfo-<?php echo $i ?>">
                        <div class="messesinfo-left">
                            <div class="mass-infos mass-infos-<?php echo $i ?>">
                                <p class="mass-date mass-date-<?php echo $i ?>">
                                    <strong><?php echo date_i18n( 'l d F Y', $newdate ) . ' - ' . $mess->time ?></strong>
									<?php echo $type ?>
                                </p>
                                <div class="messesinfo-adress">
                                    <p><?php echo $locality->type . ' ' . $locality->commonName; ?> </p>
                                    <p><?php echo $locality->address->street; ?> </p>
                                    <p><?php echo $locality->address->zipCode . ' ' . $locality->address->city; ?></p>
                                </div>

                                <p><a href="http://egliseinfo.catholique.fr/lieu/<?php echo $mess->localityId ?>"
                                      target="_blank">
										<?php _e( 'Link to the church', 'messesinfo' ); ?>
                                    </a> -
                                    <a href="http://egliseinfo.catholique.fr/communaute/<?php echo $mess->communityId ?>"
                                       target="_blank">
										<?php _e( 'Link to the community', 'messesinfo' ); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
						<?php
						if ( ! empty( $locality->picture ) ) { ?>
                            <div class="messesinfo-right">
                                <img src="<?php echo $locality->picture ?>">
                            </div>
							<?php
						}
						?>
                    </div>
                    <div class="clear"></div>
					<?php
					$i ++;
				}
			}


				$shortcode = ob_get_clean();
				$shortcode .= messesinfos_widget::messesinfo_promote();

				return $shortcode;

	}

	public function messesinfo_shortcode_search( $atts ) {

		$a              = shortcode_atts( array( 'result' => '5' ), $atts );
		$atts['result'] = intval( $atts['result'] );

		/**
		 * Add a form to search input
		 */
		$shortcode = '<form action="#" method="post">';
		$shortcode .= '<input type="text" name="messesinfo_search_field" >';
		$shortcode .= '<input type="submit" name="messesinfo_search_submit" >';
		$shortcode .= '</form>';

		$search_field = sanitize_text_field( $_POST['messesinfo_search_field'] );
		$url          = file_get_contents( 'http://www.messes.info/api/v2/horaires/' . $search_field . '?userkey=messesinfos&format=json' );

		$mass = json_decode( $url, true );

		if ( isset( $mass['errorMessage'] ) ) {
			echo '<p>' . $mass['errorMessage'] . '</p>';
		} else {
			foreach ( $mass as $m ) {
				if ( is_array( $m ) ) {
					$i = 1;
					foreach ( $m as $mess ) {
						//var_dump($mess);
						if ( $i <= $atts['result'] ) {
							//		for ( $i = 0; $i <= $atts['result']; $i++ ) {
							$newdate   = date_timestamp_get( date_create( $mess['date'] ) );
							$shortcode .= '<div class="messeinfo messeinfo-' . $i . '">
                                <div class="mass-infos mass-infos-' . $i . '">
                                    <p class="mass-date mass-date-' . $i . '">
                                        <strong>' . date_i18n( 'l d F Y', $newdate ) . ' - ' . $mess['time'] . '</strong>
                                    </p>

								    ';
							if ( $mess['timeType'] === 'SUNDAYMASS' ) {
								$type      = __( 'Weekly Mass', 'messesinfo' );
								$shortcode .= '<p>' . $type . '</p>';
							};

							$shortcode .= '<a href="http://egliseinfo.catholique.fr/lieu/' . $mess['locality']['id'] . '"
                                       target="_blank">
                                        <p>' . $mess['locality']['type'] . ' ' . $mess['locality']['name'] . '</p>
                                    </a>

                                    <p>' . $mess['locality']['address'] . $mess['locality']['zipcode'] . ' ' . $mess['locality']['city'] . '</p>

                                    <a href="http://egliseinfo.catholique.fr/communaute/' . $mess['communityId'] . '"
                                       target="_blank">
                                        <p>' . $mess['locality']['sectorType'] . ' de ' . $mess['locality']['sector'] . '</p>
                                    </a>
                                    <div class="acf-map">
	<div class="marker" data-lat="' . $mess['locality']['latitude'] . '" data-lng="' . $mess['locality']['longitude'] . '"></div>
</div>

                                </div>

                            </div>
                            <div class="clear"></div>';
						}

						$i ++;
					}
				}
			}

		}

		return $shortcode;
	}

	public function messesinfos_display_results( $mass ) {
		if ( isset( $mass['errorMessage'] ) ) {
			echo '<p>' . $mass['errorMessage'] . '</p>';
		} else {
			foreach ( $mass as $m ) {
				if ( is_array( $m ) ) {
					$i = 1;
					foreach ( $m as $mess ) {
						var_dump( $mess );
						if ( $i <= $atts['result'] ) {
							//		for ( $i = 0; $i <= $atts['result']; $i++ ) {
							$newdate   = date_timestamp_get( date_create( $mess['date'] ) );
							$shortcode .= '<div class="messeinfo messeinfo-' . $i . '">
                                <div class="mass-infos mass-infos-' . $i . '">
                                    <p class="mass-date mass-date-' . $i . '">
                                        <strong>' . date_i18n( 'l d F Y', $newdate ) . ' - ' . $mess['time'] . '</strong>
                                    </p>

								    ';
							if ( $mess['timeType'] === 'SUNDAYMASS' ) {
								$type      = __( 'Weekly Mass', 'messesinfo' );
								$shortcode .= '<p>' . $type . '</p>';
							};

							$shortcode .= '<a href="http://egliseinfo.catholique.fr/lieu/' . $mess['locality']['id'] . '"
                                       target="_blank">
                                        <p>' . $mess['locality']['type'] . ' ' . $mess['locality']['name'] . '</p>
                                    </a>

                                    <p>' . $mess['locality']['address'] . $mess['locality']['zipcode'] . ' ' . $mess['locality']['city'] . '</p>

                                    <a href="http://egliseinfo.catholique.fr/communaute/' . $mess['communityId'] . '"
                                       target="_blank">
                                        <p>' . $mess['locality']['sectorType'] . ' de ' . $mess['locality']['sector'] . '</p>
                                    </a>

                                </div>

                            </div>
                            <div class="clear"></div>';
						}

						$i ++;
					}
				}
			}

		}

		return $shortcode;

	}

}