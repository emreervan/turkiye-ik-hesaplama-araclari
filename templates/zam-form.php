<?php
/**
 * Zam calculator form template.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 *
 * @var array  $sonuc      Calculation results.
 * @var string $hata       Error message.
 * @var string $mod        Calculation mode ('yuzde' or 'net').
 * @var float  $mevcut_net Current net salary.
 * @var float  $zam_orani  Raise percentage.
 * @var float  $zamli_net  New net salary.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="tikh2026-wrap tikh2026-zam-wrap">
	<div class="tikh2026-head">
		<h2><?php esc_html_e( 'Zam Hesaplama', 'turkiye-ik-hesaplama' ); ?></h2>
		<div class="tikh2026-sub"><?php esc_html_e( 'Maaş Artış Oranı ve Tutarı', 'turkiye-ik-hesaplama' ); ?></div>
	</div>

	<?php if ( $hata ) : ?>
		<div class="tikh2026-err"><?php echo esc_html( $hata ); ?></div>
	<?php endif; ?>

	<form method="post" class="tikh2026-form">
		<?php wp_nonce_field( 'tikh_zam_action', 'tikh_zam_nonce' ); ?>
		<input type="hidden" name="zamh_mod" id="tikh2026_zam_mod" value="<?php echo esc_attr( $mod ); ?>">

		<div class="tikh2026-mods">
			<div class="tikh2026-modbtn <?php echo 'yuzde' === $mod ? 'active' : ''; ?>" data-mode="yuzde">
				<?php esc_html_e( '% → Net', 'turkiye-ik-hesaplama' ); ?>
			</div>
			<div class="tikh2026-modbtn <?php echo 'net' === $mod ? 'active' : ''; ?>" data-mode="net">
				<?php esc_html_e( 'Net → %', 'turkiye-ik-hesaplama' ); ?>
			</div>
		</div>

		<div class="tikh2026-group">
			<label for="tikh2026_zam_mevcut"><?php esc_html_e( 'Mevcut Net Maaş (TL)', 'turkiye-ik-hesaplama' ); ?></label>
			<input type="text" name="zamh_mevcut" id="tikh2026_zam_mevcut" class="tikh2026-currency-input" placeholder="<?php esc_attr_e( 'Örn: 30.000', 'turkiye-ik-hesaplama' ); ?>" value="<?php echo $mevcut_net > 0 ? esc_attr( tikh_format_currency( $mevcut_net ) ) : ''; ?>" required>
		</div>

		<div class="tikh2026-group <?php echo 'net' === $mod ? 'hidden' : ''; ?>" id="tikh2026_zam_oran_grp">
			<label for="tikh2026_zam_oran"><?php esc_html_e( 'Zam Oranı (%)', 'turkiye-ik-hesaplama' ); ?></label>
			<input type="text" name="zamh_oran" id="tikh2026_zam_oran" placeholder="<?php esc_attr_e( 'Örn: 25', 'turkiye-ik-hesaplama' ); ?>" value="<?php echo $zam_orani > 0 ? esc_attr( tikh_format_currency( $zam_orani ) ) : ''; ?>">
		</div>

		<div class="tikh2026-group <?php echo 'yuzde' === $mod ? 'hidden' : ''; ?>" id="tikh2026_zam_zamli_grp">
			<label for="tikh2026_zam_zamli"><?php esc_html_e( 'Zamlı Net Maaş (TL)', 'turkiye-ik-hesaplama' ); ?></label>
			<input type="text" name="zamh_zamli" id="tikh2026_zam_zamli" class="tikh2026-currency-input" placeholder="<?php esc_attr_e( 'Örn: 37.500', 'turkiye-ik-hesaplama' ); ?>" value="<?php echo $zamli_net > 0 ? esc_attr( tikh_format_currency( $zamli_net ) ) : ''; ?>">
		</div>

		<button type="submit" class="tikh2026-btn" style="margin-top: 20px;"><?php esc_html_e( 'Hesapla', 'turkiye-ik-hesaplama' ); ?></button>
	</form>

	<?php if ( $sonuc ) : ?>
		<div class="tikh2026-result">
			<div class="tikh2026-reshead"><?php esc_html_e( 'Hesaplama Sonucu', 'turkiye-ik-hesaplama' ); ?></div>
			<div class="tikh2026-resbody">
				<div class="tikh2026-resitem">
					<span class="tikh2026-lbl"><?php esc_html_e( 'Mevcut Net', 'turkiye-ik-hesaplama' ); ?></span>
					<span class="tikh2026-val"><?php echo esc_html( tikh_format_currency( $sonuc['mevcut'] ) ); ?> TL</span>
				</div>
				<div class="tikh2026-resitem">
					<span class="tikh2026-lbl"><?php esc_html_e( 'Zam Oranı', 'turkiye-ik-hesaplama' ); ?></span>
					<span class="tikh2026-val">%<?php echo esc_html( tikh_format_currency( $sonuc['oran'] ) ); ?></span>
				</div>
				<div class="tikh2026-resitem">
					<span class="tikh2026-lbl"><?php esc_html_e( 'Zam Tutarı', 'turkiye-ik-hesaplama' ); ?></span>
					<span class="tikh2026-val">+<?php echo esc_html( tikh_format_currency( $sonuc['tutar'] ) ); ?> TL</span>
				</div>
				<div class="tikh2026-resitem">
					<span class="tikh2026-lbl"><?php esc_html_e( 'Artış Katı', 'turkiye-ik-hesaplama' ); ?></span>
					<span class="tikh2026-val"><?php echo esc_html( tikh_format_currency( $sonuc['kat'] ) ); ?>x</span>
				</div>
				<div class="tikh2026-resitem tikh2026-big">
					<span class="tikh2026-lbl"><?php esc_html_e( 'Zamlı Net Maaş', 'turkiye-ik-hesaplama' ); ?></span>
					<span class="tikh2026-val">
						<?php
						$yeni_net = ( 'yuzde' === $sonuc['mod'] ) ? $sonuc['yeni'] : $sonuc['zamli'];
						echo esc_html( tikh_format_currency( $yeni_net ) );
						?>
						TL
					</span>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
