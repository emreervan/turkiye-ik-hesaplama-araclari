<?php
/**
 * Admin functionality.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_Admin
 *
 * Handles admin-specific functionality.
 *
 * @since 1.0.0
 */
class TIKH_Admin {

	/**
	 * Add admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_admin_menu() {
		add_menu_page(
			__( 'İK Hesaplama Araçları', 'turkiye-ik-hesaplama' ),
			__( 'İK Araçları', 'turkiye-ik-hesaplama' ),
			'manage_options',
			'tikh-araclar',
			array( $this, 'render_admin_page' ),
			'dashicons-calculator',
			30
		);
	}

	/**
	 * Enqueue admin styles.
	 *
	 * @since 1.0.0
	 *
	 * @param string $hook The current admin page hook.
	 */
	public function enqueue_styles( $hook ) {
		// Only load on our admin page.
		if ( 'toplevel_page_tikh-araclar' !== $hook ) {
			return;
		}

		wp_enqueue_style(
			'tikh-admin',
			TIKH_PLUGIN_URL . 'admin/css/tikh-admin.css',
			array(),
			TIKH_VERSION,
			'all'
		);
	}

	/**
	 * Render admin page.
	 *
	 * @since 1.0.0
	 */
	public function render_admin_page() {
		include TIKH_PLUGIN_DIR . 'admin/partials/tikh-admin-display.php';
	}
}
