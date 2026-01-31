<?php
/**
 * Gelir Vergisi calculator form template.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 *
 * @var array  $sonuc   Calculation results.
 * @var string $hata    Error message.
 * @var int    $yil     Selected year.
 * @var string $tip     Taxpayer type.
 * @var float  $matrah  Tax base.
 * @var bool   $istisna Whether exemption is applied.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="tikh2026-wrap tikh2026-gv-wrap">
	<div class="tikh2026-head">
		<h2><?php esc_html_e( 'Gelir Vergisi Hesaplama', 'turkiye-ik-hesaplama' ); ?></h2>
		<div class="tikh2026-sub"><?php esc_html_e( 'Artan Oranlı Vergi Hesaplaması', 'turkiye-ik-hesaplama' ); ?></div>
	</div>

	<?php if ( $hata ) : ?>
		<div class="tikh2026-err"><?php echo esc_html( $hata ); ?></div>
	<?php endif; ?>

	<form method="post" class="tikh2026-form">
		<?php wp_nonce_field( 'tikh_gv_action', 'tikh_gv_nonce' ); ?>

		<div class="tikh2026-grid">
			<div class="tikh2026-group">
				<label for="tikh2026_gv_yil"><?php esc_html_e( 'Yıl', 'turkiye-ik-hesaplama' ); ?></label>
				<select name="gvh_yil" id="tikh2026_gv_yil">
					<option value="2026" <?php selected( $yil, 2026 ); ?>>2026</option>
					<option value="2025" <?php selected( $yil, 2025 ); ?>>2025</option>
				</select>
			</div>

			<div class="tikh2026-group">
				<label for="tikh2026_gv_tip"><?php esc_html_e( 'Mükellef Türü', 'turkiye-ik-hesaplama' ); ?></label>
				<select name="gvh_tip" id="tikh2026_gv_tip">
					<option value="ucretli" <?php selected( $tip, 'ucretli' ); ?>><?php esc_html_e( 'Ücretli (Maaşlı)', 'turkiye-ik-hesaplama' ); ?></option>
					<option value="diger" <?php selected( $tip, 'diger' ); ?>><?php esc_html_e( 'Ücret Dışı', 'turkiye-ik-hesaplama' ); ?></option>
				</select>
			</div>

			<div class="tikh2026-group">
				<label for="tikh2026_gv_matrah"><?php esc_html_e( 'Yıllık Vergi Matrahı (TL)', 'turkiye-ik-hesaplama' ); ?></label>
				<input type="text" name="gvh_matrah" id="tikh2026_gv_matrah" class="tikh2026-currency-input" placeholder="<?php esc_attr_e( 'Örn: 500.000', 'turkiye-ik-hesaplama' ); ?>" value="<?php echo $matrah > 0 ? esc_attr( tikh_format_currency( $matrah ) ) : ''; ?>" required>
			</div>

			<div class="tikh2026-group">
				<label class="tikh2026-check" id="tikh2026_gv_istisna_wrap" style="<?php echo 'diger' === $tip ? 'opacity: 0.5;' : ''; ?>">
					<input type="checkbox" name="gvh_istisna" id="tikh2026_gv_istisna" <?php checked( $istisna ); ?> <?php disabled( 'diger' === $tip ); ?>>
					<span><?php esc_html_e( 'Asgari Ücret İstisnası', 'turkiye-ik-hesaplama' ); ?></span>
				</label>
			</div>
		</div>

		<button type="submit" class="tikh2026-btn tikh2026-gv-btn"><?php esc_html_e( 'Hesapla', 'turkiye-ik-hesaplama' ); ?></button>
	</form>

	<?php if ( $sonuc ) : ?>
		<div class="tikh2026-result">
			<div class="tikh2026-reshead tikh2026-gv-reshead">
				<?php
				printf(
					/* translators: %d: Year */
					esc_html__( '%d Yılı Gelir Vergisi Hesaplaması', 'turkiye-ik-hesaplama' ),
					esc_html( $sonuc['yil'] )
				);
				?>
			</div>

			<!-- Summary Cards -->
			<div class="tikh2026-cards" style="padding: 20px;">
				<div class="tikh2026-card">
					<div class="tikh2026-ctitle"><?php esc_html_e( 'Matrah', 'turkiye-ik-hesaplama' ); ?></div>
					<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuc['matrah'], 0 ) ); ?> TL</div>
				</div>
				<div class="tikh2026-card">
					<div class="tikh2026-ctitle"><?php esc_html_e( 'Brüt Vergi', 'turkiye-ik-hesaplama' ); ?></div>
					<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuc['brutVergi'] ) ); ?> TL</div>
				</div>
				<?php if ( $sonuc['istisna'] > 0 ) : ?>
					<div class="tikh2026-card">
						<div class="tikh2026-ctitle"><?php esc_html_e( 'İstisna', 'turkiye-ik-hesaplama' ); ?></div>
						<div class="tikh2026-cval">-<?php echo esc_html( tikh_format_currency( $sonuc['istisna'] ) ); ?> TL</div>
					</div>
				<?php endif; ?>
				<div class="tikh2026-card tikh2026-main">
					<div class="tikh2026-ctitle"><?php esc_html_e( 'Ödenecek Vergi', 'turkiye-ik-hesaplama' ); ?></div>
					<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuc['netVergi'] ) ); ?> TL</div>
				</div>
				<div class="tikh2026-card">
					<div class="tikh2026-ctitle"><?php esc_html_e( 'Efektif Oran', 'turkiye-ik-hesaplama' ); ?></div>
					<div class="tikh2026-cval">%<?php echo esc_html( tikh_format_currency( $sonuc['efektif'] ) ); ?></div>
				</div>
			</div>

			<!-- Tax Brackets Table -->
			<table class="tikh2026-tbl" style="min-width: auto;">
				<thead>
					<tr>
						<th>#</th>
						<th><?php esc_html_e( 'Dilim', 'turkiye-ik-hesaplama' ); ?></th>
						<th><?php esc_html_e( 'Oran', 'turkiye-ik-hesaplama' ); ?></th>
						<th><?php esc_html_e( 'Matrah', 'turkiye-ik-hesaplama' ); ?></th>
						<th><?php esc_html_e( 'Vergi', 'turkiye-ik-hesaplama' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $sonuc['dilimler'] as $d ) : ?>
						<?php
						$row_class = '';
						if ( $d['mevcut'] ) {
							$row_class = 'tikh2026-active';
						} elseif ( ! $d['aktif'] ) {
							$row_class = 'tikh2026-inactive';
						}
						?>
						<tr class="<?php echo esc_attr( $row_class ); ?>">
							<td><?php echo esc_html( $d['no'] ); ?></td>
							<td>
								<?php
								echo esc_html( tikh_format_currency( $d['alt'], 0 ) );
								echo ' - ';
								echo ( $d['ust'] >= PHP_FLOAT_MAX ) ? '∞' : esc_html( tikh_format_currency( $d['ust'], 0 ) );
								?>
							</td>
							<td>%<?php echo esc_html( $d['oran'] * 100 ); ?></td>
							<td><?php echo esc_html( tikh_format_currency( $d['matrah'] ) ); ?></td>
							<td><?php echo esc_html( tikh_format_currency( $d['vergi'] ) ); ?></td>
						</tr>
					<?php endforeach; ?>
					<tr class="tikh2026-tot">
						<td colspan="3"><?php esc_html_e( 'TOPLAM', 'turkiye-ik-hesaplama' ); ?></td>
						<td><?php echo esc_html( tikh_format_currency( $sonuc['matrah'] ) ); ?></td>
						<td><?php echo esc_html( tikh_format_currency( $sonuc['brutVergi'] ) ); ?></td>
					</tr>
				</tbody>
			</table>

			<?php if ( $sonuc['aktifDilim'] ) : ?>
				<div class="tikh2026-note">
					<?php
					printf(
						/* translators: %s: Tax bracket percentage */
						esc_html__( 'Matrahınız %%%s diliminde.', 'turkiye-ik-hesaplama' ),
						esc_html( $sonuc['aktifDilim']['oran'] * 100 )
					);
					?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
