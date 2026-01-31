<?php
/**
 * Bordro (Payroll) Calculator Module.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_Bordro_Params
 *
 * Contains 2026 payroll parameters and constants.
 *
 * @since 1.0.0
 */
class TIKH_Bordro_Params {

	/**
	 * Gross minimum wage for first half of 2026.
	 */
	const ASGARI_UCRET_BRUT_1 = 33030.00;

	/**
	 * Income tax base for minimum wage (first half).
	 */
	const ASGARI_UCRET_GV_MATRAHI_1 = 28075.50;

	/**
	 * Gross minimum wage for second half of 2026.
	 */
	const ASGARI_UCRET_BRUT_2 = 37455.00;

	/**
	 * Income tax base for minimum wage (second half).
	 */
	const ASGARI_UCRET_GV_MATRAHI_2 = 31836.75;

	/**
	 * Employee SGK (Social Security) rate.
	 */
	const SGK_ISCI_ORANI = 0.14;

	/**
	 * Employee unemployment insurance rate.
	 */
	const ISSIZLIK_ISCI_ORANI = 0.01;

	/**
	 * Employer SGK rate (brütten nete için %20,5).
	 */
	const SGK_ISVEREN_ORANI = 0.205;

	/**
	 * Employer SGK rate for net to gross calculation (%21,75).
	 */
	const SGK_ISVEREN_ORANI_NETTEN_BRUTE = 0.2175;

	/**
	 * Employer unemployment insurance rate.
	 */
	const ISSIZLIK_ISVEREN_ORANI = 0.02;

	/**
	 * Retired employee SGDP (employer) rate.
	 */
	const SGDP_ISVEREN_ORANI = 0.2475;

	/**
	 * Stamp tax rate.
	 */
	const DAMGA_VERGISI_ORANI = 0.00759;

	/**
	 * Stamp tax exemption amount.
	 */
	const DAMGA_ISTISNASI = 250.70;

	/**
	 * SGK ceiling (maximum base).
	 */
	const SGK_TAVAN = 330300.00;

	/**
	 * Get income tax brackets for 2026.
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of tax brackets with limit and rate.
	 */
	public static function get_vergi_dilimleri() {
		return array(
			array(
				'limit' => 190000,
				'oran'  => 0.15,
			),
			array(
				'limit' => 400000,
				'oran'  => 0.20,
			),
			array(
				'limit' => 1500000,
				'oran'  => 0.27,
			),
			array(
				'limit' => 5300000,
				'oran'  => 0.35,
			),
			array(
				'limit' => PHP_FLOAT_MAX,
				'oran'  => 0.40,
			),
		);
	}

	/**
	 * Get minimum wage income tax base for a specific month.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ay Month number (1-12).
	 *
	 * @return float Income tax base amount.
	 */
	public static function get_asgari_ucret_gv_matrahi( $ay ) {
		return ( $ay <= 6 ) ? self::ASGARI_UCRET_GV_MATRAHI_1 : self::ASGARI_UCRET_GV_MATRAHI_2;
	}

	/**
	 * Get cumulative minimum wage income tax base up to a specific month.
	 *
	 * @since 1.0.0
	 *
	 * @param int $ay Month number (1-12).
	 *
	 * @return float Cumulative income tax base.
	 */
	public static function get_kumulatif_asgari_ucret_gv_matrahi( $ay ) {
		$toplam = 0;
		for ( $i = 1; $i <= $ay; $i++ ) {
			$toplam += self::get_asgari_ucret_gv_matrahi( $i );
		}
		return $toplam;
	}
}

/**
 * Class TIKH_Bordro_Engine
 *
 * Payroll calculation engine.
 *
 * @since 1.0.0
 */
class TIKH_Bordro_Engine {

	/**
	 * Whether the employee is retired.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    bool
	 */
	private $emekli_mi;

	/**
	 * Treasury support rate (hazine desteği).
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    float
	 */
	private $hazine_destegi;

	/**
	 * Cumulative income tax base.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    float
	 */
	private $kumulatif_gv_matrahi = 0;

	/**
	 * Calculation type (brut_net or net_brut).
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string
	 */
	private $hesap_turu = 'brut_net';

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 *
	 * @param bool   $emekli_mi      Whether employee is retired.
	 * @param float  $hazine_destegi Treasury support rate (0, 0.02, or 0.05).
	 * @param string $hesap_turu     Calculation type ('brut_net' or 'net_brut').
	 */
	public function __construct( $emekli_mi = false, $hazine_destegi = 0.05, $hesap_turu = 'brut_net' ) {
		$this->emekli_mi      = $emekli_mi;
		$this->hazine_destegi = $emekli_mi ? 0 : $hazine_destegi;
		$this->hesap_turu     = $hesap_turu;
	}

