<?php
/**
 * İzin calculator form template.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 *
 * @var array  $detayli_sonuc Detailed calculation results.
 * @var string $detayli_hata  Detailed form error message.
 * @var array  $d_form        Detailed form values.
 * @var array  $hizli_sonuc   Quick calculation results.
 * @var string $hizli_hata    Quick form error message.
 * @var array  $h_form        Quick form values.
 * @var array  $kamu_dilim    Public sector brackets.
 * @var array  $ozel_dilim    Private sector brackets.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="tikh2026-wrap tikh2026-izin-wrap">
	<div class="tikh2026-izin-head">
		<h2><?php esc_html_e( 'Yıllık İzin Hesaplama', 'turkiye-ik-hesaplama' ); ?></h2>
		<div class="tikh2026-sub"><?php esc_html_e( 'Kamu ve Özel Sektör İzin Hakları', 'turkiye-ik-hesaplama' ); ?></div>
	</div>

	<div class="tikh2026-izin-grid">
		<!-- Detailed Calculation -->
		<div class="tikh2026-izin-card">
			<div class="tikh2026-izin-cardhead tikh2026-det">
				<h3><?php esc_html_e( 'Detaylı Hesaplama', 'turkiye-ik-hesaplama' ); ?></h3>
				<div class="tikh2026-izin-cdesc"><?php esc_html_e( 'İşe başlama tarihine göre', 'turkiye-ik-hesaplama' ); ?></div>
			</div>
			<div class="tikh2026-izin-cardbody">
				<?php if ( $detayli_hata ) : ?>
					<div class="tikh2026-err"><?php echo esc_html( $detayli_hata ); ?></div>
				<?php endif; ?>

				<form method="post">
					<?php wp_nonce_field( 'tikh_izin_d_action', 'tikh_izin_d_nonce' ); ?>

					<div class="tikh2026-group">
						<label><?php esc_html_e( 'Çalışan Türü', 'turkiye-ik-hesaplama' ); ?></label>
						<div class="tikh2026-radios">
							<label class="tikh2026-radio tikh2026-kamu">
								<input type="radio" name="iznh_d_tur" value="kamu" <?php checked( $d_form['tur'], 'kamu' ); ?>>
								<span class="tikh2026-rlbl"><?php esc_html_e( 'Kamu', 'turkiye-ik-hesaplama' ); ?></span>
							</label>
							<label class="tikh2026-radio tikh2026-ozel">
								<input type="radio" name="iznh_d_tur" value="ozel" <?php checked( $d_form['tur'], 'ozel' ); ?>>
								<span class="tikh2026-rlbl"><?php esc_html_e( 'Özel Sektör', 'turkiye-ik-hesaplama' ); ?></span>
							</label>
						</div>
					</div>

					<div class="tikh2026-group">
						<label for="tikh2026_izin_d_tarih"><?php esc_html_e( 'İşe Başlama Tarihi', 'turkiye-ik-hesaplama' ); ?></label>
						<input type="date" name="iznh_d_tarih" id="tikh2026_izin_d_tarih" value="<?php echo esc_attr( $d_form['tarih'] ); ?>" required>
					</div>

					<button type="submit" class="tikh2026-btn" style="margin-top: 20px; background: linear-gradient(135deg, #4299e1, #3182ce);"><?php esc_html_e( 'Hesapla', 'turkiye-ik-hesaplama' ); ?></button>
				</form>

				<?php if ( $detayli_sonuc ) : ?>
					<div class="tikh2026-result" style="margin-top: 20px;">
						<div class="tikh2026-reshead" style="background: linear-gradient(135deg, #4299e1, #3182ce); display: flex; justify-content: space-between;">
							<h4 style="margin: 0;"><?php esc_html_e( 'Sonuç', 'turkiye-ik-hesaplama' ); ?></h4>
							<span><?php echo 'kamu' === $detayli_sonuc['tur'] ? esc_html__( 'Kamu', 'turkiye-ik-hesaplama' ) : esc_html__( 'Özel Sektör', 'turkiye-ik-hesaplama' ); ?></span>
						</div>
						<div class="tikh2026-resbody">
							<div class="tikh2026-izin-big <?php echo $detayli_sonuc['gun'] > 0 ? 'tikh2026-pos' : 'tikh2026-zero'; ?>">
								<div class="tikh2026-num"><?php echo esc_html( $detayli_sonuc['gun'] ); ?></div>
								<div class="tikh2026-unit"><?php esc_html_e( 'gün yıllık izin', 'turkiye-ik-hesaplama' ); ?></div>
							</div>

							<div class="tikh2026-izin-detail">
								<div class="tikh2026-izin-ditem">
									<span class="tikh2026-izin-dlbl"><?php esc_html_e( 'İşe Başlama', 'turkiye-ik-hesaplama' ); ?></span>
									<span class="tikh2026-izin-dval"><?php echo esc_html( $detayli_sonuc['baslama'] ); ?></span>
								</div>
								<div class="tikh2026-izin-ditem">
									<span class="tikh2026-izin-dlbl"><?php esc_html_e( 'Bugün', 'turkiye-ik-hesaplama' ); ?></span>
									<span class="tikh2026-izin-dval"><?php echo esc_html( $detayli_sonuc['bugun'] ); ?></span>
								</div>
								<div class="tikh2026-izin-ditem">
									<span class="tikh2026-izin-dlbl"><?php esc_html_e( 'Çalışma Süresi', 'turkiye-ik-hesaplama' ); ?></span>
									<span class="tikh2026-izin-dval">
										<?php
										printf(
											/* translators: 1: Years, 2: Months, 3: Days */
											esc_html__( '%1$d yıl, %2$d ay, %3$d gün', 'turkiye-ik-hesaplama' ),
											esc_html( $detayli_sonuc['yil'] ),
											esc_html( $detayli_sonuc['ay'] ),
											esc_html( $detayli_sonuc['gun_fark'] )
										);
										?>
									</span>
								</div>
								<div class="tikh2026-izin-ditem">
									<span class="tikh2026-izin-dlbl"><?php esc_html_e( 'İzin Dilimi', 'turkiye-ik-hesaplama' ); ?></span>
									<span class="tikh2026-izin-dval"><?php echo esc_html( $detayli_sonuc['dilim'] ); ?></span>
								</div>
							</div>

							<?php if ( isset( $detayli_sonuc['kalan'] ) && $detayli_sonuc['kalan'] > 0 ) : ?>
								<div class="tikh2026-izin-next">
									<?php
									printf(
										/* translators: %d: Years until next bracket */
										esc_html__( '%d yıl sonra bir üst dilime geçeceksiniz.', 'turkiye-ik-hesaplama' ),
										esc_html( $detayli_sonuc['kalan'] )
									);
									?>
								</div>
							<?php endif; ?>

							<div class="tikh2026-izin-kanun"><?php echo esc_html( $detayli_sonuc['kanun'] ); ?></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Quick Calculation -->
		<div class="tikh2026-izin-card">
			<div class="tikh2026-izin-cardhead tikh2026-hiz">
				<h3><?php esc_html_e( 'Hızlı Hesaplama', 'turkiye-ik-hesaplama' ); ?></h3>
				<div class="tikh2026-izin-cdesc"><?php esc_html_e( 'Toplam çalışma yılına göre', 'turkiye-ik-hesaplama' ); ?></div>
			</div>
			<div class="tikh2026-izin-cardbody">
				<?php if ( $hizli_hata ) : ?>
					<div class="tikh2026-err"><?php echo esc_html( $hizli_hata ); ?></div>
				<?php endif; ?>

				<form method="post">
					<?php wp_nonce_field( 'tikh_izin_h_action', 'tikh_izin_h_nonce' ); ?>

					<div class="tikh2026-group">
						<label><?php esc_html_e( 'Çalışan Türü', 'turkiye-ik-hesaplama' ); ?></label>
						<div class="tikh2026-radios">
							<label class="tikh2026-radio tikh2026-kamu">
								<input type="radio" name="iznh_h_tur" value="kamu" <?php checked( $h_form['tur'], 'kamu' ); ?>>
								<span class="tikh2026-rlbl"><?php esc_html_e( 'Kamu', 'turkiye-ik-hesaplama' ); ?></span>
							</label>
							<label class="tikh2026-radio tikh2026-ozel">
								<input type="radio" name="iznh_h_tur" value="ozel" <?php checked( $h_form['tur'], 'ozel' ); ?>>
								<span class="tikh2026-rlbl"><?php esc_html_e( 'Özel Sektör', 'turkiye-ik-hesaplama' ); ?></span>
							</label>
						</div>
					</div>

					<div class="tikh2026-group">
						<label for="tikh2026_izin_h_yil"><?php esc_html_e( 'Toplam Çalışma Süresi (Yıl)', 'turkiye-ik-hesaplama' ); ?></label>
						<select name="iznh_h_yil" id="tikh2026_izin_h_yil" required>
							<option value=""><?php esc_html_e( 'Seçiniz...', 'turkiye-ik-hesaplama' ); ?></option>
							<?php for ( $i = 0; $i <= 40; $i++ ) : ?>
								<option value="<?php echo esc_attr( $i ); ?>" <?php selected( $h_form['yil'], (string) $i ); ?>>
									<?php
									printf(
										/* translators: %d: Number of years */
										esc_html__( '%d yıl', 'turkiye-ik-hesaplama' ),
										$i
									);
									?>
								</option>
							<?php endfor; ?>
						</select>
					</div>

					<button type="submit" class="tikh2026-btn" style="margin-top: 20px; background: linear-gradient(135deg, #ed8936, #dd6b20);"><?php esc_html_e( 'Hesapla', 'turkiye-ik-hesaplama' ); ?></button>
				</form>

				<?php if ( $hizli_sonuc ) : ?>
					<div class="tikh2026-result" style="margin-top: 20px;">
						<div class="tikh2026-reshead" style="background: linear-gradient(135deg, #ed8936, #dd6b20); display: flex; justify-content: space-between;">
							<h4 style="margin: 0;"><?php esc_html_e( 'Sonuç', 'turkiye-ik-hesaplama' ); ?></h4>
							<span><?php echo 'kamu' === $hizli_sonuc['tur'] ? esc_html__( 'Kamu', 'turkiye-ik-hesaplama' ) : esc_html__( 'Özel Sektör', 'turkiye-ik-hesaplama' ); ?></span>
						</div>
						<div class="tikh2026-resbody">
							<div class="tikh2026-izin-big <?php echo $hizli_sonuc['gun'] > 0 ? 'tikh2026-pos' : 'tikh2026-zero'; ?>">
								<div class="tikh2026-num"><?php echo esc_html( $hizli_sonuc['gun'] ); ?></div>
								<div class="tikh2026-unit"><?php esc_html_e( 'gün yıllık izin', 'turkiye-ik-hesaplama' ); ?></div>
							</div>

							<div class="tikh2026-izin-detail">
								<div class="tikh2026-izin-ditem">
									<span class="tikh2026-izin-dlbl"><?php esc_html_e( 'Çalışma Süresi', 'turkiye-ik-hesaplama' ); ?></span>
									<span class="tikh2026-izin-dval">
										<?php
										printf(
											/* translators: %d: Number of years */
											esc_html__( '%d yıl', 'turkiye-ik-hesaplama' ),
											esc_html( $hizli_sonuc['calismaYil'] )
										);
										?>
									</span>
								</div>
								<div class="tikh2026-izin-ditem">
									<span class="tikh2026-izin-dlbl"><?php esc_html_e( 'İzin Dilimi', 'turkiye-ik-hesaplama' ); ?></span>
									<span class="tikh2026-izin-dval"><?php echo esc_html( $hizli_sonuc['dilim'] ); ?></span>
								</div>
							</div>

							<?php if ( isset( $hizli_sonuc['kalan'] ) && $hizli_sonuc['kalan'] > 0 ) : ?>
								<div class="tikh2026-izin-next">
									<?php
									printf(
										/* translators: %d: Years until next bracket */
										esc_html__( '%d yıl sonra bir üst dilime geçeceksiniz.', 'turkiye-ik-hesaplama' ),
										esc_html( $hizli_sonuc['kalan'] )
									);
									?>
								</div>
							<?php endif; ?>

							<div class="tikh2026-izin-kanun"><?php echo esc_html( $hizli_sonuc['kanun'] ); ?></div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<!-- Reference Table -->
	<div class="tikh2026-izin-tablo">
		<div class="tikh2026-izin-tablohead">
			<h3><?php esc_html_e( 'Yıllık İzin Süreleri Tablosu', 'turkiye-ik-hesaplama' ); ?></h3>
		</div>
		<div class="tikh2026-izin-tablogrid">
			<div class="tikh2026-izin-tablocol tikh2026-kamu">
				<h4><?php esc_html_e( 'Kamu (657 DMK)', 'turkiye-ik-hesaplama' ); ?></h4>
				<?php foreach ( $kamu_dilim as $dilim ) : ?>
					<div class="tikh2026-izin-tablorow">
						<span class="tikh2026-izin-tsure"><?php echo esc_html( $dilim['dilim'] ); ?></span>
						<span class="tikh2026-izin-tgun">
							<?php
							printf(
								/* translators: %d: Number of days */
								esc_html__( '%d gün', 'turkiye-ik-hesaplama' ),
								esc_html( $dilim['gun'] )
							);
							?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="tikh2026-izin-tablocol tikh2026-ozel">
				<h4><?php esc_html_e( 'Özel Sektör (4857 İş K.)', 'turkiye-ik-hesaplama' ); ?></h4>
				<?php foreach ( $ozel_dilim as $dilim ) : ?>
					<div class="tikh2026-izin-tablorow">
						<span class="tikh2026-izin-tsure"><?php echo esc_html( $dilim['dilim'] ); ?></span>
						<span class="tikh2026-izin-tgun">
							<?php
							printf(
								/* translators: %d: Number of days */
								esc_html__( '%d gün', 'turkiye-ik-hesaplama' ),
								esc_html( $dilim['gun'] )
							);
							?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
