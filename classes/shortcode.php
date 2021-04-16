<?php

use MESSESINFO\API\Messesinfo;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly.

class thfo_messeinfo_shortcode {
	function __construct() {
		add_shortcode( 'messesinfo', array( $this, 'messesinfo_shortcode' ) );
		add_shortcode( 'messesinfo_no_option', array( $this, 'messesinfo_no_option' ) );
	}

	public function messesinfo_no_option( $atts ){
		shortcode_atts(
			array(
				'code_to_insert'         => 'carmaux',
			),
			$atts,
			'messesinfo_no_option' );

		return $atts['code_to_insert'];

    }

	public function messesinfo_shortcode( $atts ) {

		shortcode_atts(
			array(
				'data-localityId'         => 'carmaux',
				'data-displayDetails'     => 'false', //true
				'data-display'            => 'TREE', // Si data-displayDetails == true
				'data-open-in-egliseinfo' => 'true',
				'data-region'             => 'fr', // param obligatoire
				'several-widget'          => 'false', // add JS
				'data-egliseinfo'         => 'horaires' // horaires/lieu
			),
			$atts,
			'messesinfo' );

		if ( 'false' === $atts['several-widget'] ) {
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
			<?php
			switch ( $atts['data-egliseinfo'] ) {
				case 'horaires':
					?>

                    <div data-egliseinfo="<?php echo $atts['data-egliseinfo'] ?>"
                         data-search="<?php echo $atts['data-localityid'] ?>’"
                         data-open-in-egliseinfo="<?php echo $atts['data-egliseinfo'] ?>">
                    </div>

					<?php
					break;
				case 'lieu':
					?>
                    <div data-egliseinfo="<?php echo $atts['data-egliseinfo'] ?>"
                         data-localityId="<?php echo $atts['data-localityid'] ?>"
                         data-open-in-egliseinfo="<?php echo $atts['data-egliseinfo'] ?>">
                    </div>

					<?php
					break;
				case 'communaute':
					?>
                    <div data-egliseinfo="<?php echo $atts['data-egliseinfo'] ?>"
                         data-communityId="<?php echo $atts['data-localityid'] ?>"
                         data-open-in-egliseinfo="<?php echo $atts['data-egliseinfo'] ?>">
                    </div>
				<?php
			}
			?>
            <script type="text/javascript" language="javascript"
                    src="https://messes.info//Widget/Widget.nocache.js">

            </script>
            <p>Retrouvez tous les horaires des célébrations sur <a
                        href="https://messes.info/<?php echo $atts['data-egliseinfo'] ?>/<?php echo $atts['data-localityid'] ?>">www.messes.info</a>
            </p>
        </div>
		<?php
		$shortcode = ob_get_clean();

		return $shortcode;
	}
}