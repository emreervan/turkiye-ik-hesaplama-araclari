<?php
/**
 * Bordro calculator form template.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 *
 * @var bool   $hesap_yapildi  Whether calculation was performed.
 * @var array  $sonuclar       Calculation results.
 * @var string $hata_mesaji    Error message.
 * @var string $hesap_turu     Calculation type.
 * @var float  $girilen_deger  Input value.
 * @var bool   $emekli_mi      Whether employee is retired.
 * @var float  $hazine_destegi Treasury support rate.
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="tikh2026-wrap tikh2026-bordro-wrap">
	<div class="tikh2026-head">
		<h2><?php esc_html_e( 'Maaş Hesaplama', 'turkiye-ik-hesaplama' ); ?></h2>
		<div class="tikh2026-sub"><?php esc_html_e( 'Türkiye Bordro Mevzuatına Uyumlu', 'turkiye-ik-hesaplama' ); ?></div>
		<span class="tikh2026-badge">2026</span>
	</div>

	<?php if ( $hata_mesaji ) : ?>
		<div class="tikh2026-err"><?php echo esc_html( $hata_mesaji ); ?></div>
	<?php endif; ?>

	<form method="post" class="tikh2026-form">
		<?php wp_nonce_field( 'tikh_bordro_action', 'tikh_bordro_nonce' ); ?>

		<div class="tikh2026-grid">
			<div class="tikh2026-group">
				<label for="tikh2026_bordro_turu"><?php esc_html_e( 'Hesaplama Türü', 'turkiye-ik-hesaplama' ); ?></label>
				<select name="hesap_turu" id="tikh2026_bordro_turu">
					<option value="brut_net" <?php selected( $hesap_turu, 'brut_net' ); ?>><?php esc_html_e( 'Brütten Nete', 'turkiye-ik-hesaplama' ); ?></option>
					<option value="net_brut" <?php selected( $hesap_turu, 'net_brut' ); ?>><?php esc_html_e( 'Netten Brüte', 'turkiye-ik-hesaplama' ); ?></option>
				</select>
			</div>

			<div class="tikh2026-group">
				<label for="tikh2026_bordro_tutar" id="tikh2026_bordro_lbl"><?php esc_html_e( 'Aylık Brüt Maaş (TL)', 'turkiye-ik-hesaplama' ); ?></label>
				<input type="text" name="maas_tutari" id="tikh2026_bordro_tutar" class="tikh2026-currency-input" placeholder="<?php esc_attr_e( 'Örn: 50.000', 'turkiye-ik-hesaplama' ); ?>" value="<?php echo $girilen_deger > 0 ? esc_attr( tikh_format_currency( $girilen_deger ) ) : ''; ?>" required>
			</div>

			<div class="tikh2026-group">
				<label for="tikh2026_bordro_hazine"><?php esc_html_e( 'Hazine Desteği', 'turkiye-ik-hesaplama' ); ?></label>
				<select name="hazine_destegi" id="tikh2026_bordro_hazine">
					<option value="0.05" <?php selected( $hazine_destegi, 0.05 ); ?>><?php esc_html_e( '%5 İndirim', 'turkiye-ik-hesaplama' ); ?></option>
					<option value="0.02" <?php selected( $hazine_destegi, 0.02 ); ?>><?php esc_html_e( '%2 İndirim', 'turkiye-ik-hesaplama' ); ?></option>
					<option value="0" <?php selected( $hazine_destegi, 0 ); ?>><?php esc_html_e( 'Yok', 'turkiye-ik-hesaplama' ); ?></option>
				</select>
			</div>
		</div>

		<button type="submit" class="tikh2026-btn"><?php esc_html_e( 'Hesapla', 'turkiye-ik-hesaplama' ); ?></button>
	</form>

	<?php if ( $hesap_yapildi && $sonuclar ) : ?>
		<?php
		// Determine if it's brut_net or net_brut
		$is_brut_net = ( 'Brütten Nete' === $sonuclar['hesap_turu'] || 'brut_net' === $hesap_turu );
		?>

		<!-- Summary Cards -->
		<div class="tikh2026-cards">
			<div class="tikh2026-card tikh2026-card-green">
				<div class="tikh2026-ctitle"><?php esc_html_e( 'Yıllık Brüt', 'turkiye-ik-hesaplama' ); ?></div>
				<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['brut'], 0 ) ); ?><span class="tikh2026-cunit"> TL</span></div>
			</div>
			<div class="tikh2026-card tikh2026-card-blue">
				<div class="tikh2026-ctitle"><?php esc_html_e( 'Yıllık Toplam Net', 'turkiye-ik-hesaplama' ); ?></div>
				<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['toplam_net_ele_gecen'], 0 ) ); ?><span class="tikh2026-cunit"> TL</span></div>
			</div>
			<div class="tikh2026-card tikh2026-card-orange">
				<div class="tikh2026-ctitle"><?php esc_html_e( 'Toplam Kesinti', 'turkiye-ik-hesaplama' ); ?></div>
				<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['brut'] - $sonuclar['toplam']['net'], 0 ) ); ?><span class="tikh2026-cunit"> TL</span></div>
			</div>
			<div class="tikh2026-card tikh2026-card-purple">
				<div class="tikh2026-ctitle"><?php esc_html_e( 'İşveren Maliyeti', 'turkiye-ik-hesaplama' ); ?></div>
				<div class="tikh2026-cval"><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['toplam_isveren_maliyeti'], 0 ) ); ?><span class="tikh2026-cunit"> TL</span></div>
			</div>
		</div>

		<!-- Details -->
		<div class="tikh2026-info">
			<h4><?php esc_html_e( 'Detaylar', 'turkiye-ik-hesaplama' ); ?></h4>
			<ul>
				<li><strong><?php esc_html_e( 'Hesaplama Türü:', 'turkiye-ik-hesaplama' ); ?></strong> <?php echo esc_html( $sonuclar['hesap_turu'] ); ?></li>
				<li><strong><?php esc_html_e( 'Girilen Değer:', 'turkiye-ik-hesaplama' ); ?></strong> <?php echo esc_html( tikh_format_currency( $sonuclar['girilen'] ) ); ?> TL</li>
				<?php if ( $sonuclar['hazine_destegi'] > 0 ) : ?>
					<li><strong><?php esc_html_e( 'Hazine Desteği:', 'turkiye-ik-hesaplama' ); ?></strong> %<?php echo esc_html( $sonuclar['hazine_destegi'] * 100 ); ?></li>
				<?php endif; ?>
			</ul>
		</div>

		<?php if ( $is_brut_net ) : ?>
			<!-- BRÜTTEN NETE: Çalışan Bordrosu -->
			<div class="tikh2026-tblwrap">
				<div class="tikh2026-tblhead"><?php esc_html_e( 'Çalışan Bordrosu (Brütten Nete)', 'turkiye-ik-hesaplama' ); ?></div>
				<div class="tikh2026-tblscroll">
					<table class="tikh2026-tbl">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Ay', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Brüt', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'SGK İşçi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'İşsizlik İşçi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Gelir Vergisi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Damga Vergisi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Küm. Matrah', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Net', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'GV İstisnası', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'DV İstisnası', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Toplam Net', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'SGK İşveren', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'İşsizlik İşveren', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Toplam Maliyet', 'turkiye-ik-hesaplama' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $sonuclar['aylik'] as $ay ) : ?>
								<tr>
									<td><?php echo esc_html( $ay['ay'] ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['brut'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['sgk_isci'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['issizlik_isci'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['gelir_vergisi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['damga_vergisi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['kumulatif_gv_matrahi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['net'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['gv_istisnasi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['damga_istisnasi'] ) ); ?></td>
									<td><strong><?php echo esc_html( tikh_format_currency( $ay['toplam_net_ele_gecen'] ) ); ?></strong></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['sgk_isveren'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['issizlik_isveren'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['toplam_isveren_maliyeti'] ) ); ?></td>
								</tr>
							<?php endforeach; ?>
							<tr class="tikh2026-tot">
								<td><?php esc_html_e( 'TOPLAM', 'turkiye-ik-hesaplama' ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['brut'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['sgk_isci'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['issizlik_isci'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['gelir_vergisi'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['damga_vergisi'] ) ); ?></td>
								<td>-</td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['net'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['gv_istisnasi'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['damga_istisnasi'] ) ); ?></td>
								<td><strong><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['toplam_net_ele_gecen'] ) ); ?></strong></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['sgk_isveren'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['issizlik_isveren'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['toplam_isveren_maliyeti'] ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

		<?php else : ?>
			<!-- NETTEN BRÜTE: Çalışan Bordrosu -->
			<div class="tikh2026-tblwrap">
				<div class="tikh2026-tblhead"><?php esc_html_e( 'Çalışan Bordrosu (Netten Brüte)', 'turkiye-ik-hesaplama' ); ?></div>
				<div class="tikh2026-tblscroll">
					<table class="tikh2026-tbl">
						<thead>
							<tr>
								<th><?php esc_html_e( 'Ay', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Hedef Net', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'SGK İşçi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'İşsizlik İşçi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Gelir Vergisi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Damga Vergisi', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Küm. Matrah', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Brüt', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'GV İstisnası', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'DV İstisnası', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Toplam Net', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'SGK İşveren', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'İşsizlik İşveren', 'turkiye-ik-hesaplama' ); ?></th>
								<th><?php esc_html_e( 'Toplam Maliyet', 'turkiye-ik-hesaplama' ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $sonuclar['aylik'] as $ay ) : ?>
								<tr>
									<td><?php echo esc_html( $ay['ay'] ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['net'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['sgk_isci'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['issizlik_isci'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['gelir_vergisi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['damga_vergisi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['kumulatif_gv_matrahi'] ) ); ?></td>
									<td><strong><?php echo esc_html( tikh_format_currency( $ay['brut'] ) ); ?></strong></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['gv_istisnasi'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['damga_istisnasi'] ) ); ?></td>
									<td><strong><?php echo esc_html( tikh_format_currency( $ay['toplam_net_ele_gecen'] ) ); ?></strong></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['sgk_isveren'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['issizlik_isveren'] ) ); ?></td>
									<td><?php echo esc_html( tikh_format_currency( $ay['toplam_isveren_maliyeti'] ) ); ?></td>
								</tr>
							<?php endforeach; ?>
							<tr class="tikh2026-tot">
								<td><?php esc_html_e( 'TOPLAM', 'turkiye-ik-hesaplama' ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['net'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['sgk_isci'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['issizlik_isci'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['gelir_vergisi'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['damga_vergisi'] ) ); ?></td>
								<td>-</td>
								<td><strong><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['brut'] ) ); ?></strong></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['gv_istisnasi'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['damga_istisnasi'] ) ); ?></td>
								<td><strong><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['toplam_net_ele_gecen'] ) ); ?></strong></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['sgk_isveren'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['issizlik_isveren'] ) ); ?></td>
								<td><?php echo esc_html( tikh_format_currency( $sonuclar['toplam']['toplam_isveren_maliyeti'] ) ); ?></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</div>
