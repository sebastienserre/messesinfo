<?php


namespace MESSESINFO\API;

use function json_decode;
use function ob_get_clean;
use function ob_start;
use function rawurlencode;
use function set_transient;
use function sizeof;
use function var_dump;
use function wp_remote_get;
use function wp_remote_retrieve_body;
use function wp_remote_retrieve_response_code;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

/**
 * Class Messesinfo
 * @package MESSESINFO\API
 */
class Messesinfo {

	public function __construct() {

	}

	/**
	 * @param $search string
	 *
	 * @return false|string|void
	 */
	public static function get_localityId( $search ) {
		$url         = rawurlencode( $search );
		$response    = wp_remote_get( "http://www.messes.info/api/v2/annuaire/$url?userkey=test&format=json" );
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $status_code ) {

			$body = json_decode( wp_remote_retrieve_body( $response ) );
			switch ( sizeof( $body ) ) {
				case 0:
					$localityId = 'Theres no results';
					break;
				default:
				case sizeof( $body ) > 1 :
					ob_start();
					?>
                    <p><?php _e( 'Here your results:' ) ?></p>
                    <ul>
						<?php
						foreach ( $body as $b ) {
							?>
                            <li><?php echo $b->id; ?></li>
							<?php
						}
						?>
                    </ul>
					<?php
					$localityId = ob_get_clean();
			}

		} else {
			$localityId = __( 'We are sorry, no results match your criterias' );
		}

		return $localityId;

	}

	/**
	 * @param $localityId string Retrieve your LocalityId in Messsesinfo settings tab Tool
	 *
	 * @return  $mass object
	 */
	public static function get_mass_data( $localityId ) {
	    $localityId = rawurlencode( $localityId );
		$url = "http://www.messes.info/api/v2/lieu/$localityId?userkey=test&format=json";

		$response    = wp_remote_get( $url );
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $status_code ) {
			$mass = json_decode( wp_remote_retrieve_body( $response ) );
		}

		return $mass;

	}

	public static function get_locality_info( $localityId ){
		$localityId = rawurlencode( $localityId );
		$url = "http://www.messes.info/api/v2/lieu-info/$localityId?userkey=test&format=json";

		$response = wp_remote_get( $url );
		$status_code = wp_remote_retrieve_response_code( $response );
		if ( 200 === $status_code ) {
			$locality = json_decode( wp_remote_retrieve_body( $response ) );
		}

		return $locality;
    }

	public static function promote() {
		$promote = get_option( 'thfo_ads' );
		if ( $promote === '1' ) {
			return '<p class="promote">' . __( 'Find all Mass hours on: ', 'messesinfo' ) . ' <a	href="http://egliseinfo.catholique.fr/">egliseinfo.catholique.fr</a></p>';
		}
	}
}

new Messesinfo();
