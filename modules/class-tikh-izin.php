<?php
/**
 * İzin (Annual Leave) Calculator Module.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_Izin
 *
 * Annual leave calculator based on Turkish labor law.
 * - Public sector: 657 DMK (Devlet Memurları Kanunu)
 * - Private sector: 4857 İş Kanunu
 *
 * @since 1.0.0
 */
class TIKH_Izin {

	/**
	 * Leave brackets for public sector (657 DMK).
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of leave brackets.
	 */
	public static function get_kamu_dilimleri() {
		return array(
			array(
				'min_yil' => 0,
				'max_yil' => 1,
				'gun'     => 0,
				'dilim'   => __( '1 yıldan az', 'turkiye-ik-hesaplama' ),
			),
			array(
				'min_yil' => 1,
				'max_yil' => 10,
				'gun'     => 20,
				'dilim'   => __( '1 - 10 yıl', 'turkiye-ik-hesaplama' ),
			),
			array(
				'min_yil' => 10,
				'max_yil' => PHP_INT_MAX,
				'gun'     => 30,
				'dilim'   => __( '10+ yıl', 'turkiye-ik-hesaplama' ),
			),
		);
	}

	/**
	 * Leave brackets for private sector (4857 İş Kanunu).
	 *
	 * @since 1.0.0
	 *
	 * @return array Array of leave brackets.
	 */
	public static function get_ozel_dilimleri() {
		return array(
			array(
				'min_yil' => 0,
				'max_yil' => 1,
				'gun'     => 0,
				'dilim'   => __( '1 yıldan az', 'turkiye-ik-hesaplama' ),
			),
			array(
				'min_yil' => 1,
				'max_yil' => 6,
				'gun'     => 14,
				'dilim'   => __( '1 - 5 yıl', 'turkiye-ik-hesaplama' ),
			),
			array(
				'min_yil' => 6,
				'max_yil' => 15,
				'gun'     => 20,
				'dilim'   => __( '6 - 15 yıl', 'turkiye-ik-hesaplama' ),
			),
			array(
				'min_yil' => 15,
				'max_yil' => PHP_INT_MAX,
				'gun'     => 26,
				'dilim'   => __( '15+ yıl', 'turkiye-ik-hesaplama' ),
			),
		);
	}