	/**
	 * Reset cumulative values.
	 *
	 * @since 1.0.0
	 */
	public function reset_kumulatif() {
		$this->kumulatif_gv_matrahi = 0;
	}

	/**
	 * Calculate cumulative income tax.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @param float $kumulatif_matrah Cumulative tax base.
	 *
	 * @return float Calculated tax amount.
	 */
	private function hesapla_kumulatif_vergi( $kumulatif_matrah ) {
		if ( $kumulatif_matrah <= 0 ) {
			return 0;
		}

		$dilimler     = TIKH_Bordro_Params::get_vergi_dilimleri();
		$vergi        = 0;
		$onceki_limit = 0;

		foreach ( $dilimler as $dilim ) {
			if ( $kumulatif_matrah <= $dilim['limit'] ) {
				$vergi += ( $kumulatif_matrah - $onceki_limit ) * $dilim['oran'];
				break;
			} else {
				$vergi       += ( $dilim['limit'] - $onceki_limit ) * $dilim['oran'];
				$onceki_limit = $dilim['limit'];
			}
		}

		return $vergi;
	}

	/**
	 * Calculate income tax exemption for minimum wage.
	 *
	 * @since  1.0.0
	 * @access private
	 *
	 * @param int $ay Month number.
	 *
	 * @return float Exemption amount.
	 */
	private function hesapla_gv_istisnasi( $ay ) {
		$kumulatif_asgari_matrah       = TIKH_Bordro_Params::get_kumulatif_asgari_ucret_gv_matrahi( $ay );
		$onceki_kumulatif_asgari_matrah = TIKH_Bordro_Params::get_kumulatif_asgari_ucret_gv_matrahi( $ay - 1 );

		$toplam_asgari_vergi = $this->hesapla_kumulatif_vergi( $kumulatif_asgari_matrah );
		$onceki_asgari_vergi = $this->hesapla_kumulatif_vergi( $onceki_kumulatif_asgari_matrah );

		return $toplam_asgari_vergi - $onceki_asgari_vergi;
	}

	/**
	 * Calculate gross to net salary.
	 *
	 * @since 1.0.0
	 *
	 * @param float $brut  Gross salary.
	 * @param int   $ay_no Month number (1-12).
	 *
	 * @return array Calculation results.
	 */
	public function hesapla_brutten_nete( $brut, $ay_no ) {
		// SGK calculations.
		$sgk_matrahi   = min( $brut, TIKH_Bordro_Params::SGK_TAVAN );
		$sgk_isci      = round( $sgk_matrahi * TIKH_Bordro_Params::SGK_ISCI_ORANI, 2 );
		$issizlik_isci = $this->emekli_mi ? 0 : round( $sgk_matrahi * TIKH_Bordro_Params::ISSIZLIK_ISCI_ORANI, 2 );

		// Income tax calculations.
		$aylik_gv_matrahi = $brut - $sgk_isci - $issizlik_isci;
		$onceki_kumulatif = $this->kumulatif_gv_matrahi;
		$yeni_kumulatif   = $onceki_kumulatif + $aylik_gv_matrahi;

		$toplam_vergi     = $this->hesapla_kumulatif_vergi( $yeni_kumulatif );
		$onceki_vergi     = $this->hesapla_kumulatif_vergi( $onceki_kumulatif );
		$brut_gelir_vergisi = round( $toplam_vergi - $onceki_vergi, 2 );

		$gv_istisnasi     = round( $this->hesapla_gv_istisnasi( $ay_no ), 2 );
		$net_gelir_vergisi = max( 0, round( $brut_gelir_vergisi - $gv_istisnasi, 2 ) );

		// Update cumulative.
		$this->kumulatif_gv_matrahi = $yeni_kumulatif;

		// Stamp tax calculations.
		$brut_damga_vergisi = round( $brut * TIKH_Bordro_Params::DAMGA_VERGISI_ORANI, 2 );
		$damga_istisnasi    = TIKH_Bordro_Params::DAMGA_ISTISNASI;
		$net_damga_vergisi  = max( 0, round( $brut_damga_vergisi - $damga_istisnasi, 2 ) );

		// Net salary.
		$net = round( $brut - $sgk_isci - $issizlik_isci - $net_gelir_vergisi - $net_damga_vergisi, 2 );

		// Employer costs.
		if ( $this->emekli_mi ) {
			$sgk_isveren      = round( $sgk_matrahi * TIKH_Bordro_Params::SGDP_ISVEREN_ORANI, 2 );
			$issizlik_isveren = 0;
		} else {
			// Use different SGK employer rate based on calculation type.
			if ( 'net_brut' === $this->hesap_turu ) {
				// Netten brüte: %21,75
				$sgk_isveren_orani = TIKH_Bordro_Params::SGK_ISVEREN_ORANI_NETTEN_BRUTE - $this->hazine_destegi;
			} else {
				// Brütten nete: %20,5
				$sgk_isveren_orani = TIKH_Bordro_Params::SGK_ISVEREN_ORANI - $this->hazine_destegi;
			}
			$sgk_isveren       = round( $sgk_matrahi * $sgk_isveren_orani, 2 );
			$issizlik_isveren  = round( $sgk_matrahi * TIKH_Bordro_Params::ISSIZLIK_ISVEREN_ORANI, 2 );
		}

		$toplam_isveren_maliyeti = round( $brut + $sgk_isveren + $issizlik_isveren, 2 );

		return array(
			'brut'                    => $brut,
			'sgk_isci'                => $sgk_isci,
			'issizlik_isci'           => $issizlik_isci,
			'gv_matrahi'              => round( $aylik_gv_matrahi, 2 ),
			'kumulatif_gv_matrahi'    => round( $yeni_kumulatif, 2 ),
			'brut_gelir_vergisi'      => $brut_gelir_vergisi,
			'gv_istisnasi'            => $gv_istisnasi,
			'net_gelir_vergisi'       => $net_gelir_vergisi,
			'brut_damga_vergisi'      => $brut_damga_vergisi,
			'damga_istisnasi'         => $damga_istisnasi,
			'net_damga_vergisi'       => $net_damga_vergisi,
			'net'                     => $net,
			'sgk_isveren'             => $sgk_isveren,
			'issizlik_isveren'        => $issizlik_isveren,
			'toplam_isveren_maliyeti' => $toplam_isveren_maliyeti,
		);
	}

