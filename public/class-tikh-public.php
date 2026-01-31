<?php
/**
 * Public-facing functionality.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_Public
 *
 * Handles public-facing functionality including asset enqueueing.
 *
 * @since 1.0.0
 */
class TIKH_Public {

	/**
	 * Enqueue public styles.
	 *
	 * Always enqueue on frontend - file is small and cached by browser.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style(
			'tikh-public',
			TIKH_PLUGIN_URL . 'public/css/tikh-public.css',
			array(),
			TIKH_VERSION,
			'all'
		);
	}

	/**
	 * Enqueue public scripts.
	 *
	 * Always enqueue on frontend - file is small and cached by browser.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'tikh-public',
			TIKH_PLUGIN_URL . 'public/js/tikh-public.js',
			array( 'jquery' ),
			TIKH_VERSION,
			true
		);

		// Localize script with translations and settings.
		wp_localize_script(
			'tikh-public',
			'tikhPublic',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'tikh_public_nonce' ),
				'i18n'    => array(
					'brutLabel' => __( 'Aylık Brüt Maaş (TL)', 'turkiye-ik-hesaplama' ),
					'netLabel'  => __( 'Hedef Net Maaş (TL)', 'turkiye-ik-hesaplama' ),
				),
			)
		);
	}
}
