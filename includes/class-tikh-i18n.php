<?php
/**
 * Internationalization class.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class TIKH_I18n
 *
 * Defines the internationalization functionality.
 *
 * @since 1.0.0
 */
class TIKH_I18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since 1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'turkiye-ik-hesaplama',
			false,
			dirname( TIKH_PLUGIN_BASENAME ) . '/languages/'
		);
	}
}