	/**
	 * Calculate net to gross salary.
	 *
	 * @since 1.0.0
	 *
	 * @param float $hedef_net Target net salary.
	 * @param int   $ay_no     Month number (1-12).
	 *
	 * @return array Calculation results.
	 */
	public function hesapla_netten_brute( $hedef_net, $ay_no ) {
		$baslangic_kumulatif = $this->kumulatif_gv_matrahi;
		$brut_tahmin         = $hedef_net * 1.50;

		for ( $i = 0; $i < 200; $i++ ) {
			$this->kumulatif_gv_matrahi = $baslangic_kumulatif;
			$sonuc                      = $this->hesapla_brutten_nete( $brut_tahmin, $ay_no );
			$fark                       = $hedef_net - $sonuc['net'];

			if ( abs( $fark ) < 0.01 ) {
				$this->kumulatif_gv_matrahi = $baslangic_kumulatif;
				return $this->hesapla_brutten_nete( $brut_tahmin, $ay_no );
			}

			$brut_tahmin += $fark * 1.35;

			if ( $brut_tahmin < 0 ) {
				$brut_tahmin = $hedef_net * 1.2;
			}
		}

		$this->kumulatif_gv_matrahi = $baslangic_kumulatif;
		return $this->hesapla_brutten_nete( $brut_tahmin, $ay_no );
	}

	/**
	 * Calculate yearly gross to net.
	 *
	 * @since 1.0.0
	 *
	 * @param float $aylik_brut Monthly gross salary.
	 *
	 * @return array Array with 'aylik' (monthly) and 'toplam' (totals).
	 */
	public function hesapla_yillik_brutten_nete( $aylik_brut ) {
		$this->reset_kumulatif();

		$aylar    = tikh_get_month_names();
		$sonuclar = array();
		$toplamlar = array(
			'brut'                    => 0,
			'sgk_isci'                => 0,
			'issizlik_isci'           => 0,
			'net_gelir_vergisi'       => 0,
			'net_damga_vergisi'       => 0,
			'net'                     => 0,
			'sgk_isveren'             => 0,
			'issizlik_isveren'        => 0,
			'toplam_isveren_maliyeti' => 0,
			'gv_istisnasi'            => 0,
			'damga_istisnasi'         => 0,
		);

		foreach ( $aylar as $ay_no => $ay_adi ) {
			$sonuc          = $this->hesapla_brutten_nete( $aylik_brut, $ay_no );
			$sonuc['ay']    = $ay_adi;
			$sonuc['ay_no'] = $ay_no;
			$sonuclar[]     = $sonuc;

			foreach ( $toplamlar as $key => $val ) {
				if ( isset( $sonuc[ $key ] ) ) {
					$toplamlar[ $key ] += $sonuc[ $key ];
				}
			}
		}

		return array(
			'aylik'  => $sonuclar,
			'toplam' => $toplamlar,
		);
	}

