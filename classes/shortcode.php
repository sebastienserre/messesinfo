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
				switch ( $mess->timeType ){
                    case 'SUNDAYMASS':
	                    $type = __( 'Weekly Mass', 'messesinfo' );
	                    break;
                    case 'WEEKMASS':
	                    $type = __( 'Daily Mass', 'messesinfo' );
	                    break;

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
		$shortcode .= Messesinfo::promote();

		return $shortcode;
	}
}