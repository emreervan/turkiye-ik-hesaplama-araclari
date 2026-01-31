<?php
/**
 * Gelir Vergisi (Income Tax) Calculator Module.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_Gelir_Vergisi_Params
 *
 * Contains income tax parameters for 2025 and 2026.
 *
 * @since 1.0.0
 */
class TIKH_Gelir_Vergisi_Params {

	/**
	 * Get tax brackets for a specific year and taxpayer type.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $yil Year (2025 or 2026).
	 * @param string $tip Taxpayer type ('ucretli' or 'diger').
	 *
	 * @return array Array of tax brackets.
	 */
	public static function get_dilimler( $yil, $tip ) {
		if ( 2026 === $yil ) {
			if ( 'ucretli' === $tip ) {
				return array(
					array(
						'alt'    => 0,
						'ust'    => 190000,
						'oran'   => 0.15,
						'onceki' => 0,
					),
					array(
						'alt'    => 190000,
						'ust'    => 400000,
						'oran'   => 0.20,
						'onceki' => 28500,
					),
					array(
						'alt'    => 400000,
						'ust'    => 1500000,
						'oran'   => 0.27,
						'onceki' => 70500,
					),
					array(
						'alt'    => 1500000,
						'ust'    => 5300000,
						'oran'   => 0.35,
						'onceki' => 367500,
					),
					array(
						'alt'    => 5300000,
						'ust'    => PHP_FLOAT_MAX,
						'oran'   => 0.40,
						'onceki' => 1697500,
					),
				);
			} else {
				return array(
					array(
						'alt'    => 0,
						'ust'    => 190000,
						'oran'   => 0.15,
						'onceki' => 0,
					),
					array(
						'alt'    => 190000,
						'ust'    => 400000,
						'oran'   => 0.20,
						'onceki' => 28500,
					),
					array(
						'alt'    => 400000,
						'ust'    => 1000000,
						'oran'   => 0.27,
						'onceki' => 70500,
					),
					array(
						'alt'    => 1000000,
						'ust'    => 5300000,
						'oran'   => 0.35,
						'onceki' => 232500,
					),
					array(
						'alt'    => 5300000,
						'ust'    => PHP_FLOAT_MAX,
						'oran'   => 0.40,
						'onceki' => 1737500,
					),
				);
			}
		} else {
			// 2025 brackets.
			if ( 'ucretli' === $tip ) {
				return array(
					array(
						'alt'    => 0,
						'ust'    => 158000,
						'oran'   => 0.15,
						'onceki' => 0,
					),
					array(
						'alt'    => 158000,
						'ust'    => 330000,
						'oran'   => 0.20,
						'onceki' => 23700,
					),
					array(
						'alt'    => 330000,
						'ust'    => 1200000,
						'oran'   => 0.27,
						'onceki' => 58100,
					),
					array(
						'alt'    => 1200000,
						'ust'    => 4300000,
						'oran'   => 0.35,
						'onceki' => 293000,
					),
					array(
						'alt'    => 4300000,
						'ust'    => PHP_FLOAT_MAX,
						'oran'   => 0.40,
						'onceki' => 1378000,
					),
				);
			} else {
				return array(
					array(
						'alt'    => 0,
						'ust'    => 158000,
						'oran'   => 0.15,
						'onceki' => 0,
					),
					array(
						'alt'    => 158000,
						'ust'    => 330000,
						'oran'   => 0.20,
						'onceki' => 23700,
					),
					array(
						'alt'    => 330000,
						'ust'    => 800000,
						'oran'   => 0.27,
						'onceki' => 58100,
					),
					array(
						'alt'    => 800000,
						'ust'    => 4300000,
						'oran'   => 0.35,
						'onceki' => 185000,
					),
					array(
						'alt'    => 4300000,
						'ust'    => PHP_FLOAT_MAX,
						'oran'   => 0.40,
						'onceki' => 1410000,
					),
				);
			}
		}
	}

	/**
	 * Get minimum wage tax exemption for a year.
	 *
	 * @since 1.0.0
	 *
	 * @param int $yil Year.
	 *
	 * @return float Exemption amount.
	 */
	public static function get_asgari_ucret_istisnasi( $yil ) {
		return ( 2026 === $yil ) ? 50535.96 : 42020.16;
	}
}

/**
 * Class TIKH_Gelir_Vergisi
 *
 * Income tax calculator.
 *
 * @since 1.0.0
 */
class TIKH_Gelir_Vergisi {