	/**
	 * Register the shortcode.
	 *
	 * @since 1.0.0
	 */
	public function register_shortcode() {
		add_shortcode( 'izin_hesapla', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Calculate leave days based on years of service.
	 *
	 * @since 1.0.0
	 *
	 * @param int    $yil_sayisi Years of service.
	 * @param string $tur        Employee type ('kamu' or 'ozel').
	 *
	 * @return array Calculation results.
	 */
	public function hesapla_izin( $yil_sayisi, $tur = 'ozel' ) {
		$dilimler = ( 'kamu' === $tur ) ? self::get_kamu_dilimleri() : self::get_ozel_dilimleri();

		$gun          = 0;
		$aktif_dilim  = '';
		$kalan_yil    = 0;
		$sonraki_gun  = 0;

		foreach ( $dilimler as $index => $dilim ) {
			if ( $yil_sayisi >= $dilim['min_yil'] && $yil_sayisi < $dilim['max_yil'] ) {
				$gun         = $dilim['gun'];
				$aktif_dilim = $dilim['dilim'];

				// Calculate years until next bracket.
				if ( isset( $dilimler[ $index + 1 ] ) ) {
					$kalan_yil   = $dilimler[ $index + 1 ]['min_yil'] - $yil_sayisi;
					$sonraki_gun = $dilimler[ $index + 1 ]['gun'];
				}
				break;
			}
		}

		$kanun = ( 'kamu' === $tur )
			? __( '657 sayılı Devlet Memurları Kanunu', 'turkiye-ik-hesaplama' )
			: __( '4857 sayılı İş Kanunu', 'turkiye-ik-hesaplama' );

		return array(
			'tur'         => $tur,
			'calismaYil'  => $yil_sayisi,
			'gun'         => $gun,
			'dilim'       => $aktif_dilim,
			'kalan'       => $kalan_yil,
			'sonrakiGun'  => $sonraki_gun,
			'kanun'       => $kanun,
		);
	}

	/**
	 * Calculate leave from start date (detailed calculation).
	 *
	 * @since 1.0.0
	 *
	 * @param string $baslama_tarihi Start date (Y-m-d format).
	 * @param string $tur            Employee type.
	 *
	 * @return array Calculation results.
	 */
	public function hesapla_detayli( $baslama_tarihi, $tur = 'ozel' ) {
		$diff = tikh_date_diff( $baslama_tarihi );

		$yil_sayisi = $diff['years'];
		$sonuc      = $this->hesapla_izin( $yil_sayisi, $tur );

		// Add date details.
		$sonuc['baslama'] = wp_date( 'd.m.Y', strtotime( $baslama_tarihi ) );
		$sonuc['bugun']   = wp_date( 'd.m.Y' );
		$sonuc['yil']     = $diff['years'];
		$sonuc['ay']      = $diff['months'];
		$sonuc['gun_fark'] = $diff['days'];

		return $sonuc;
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
		// Detailed calculation form values.
		$detayli_sonuc = null;
		$detayli_hata  = '';
		$d_form        = array(
			'tur'   => 'ozel',
			'tarih' => '',
		);

		// Quick calculation form values.
		$hizli_sonuc = null;
		$hizli_hata  = '';
		$h_form      = array(
			'tur' => 'ozel',
			'yil' => '',
		);

		// Process detailed form submission.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['tikh_izin_d_nonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tikh_izin_d_nonce'] ) ), 'tikh_izin_d_action' ) ) {
				$d_form['tur']   = isset( $_POST['iznh_d_tur'] ) ? sanitize_text_field( wp_unslash( $_POST['iznh_d_tur'] ) ) : 'ozel';
				$d_form['tarih'] = isset( $_POST['iznh_d_tarih'] ) ? sanitize_text_field( wp_unslash( $_POST['iznh_d_tarih'] ) ) : '';

				if ( empty( $d_form['tarih'] ) ) {
					$detayli_hata = __( 'İşe başlama tarihi giriniz.', 'turkiye-ik-hesaplama' );
				} else {
					$tarih_obj = DateTime::createFromFormat( 'Y-m-d', $d_form['tarih'] );
					if ( ! $tarih_obj || $tarih_obj > new DateTime() ) {
						$detayli_hata = __( 'Geçerli bir tarih giriniz.', 'turkiye-ik-hesaplama' );
					} else {
						$detayli_sonuc = $this->hesapla_detayli( $d_form['tarih'], $d_form['tur'] );
					}
				}
			}
		}

		// Process quick form submission.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && isset( $_POST['tikh_izin_h_nonce'] ) ) {
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['tikh_izin_h_nonce'] ) ), 'tikh_izin_h_action' ) ) {
				$h_form['tur'] = isset( $_POST['iznh_h_tur'] ) ? sanitize_text_field( wp_unslash( $_POST['iznh_h_tur'] ) ) : 'ozel';
				$h_form['yil'] = isset( $_POST['iznh_h_yil'] ) ? sanitize_text_field( wp_unslash( $_POST['iznh_h_yil'] ) ) : '';

				if ( '' === $h_form['yil'] ) {
					$hizli_hata = __( 'Çalışma süresi seçiniz.', 'turkiye-ik-hesaplama' );
				} else {
					$hizli_sonuc = $this->hesapla_izin( absint( $h_form['yil'] ), $h_form['tur'] );
				}
			}
		}

		// Render template.
		return tikh_get_template(
			'izin-form',
			array(
				'detayli_sonuc' => $detayli_sonuc,
				'detayli_hata'  => $detayli_hata,
				'd_form'        => $d_form,
				'hizli_sonuc'   => $hizli_sonuc,
				'hizli_hata'    => $hizli_hata,
				'h_form'        => $h_form,
				'kamu_dilim'    => self::get_kamu_dilimleri(),
				'ozel_dilim'    => self::get_ozel_dilimleri(),
			)
		);
	}
}
