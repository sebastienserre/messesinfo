<?php


class thfo_messeinfo_shortcode {
	function __construct() {
		add_shortcode( 'messeinfo', array( $this, 'messeinfo_shortcode' ) );
	}


	public function messeinfo_shortcode( $atts ) {

		$a = shortcode_atts( array( 'cp' => '78370' ), $atts );

		$url  = file_get_contents( "http://egliseinfo.catholique.fr/api/v2/annuaire/" . $a['cp'] . "?userkey=test&format=json" );
		$city = json_decode( $url );
		foreach ( $city as $cities ) {
			$comunity = $cities->communityId;
		}

		$url         = file_get_contents( "http://egliseinfo.catholique.fr/api/v2/horaires/community%3A" . $comunity . "?userkey=test&format=json" );
		$communities = json_decode( $url, true );

		$i = 0;
		//var_dump($communities);
		if ( ! empty( $communities['errorMessage'] ) ) {
			echo $communities['errorMessage'];
		} else {
			foreach ( $communities as $c ) { ?>
				<div class="messeinfo messeinfo-<?php echo $i ?>">
					<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['locality']['picture'] ) ) { ?>
						<img src="<?php echo $communities['listCelebrationTime'][ $i ]['locality']['picture'] ?>"
						     alt="<?php echo $communities['listCelebrationTime'][ $i ]['locality']['type'];
						     echo ' ' . $communities['listCelebrationTime'][ $i ]['locality']['name'] ?>"/>
					<?php } ?>
					<p>
						<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['locality']['type'] ) ) { ?>
							<?php echo $communities['listCelebrationTime'][ $i ]['locality']['type'];
						} ?>

						<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['locality']['name'] ) ) {
							echo $communities['listCelebrationTime'][ $i ]['locality']['name'] ?>
						<?php } ?>
					</p>

					<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['locality']['address'] ) ) { ?>
					<p><?php echo $communities['listCelebrationTime'][ $i ]['locality']['address'];
						} ?>

						<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['locality']['city'] ) ) {
							echo $communities['listCelebrationTime'][ $i ]['locality']['city'];
						} ?></p>
					<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['date'] ) ) {
						$newdate = date_create($communities['listCelebrationTime'][ $i ]['date']);
						$ts = date_timestamp_get($newdate);
						?>
						<p><?php echo date_i18n( 'l d F Y', $ts ); ?></p>
						<p><?php //echo date_i18n($newdate, 'l d F Y'); ?></p>
					<?php } ?>
					<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['time'] ) ) { ?>
						<p><?php echo $communities['listCelebrationTime'][ $i ]['time'] ?></p>
					<?php } ?>
					<?php if ( ! empty( $communities['listCelebrationTime'][ $i ]['celebrationName'] ) ) { ?>
						<p><?php echo $communities['listCelebrationTime'][ $i ]['celebrationName'] ?></p>
					<?php } ?>

				</div>
				<?php $i ++;
			}
			echo '<p>Retrouvez tous les horaires des célébrations sur <a	href="http://egliseinfo.catholique.fr/horaires/' . $a['cp'] . '?limit=1">egliseinfo.catholique.fr</a></p>';

		}
	}


}