<?php
/**
 * Admin page display template.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="tikh-admin-wrap">
	<div class="tikh-admin-header">
		<h1><?php esc_html_e( 'Türkiye İK Hesaplama Araçları', 'turkiye-ik-hesaplama' ); ?></h1>
		<p><?php esc_html_e( 'Bordro, zam, gelir vergisi ve yıllık izin hesaplama araçları', 'turkiye-ik-hesaplama' ); ?></p>
	</div>

	<div class="tikh-admin-body">
		<!-- Shortcodes Section -->
		<div class="tikh-admin-section">
			<h2>
				<span class="dashicons dashicons-shortcode"></span>
				<?php esc_html_e( 'Kullanılabilir Shortcode\'lar', 'turkiye-ik-hesaplama' ); ?>
			</h2>

			<div class="tikh-shortcode-list">
				<div class="tikh-shortcode-item">
					<h3><?php esc_html_e( 'Maaş / Bordro Hesaplama', 'turkiye-ik-hesaplama' ); ?></h3>
					<code>[maas_hesapla]</code>
					<p><?php esc_html_e( 'Brütten nete ve netten brüte maaş hesaplama. 12 aylık detaylı bordro çıktısı.', 'turkiye-ik-hesaplama' ); ?></p>
				</div>

				<div class="tikh-shortcode-item">
					<h3><?php esc_html_e( 'Zam Hesaplama', 'turkiye-ik-hesaplama' ); ?></h3>
					<code>[zam_hesapla]</code>
					<p><?php esc_html_e( 'Yüzdelik zam oranından yeni maaş veya yeni maaştan zam oranı hesaplama.', 'turkiye-ik-hesaplama' ); ?></p>
				</div>

				<div class="tikh-shortcode-item">
					<h3><?php esc_html_e( 'Gelir Vergisi Hesaplama', 'turkiye-ik-hesaplama' ); ?></h3>
					<code>[gelir_vergisi_hesapla]</code>
					<p><?php esc_html_e( 'Artan oranlı gelir vergisi hesaplama. 2025 ve 2026 vergi dilimleri.', 'turkiye-ik-hesaplama' ); ?></p>
				</div>

				<div class="tikh-shortcode-item">
					<h3><?php esc_html_e( 'Yıllık İzin Hesaplama', 'turkiye-ik-hesaplama' ); ?></h3>
					<code>[izin_hesapla]</code>
					<p><?php esc_html_e( 'Kamu (657 DMK) ve özel sektör (4857 İş K.) yıllık izin hakları.', 'turkiye-ik-hesaplama' ); ?></p>
				</div>
			</div>
		</div>

		<!-- 2026 Parameters Section -->
		<div class="tikh-admin-section">
			<h2>
				<span class="dashicons dashicons-info-outline"></span>
				<?php esc_html_e( '2026 Yılı Parametreleri', 'turkiye-ik-hesaplama' ); ?>
			</h2>

			<div class="tikh-params-grid">
				<div class="tikh-param-card">
					<h4><?php esc_html_e( 'Asgari Ücret', 'turkiye-ik-hesaplama' ); ?></h4>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'Brüt (Ocak-Haziran)', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">33.030 TL</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'Brüt (Temmuz-Aralık)', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">37.455 TL</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'SGK Tavan', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">330.300 TL</span>
					</div>
				</div>

				<div class="tikh-param-card">
					<h4><?php esc_html_e( 'SGK Oranları', 'turkiye-ik-hesaplama' ); ?></h4>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'SGK İşçi', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">%14</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'İşsizlik İşçi', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">%1</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'SGK İşveren', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">%21,75</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label"><?php esc_html_e( 'İşsizlik İşveren', 'turkiye-ik-hesaplama' ); ?></span>
						<span class="tikh-param-value">%2</span>
					</div>
				</div>

				<div class="tikh-param-card">
					<h4><?php esc_html_e( 'Gelir Vergisi Dilimleri (Ücretli)', 'turkiye-ik-hesaplama' ); ?></h4>
					<div class="tikh-param-row">
						<span class="tikh-param-label">0 - 190.000 TL</span>
						<span class="tikh-param-value">%15</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label">190.000 - 400.000 TL</span>
						<span class="tikh-param-value">%20</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label">400.000 - 1.500.000 TL</span>
						<span class="tikh-param-value">%27</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label">1.500.000 - 5.300.000 TL</span>
						<span class="tikh-param-value">%35</span>
					</div>
					<div class="tikh-param-row">
						<span class="tikh-param-label">5.300.000 TL üzeri</span>
						<span class="tikh-param-value">%40</span>
					</div>
				</div>
			</div>
		</div>

		<!-- Usage Info -->
		<div class="tikh-admin-section">
			<h2>
				<span class="dashicons dashicons-editor-help"></span>
				<?php esc_html_e( 'Kullanım', 'turkiye-ik-hesaplama' ); ?>
			</h2>

			<div class="tikh-info-box">
				<h4><?php esc_html_e( 'Shortcode Nasıl Eklenir?', 'turkiye-ik-hesaplama' ); ?></h4>
				<ul>
					<li><?php esc_html_e( 'Herhangi bir sayfa veya yazıya ilgili shortcode\'u ekleyin.', 'turkiye-ik-hesaplama' ); ?></li>
					<li><?php esc_html_e( 'Gutenberg editöründe "Shortcode" bloğu kullanabilirsiniz.', 'turkiye-ik-hesaplama' ); ?></li>
					<li><?php esc_html_e( 'Klasik editörde doğrudan shortcode\'u yazabilirsiniz.', 'turkiye-ik-hesaplama' ); ?></li>
					<li><?php esc_html_e( 'Widget alanlarında da shortcode kullanabilirsiniz.', 'turkiye-ik-hesaplama' ); ?></li>
				</ul>
			</div>

			<div class="tikh-info-box" style="margin-top: 15px;">
				<h4><?php esc_html_e( 'Önemli Notlar', 'turkiye-ik-hesaplama' ); ?></h4>
				<ul>
					<li><?php esc_html_e( 'Hesaplamalar 2026 yılı mevzuatına göre yapılmaktadır.', 'turkiye-ik-hesaplama' ); ?></li>
					<li><?php esc_html_e( 'Asgari ücret istisnası ve damga vergisi istisnası otomatik uygulanmaktadır.', 'turkiye-ik-hesaplama' ); ?></li>
					<li><?php esc_html_e( 'Emekli çalışanlar için SGDP hesaplaması yapılmaktadır.', 'turkiye-ik-hesaplama' ); ?></li>
					<li><?php esc_html_e( 'Hazine desteği (%5, %2 veya yok) seçilebilmektedir.', 'turkiye-ik-hesaplama' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</div>