	/**
	 * Calculate yearly net to gross.
	 *
	 * @since 1.0.0
	 *
	 * @param float $hedef_net Target monthly net salary.
	 *
	 * @return array Array with 'aylik' (monthly) and 'toplam' (totals).
	 */
	public function hesapla_yillik_netten_brute( $hedef_net ) {
		$this->reset_kumulatif();

		$aylar    = tikh_get_month_names();
		$sonuclar = array();
		$toplamlar = array(
			'brut'                    => 0,
			'sgk_isci'                => 0,
			'issizlik_isci'           => 0,
			'net_gelir_vergisi'       => 0,
			'net_damga_vergisi'       => 0,
			'net'                     => 0,
			'sgk_isveren'             => 0,
			'issizlik_isveren'        => 0,
			'toplam_isveren_maliyeti' => 0,
			'gv_istisnasi'            => 0,
			'damga_istisnasi'         => 0,
		);

		foreach ( $aylar as $ay_no => $ay_adi ) {
			$sonuc          = $this->hesapla_netten_brute( $hedef_net, $ay_no );
			$sonuc['ay']    = $ay_adi;
			$sonuc['ay_no'] = $ay_no;
			$sonuclar[]     = $sonuc;

			foreach ( $toplamlar as $key => $val ) {
				if ( isset( $sonuc[ $key ] ) ) {
					$toplamlar[ $key ] += $sonuc[ $key ];
				}
			}
		}

		return array(
			'aylik'  => $sonuclar,
			'toplam' => $toplamlar,
		);
	}
}

/**
 * Class TIKH_Bordro
 *
 * Main Bordro shortcode handler.
 *
 * @since 1.0.0
 */
class TIKH_Bordro {

	/**
	 * Register the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcode() {
		add_shortcode( 'maas_hesapla', array( $this, 'render_shortcode' ) );
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
		$hesap_yapildi  = false;
		$sonuclar       = null;
		$hata_mesaji    = '';
		$hesap_turu     = '';
		$girilen_deger  = '';
		$emekli_mi      = false;
		$hazine_destegi = 0.05;

		// Process form submission.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['tikh_bordro_nonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tikh_bordro_nonce'] ) ), 'tikh_bordro_action' ) ) {
				$hesap_turu     = isset( $_POST['hesap_turu'] ) ? sanitize_text_field( wp_unslash( $_POST['hesap_turu'] ) ) : 'brut_net';
				$girilen_deger  = isset( $_POST['maas_tutari'] ) ? tikh_sanitize_currency( wp_unslash( $_POST['maas_tutari'] ) ) : 0;
				$emekli_mi      = isset( $_POST['emekli_calisan'] ) && '1' === $_POST['emekli_calisan'];
				$hazine_destegi = isset( $_POST['hazine_destegi'] ) ? floatval( $_POST['hazine_destegi'] ) : 0.05;

				if ( $girilen_deger <= 0 ) {
					$hata_mesaji = __( 'Lütfen geçerli bir maaş tutarı giriniz.', 'turkiye-ik-hesaplama' );
				} elseif ( $girilen_deger < TIKH_Bordro_Params::ASGARI_UCRET_BRUT_1 && 'brut_net' === $hesap_turu ) {
					$hata_mesaji = __( 'Brüt maaş asgari ücretin altında olamaz.', 'turkiye-ik-hesaplama' );
				} else {
					$hesaplayici = new TIKH_Bordro_Engine( $emekli_mi, $hazine_destegi, $hesap_turu );

					if ( 'brut_net' === $hesap_turu ) {
						$sonuclar               = $hesaplayici->hesapla_yillik_brutten_nete( $girilen_deger );
						$sonuclar['hesap_turu'] = __( 'Brütten Nete', 'turkiye-ik-hesaplama' );
					} else {
						$sonuclar               = $hesaplayici->hesapla_yillik_netten_brute( $girilen_deger );
						$sonuclar['hesap_turu'] = __( 'Netten Brüte', 'turkiye-ik-hesaplama' );
					}

					$sonuclar['girilen']        = $girilen_deger;
					$sonuclar['emekli']         = $emekli_mi;
					$sonuclar['hazine_destegi'] = $hazine_destegi;
					$hesap_yapildi              = true;
				}
			}
		}

		// Render template.
		return tikh_get_template(
			'bordro-form',
			array(
				'hesap_yapildi'  => $hesap_yapildi,
				'sonuclar'       => $sonuclar,
				'hata_mesaji'    => $hata_mesaji,
				'hesap_turu'     => $hesap_turu,
				'girilen_deger'  => $girilen_deger,
				'emekli_mi'      => $emekli_mi,
				'hazine_destegi' => $hazine_destegi,
			)
		);
	}
}