	/**
	 * Register the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcode() {
		add_shortcode( 'gelir_vergisi_hesapla', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Calculate income tax.
	 *
	 * @since 1.0.0
	 *
	 * @param float  $matrah  Tax base amount.
	 * @param int    $yil     Year.
	 * @param string $tip     Taxpayer type.
	 * @param bool   $istisna Whether to apply minimum wage exemption.
	 *
	 * @return array Calculation results.
	 */
	public function hesapla( $matrah, $yil, $tip, $istisna = false ) {
		$dilimler     = TIKH_Gelir_Vergisi_Params::get_dilimler( $yil, $tip );
		$toplam_vergi = 0;
		$dilim_detay  = array();
		$aktif_dilim  = null;

		foreach ( $dilimler as $i => $d ) {
			$dilim_no = $i + 1;

			if ( $matrah <= $d['alt'] ) {
				$dilim_detay[] = array(
					'no'     => $dilim_no,
					'alt'    => $d['alt'],
					'ust'    => $d['ust'],
					'oran'   => $d['oran'],
					'matrah' => 0,
					'vergi'  => 0,
					'aktif'  => false,
					'mevcut' => false,
				);
			} elseif ( $matrah > $d['ust'] ) {
				$dilim_matrah  = $d['ust'] - $d['alt'];
				$dilim_vergi   = $dilim_matrah * $d['oran'];
				$toplam_vergi += $dilim_vergi;

				$dilim_detay[] = array(
					'no'     => $dilim_no,
					'alt'    => $d['alt'],
					'ust'    => $d['ust'],
					'oran'   => $d['oran'],
					'matrah' => $dilim_matrah,
					'vergi'  => $dilim_vergi,
					'aktif'  => true,
					'mevcut' => false,
				);
			} else {
				$dilim_matrah  = $matrah - $d['alt'];
				$dilim_vergi   = $dilim_matrah * $d['oran'];
				$toplam_vergi += $dilim_vergi;

				$dilim_detay[] = array(
					'no'     => $dilim_no,
					'alt'    => $d['alt'],
					'ust'    => $d['ust'],
					'oran'   => $d['oran'],
					'matrah' => $dilim_matrah,
					'vergi'  => $dilim_vergi,
					'aktif'  => true,
					'mevcut' => true,
				);

				$aktif_dilim = $d;
			}
		}

		// Apply exemption if applicable.
		$istisna_tutar = 0;
		if ( $istisna && 'ucretli' === $tip ) {
			$istisna_tutar = min( $toplam_vergi, TIKH_Gelir_Vergisi_Params::get_asgari_ucret_istisnasi( $yil ) );
		}

		$net_vergi    = max( 0, $toplam_vergi - $istisna_tutar );
		$efektif_oran = $matrah > 0 ? ( $net_vergi / $matrah ) * 100 : 0;

		return array(
			'yil'        => $yil,
			'tip'        => $tip,
			'matrah'     => $matrah,
			'brutVergi'  => $toplam_vergi,
			'istisna'    => $istisna_tutar,
			'netVergi'   => $net_vergi,
			'efektif'    => $efektif_oran,
			'dilimler'   => $dilim_detay,
			'aktifDilim' => $aktif_dilim,
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
		$sonuc   = null;
		$hata    = '';
		$yil     = 2026;
		$tip     = 'ucretli';
		$matrah  = '';
		$istisna = false;

		// Process form submission.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['tikh_gv_nonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tikh_gv_nonce'] ) ), 'tikh_gv_action' ) ) {
				$yil     = isset( $_POST['gvh_yil'] ) ? absint( $_POST['gvh_yil'] ) : 2026;
				$tip     = isset( $_POST['gvh_tip'] ) ? sanitize_text_field( wp_unslash( $_POST['gvh_tip'] ) ) : 'ucretli';
				$matrah  = isset( $_POST['gvh_matrah'] ) ? tikh_sanitize_currency( wp_unslash( $_POST['gvh_matrah'] ) ) : 0;
				$istisna = isset( $_POST['gvh_istisna'] ) && 'ucretli' === $tip;

				if ( $matrah <= 0 ) {
					$hata = __( 'Vergi matrahı sıfırdan büyük olmalıdır.', 'turkiye-ik-hesaplama' );
				} else {
					$sonuc = $this->hesapla( $matrah, $yil, $tip, $istisna );
				}
			}
		}

		// Render template.
		return tikh_get_template(
			'gelir-vergisi-form',
			array(
				'sonuc'   => $sonuc,
				'hata'    => $hata,
				'yil'     => $yil,
				'tip'     => $tip,
				'matrah'  => $matrah,
				'istisna' => $istisna,
			)
		);
	}
}
