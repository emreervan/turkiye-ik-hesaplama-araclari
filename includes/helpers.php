<?php
/**
 * Helper functions.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Format a number as Turkish currency.
 *
 * @since 1.0.0
 *
 * @param float $number   The number to format.
 * @param int   $decimals Optional. Number of decimal places. Default 2.
 *
 * @return string Formatted number (e.g., "1.234,56").
 */
function tikh_format_currency( $number, $decimals = 2 ) {
	return number_format( (float) $number, $decimals, ',', '.' );
}

/**
 * Parse a Turkish-formatted currency string to float.
 *
 * @since 1.0.0
 *
 * @param string $string The currency string to parse (e.g., "1.234,56").
 *
 * @return float The parsed number.
 */
function tikh_parse_currency( $string ) {
	// Remove thousand separators (dots) and replace decimal comma with dot.
	$cleaned = str_replace( array( '.', ',' ), array( '', '.' ), $string );
	return floatval( $cleaned );
}

/**
 * Format a number as percentage.
 *
 * @since 1.0.0
 *
 * @param float $number   The number to format.
 * @param int   $decimals Optional. Number of decimal places. Default 2.
 *
 * @return string Formatted percentage (e.g., "25,50").
 */
function tikh_format_percent( $number, $decimals = 2 ) {
	return number_format( (float) $number, $decimals, ',', '.' );
}

/**
 * Get a template file and return its contents.
 *
 * @since 1.0.0
 *
 * @param string $template_name Name of the template file (without .php).
 * @param array  $args          Optional. Variables to extract for the template.
 *
 * @return string Template output.
 */
function tikh_get_template( $template_name, $args = array() ) {
	$template_path = TIKH_PLUGIN_DIR . 'templates/' . $template_name . '.php';

	if ( ! file_exists( $template_path ) ) {
		return '';
	}

	if ( ! empty( $args ) && is_array( $args ) ) {
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract
		extract( $args, EXTR_SKIP );
	}

	ob_start();
	include $template_path;
	return ob_get_clean();
}

/**
 * Sanitize a currency input value.
 *
 * @since 1.0.0
 *
 * @param string $value The input value to sanitize.
 *
 * @return float Sanitized float value.
 */
function tikh_sanitize_currency( $value ) {
	$value = sanitize_text_field( $value );
	return tikh_parse_currency( $value );
}

/**
 * Check if a shortcode is present in the current post content.
 *
 * @since 1.0.0
 *
 * @param string|array $shortcodes Shortcode name(s) to check.
 *
 * @return bool True if any shortcode is present.
 */
function tikh_has_shortcode( $shortcodes ) {
	global $post;

	if ( ! is_a( $post, 'WP_Post' ) ) {
		return false;
	}

	if ( ! is_array( $shortcodes ) ) {
		$shortcodes = array( $shortcodes );
	}

	foreach ( $shortcodes as $shortcode ) {
		if ( has_shortcode( $post->post_content, $shortcode ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Get month names in Turkish.
 *
 * @since 1.0.0
 *
 * @return array Array of month names.
 */
function tikh_get_month_names() {
	return array(
		1  => __( 'Ocak', 'turkiye-ik-hesaplama' ),
		2  => __( 'Şubat', 'turkiye-ik-hesaplama' ),
		3  => __( 'Mart', 'turkiye-ik-hesaplama' ),
		4  => __( 'Nisan', 'turkiye-ik-hesaplama' ),
		5  => __( 'Mayıs', 'turkiye-ik-hesaplama' ),
		6  => __( 'Haziran', 'turkiye-ik-hesaplama' ),
		7  => __( 'Temmuz', 'turkiye-ik-hesaplama' ),
		8  => __( 'Ağustos', 'turkiye-ik-hesaplama' ),
		9  => __( 'Eylül', 'turkiye-ik-hesaplama' ),
		10 => __( 'Ekim', 'turkiye-ik-hesaplama' ),
		11 => __( 'Kasım', 'turkiye-ik-hesaplama' ),
		12 => __( 'Aralık', 'turkiye-ik-hesaplama' ),
	);
}

/**
 * Calculate date difference in years, months, and days.
 *
 * @since 1.0.0
 *
 * @param string $start_date Start date in Y-m-d format.
 * @param string $end_date   Optional. End date in Y-m-d format. Defaults to today.
 *
 * @return array Array with 'years', 'months', 'days', and 'total_days' keys.
 */
function tikh_date_diff( $start_date, $end_date = null ) {
	$start = new DateTime( $start_date );
	$end   = $end_date ? new DateTime( $end_date ) : new DateTime();

	$diff = $start->diff( $end );

	return array(
		'years'      => $diff->y,
		'months'     => $diff->m,
		'days'       => $diff->d,
		'total_days' => $diff->days,
	);
}
