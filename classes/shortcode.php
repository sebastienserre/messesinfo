<?php

use MESSESINFO\API\Messesinfo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

class thfo_messeinfo_shortcode {
	function __construct() {
		add_shortcode( 'messesinfo', array( $this, 'messesinfo_shortcode' ) );
		add_shortcode( 'messesinfo_search', array( $this, 'messesinfo_shortcode_search' ) );
	}

	public function messesinfo_shortcode( $atts ) {

		shortcode_atts(
			array(
				'data-localityId'         => '78/plaisir/saint-pierre',
				'data-displayDetails'     => 'false', //true
				'data-display'            => 'TREE', // Si data-displayDetails == true
				'data-open-in-egliseinfo' => 'true',
				'data-region'             => 'fr', // param obligatoire
				'several-widget'          => 'false', // add JS
			),
			$atts,
			'messesinfo',
		);

		if ( 'false' === $atts['several-widget'] ){
			unset( $atts['several-widget'] );
		}
		if ( 'true' === $atts['several-widget'] ) {
			add_action( 'wp_enqueue_scripts', function () {
				wp_enqueue_script( 'messesinfosJS', 'https://messes.info//Widget/Widget.nocache.js', '', '', true );
			} );
			unset( $atts['several-widget'] );
		}

		ob_start();
		?>
		<div class="EgliseInfo-container">
			<div data-egliseinfo="lieu"
			<?php
			foreach ( $atts as $key => $value ){
				echo $key . '="'. $value .'"';
			}
			?>
			>
			</div>
			<script type="text/javascript" language="javascript"
			        src="https://messes.info//Widget/Widget.nocache.js"></script>
			<p>Retrouvez tous les horaires des célébrations sur <a
					href="https://messes.info//lieu/78/plaisir/saint-pierre">www.messes.info</a>
			</p>
		</div>

		<?php
		$shortcode = ob_get_clean();

		return $shortcode;
	}
}