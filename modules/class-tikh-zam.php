<?php
/**
 * Zam (Salary Raise) Calculator Module.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_Zam
 *
 * Salary raise calculator.
 *
 * @since 1.0.0
 */
class TIKH_Zam {

	/**
	 * Register the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcode() {
		add_shortcode( 'zam_hesapla', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Calculate raise from percentage.
	 *
	 * @since 1.0.0
	 *
	 * @param float $mevcut_net Current net salary.
	 * @param float $zam_orani  Raise percentage.
	 *
	 * @return array Calculation results.
	 */
	public function hesapla_yuzdeden( $mevcut_net, $zam_orani ) {
		$zam_tutari = $mevcut_net * ( $zam_orani / 100 );
		$yeni_net   = $mevcut_net + $zam_tutari;

		return array(
			'mod'    => 'yuzde',
			'mevcut' => $mevcut_net,
			'oran'   => $zam_orani,
			'tutar'  => $zam_tutari,
			'yeni'   => $yeni_net,
			'kat'    => $yeni_net / $mevcut_net,
		);
	}

	/**
	 * Calculate percentage from new salary.
	 *
	 * @since 1.0.0
	 *
	 * @param float $mevcut_net Current net salary.
	 * @param float $zamli_net  New (raised) net salary.
	 *
	 * @return array Calculation results.
	 */
	public function hesapla_netten( $mevcut_net, $zamli_net ) {
		$zam_tutari = $zamli_net - $mevcut_net;
		$zam_orani  = ( $zam_tutari / $mevcut_net ) * 100;

		return array(
			'mod'   => 'net',
			'mevcut' => $mevcut_net,
			'zamli' => $zamli_net,
			'tutar' => $zam_tutari,
			'oran'  => $zam_orani,
			'kat'   => $zamli_net / $mevcut_net,
		);
	}

	/**
	 * Render the shortcode output.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Shortcode attributes.
	 *
	 * @return string HTML output.
	 */
	public function render_shortcode( $atts ) {
		$sonuc      = null;
		$hata       = '';
		$mod        = 'yuzde';
		$mevcut_net = '';
		$zam_orani  = '';
		$zamli_net  = '';

		// Process form submission.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['tikh_zam_nonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tikh_zam_nonce'] ) ), 'tikh_zam_action' ) ) {
				$mod        = isset( $_POST['zamh_mod'] ) ? sanitize_text_field( wp_unslash( $_POST['zamh_mod'] ) ) : 'yuzde';
				$mevcut_net = isset( $_POST['zamh_mevcut'] ) ? tikh_sanitize_currency( wp_unslash( $_POST['zamh_mevcut'] ) ) : 0;

				if ( $mevcut_net <= 0 ) {
					$hata = __( 'Mevcut net maaş sıfırdan büyük olmalıdır.', 'turkiye-ik-hesaplama' );
				} else {
					if ( 'yuzde' === $mod ) {
						$zam_orani = isset( $_POST['zamh_oran'] ) ? floatval( str_replace( ',', '.', wp_unslash( $_POST['zamh_oran'] ) ) ) : 0;

						if ( $zam_orani <= 0 ) {
							$hata = __( 'Zam oranı sıfırdan büyük olmalıdır.', 'turkiye-ik-hesaplama' );
						} else {
							$sonuc = $this->hesapla_yuzdeden( $mevcut_net, $zam_orani );
						}
					} else {
						$zamli_net = isset( $_POST['zamh_zamli'] ) ? tikh_sanitize_currency( wp_unslash( $_POST['zamh_zamli'] ) ) : 0;

						if ( $zamli_net <= $mevcut_net ) {
							$hata = __( 'Zamlı net, mevcut netten büyük olmalıdır.', 'turkiye-ik-hesaplama' );
						} else {
							$sonuc = $this->hesapla_netten( $mevcut_net, $zamli_net );
						}
					}
				}
			}
		}

		// Render template.
		return tikh_get_template(
			'zam-form',
			array(
				'sonuc'      => $sonuc,
				'hata'       => $hata,
				'mod'        => $mod,
				'mevcut_net' => $mevcut_net,
				'zam_orani'  => $zam_orani,
				'zamli_net'  => $zamli_net,
			)
		);
	}
}
