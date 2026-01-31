<?php
/**
 * Türkiye İK Hesaplama Araçları
 *
 * @package           TurkiyeIKHesaplama
 * @author            Emre Ervan
 * @copyright         2026 Emre Ervan
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Türkiye İK Hesaplama Araçları
 * Plugin URI:        https://emreervan.dev/turkiye-ik-hesaplama
 * Description:       Türkiye mevzuatına uygun bordro, zam, gelir vergisi ve yıllık izin hesaplama araçları. 2026 parametreleri ile güncel.
 * Version:           1.0.0
 * Requires at least: 5.8
 * Requires PHP:      7.4
 * Author:            Emre Ervan
 * Author URI:        https://emreervan.dev
 * Text Domain:       turkiye-ik-hesaplama
 * Domain Path:       /languages
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin version.
 */
define( 'TIKH_VERSION', '1.0.0' );

/**
 * Plugin directory path.
 */
define( 'TIKH_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

/**
 * Plugin directory URL.
 */
define( 'TIKH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Plugin basename.
 */
define( 'TIKH_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Minimum PHP version required.
 */
define( 'TIKH_MIN_PHP_VERSION', '7.4' );

/**
 * Minimum WordPress version required.
 */
define( 'TIKH_MIN_WP_VERSION', '5.8' );

/**
 * Check PHP version.
 *
 * @return bool
 */
function tikh_check_php_version() {
	return version_compare( PHP_VERSION, TIKH_MIN_PHP_VERSION, '>=' );
}

/**
 * Check WordPress version.
 *
 * @return bool
 */
function tikh_check_wp_version() {
	return version_compare( get_bloginfo( 'version' ), TIKH_MIN_WP_VERSION, '>=' );
}

/**
 * Display admin notice for version requirements.
 *
 * @return void
 */
function tikh_version_notice() {
	$message = '';

	if ( ! tikh_check_php_version() ) {
		$message = sprintf(
			/* translators: 1: Required PHP version, 2: Current PHP version */
			__( 'Türkiye İK Hesaplama Araçları requires PHP %1$s or higher. You are running PHP %2$s.', 'turkiye-ik-hesaplama' ),
			TIKH_MIN_PHP_VERSION,
			PHP_VERSION
		);
	} elseif ( ! tikh_check_wp_version() ) {
		$message = sprintf(
			/* translators: 1: Required WordPress version, 2: Current WordPress version */
			__( 'Türkiye İK Hesaplama Araçları requires WordPress %1$s or higher. You are running WordPress %2$s.', 'turkiye-ik-hesaplama' ),
			TIKH_MIN_WP_VERSION,
			get_bloginfo( 'version' )
		);
	}

	if ( $message ) {
		printf( '<div class="notice notice-error"><p>%s</p></div>', esc_html( $message ) );
	}
}

/**
 * Check requirements and initialize plugin.
 *
 * @return void
 */
function tikh_init() {
	// Check requirements.
	if ( ! tikh_check_php_version() || ! tikh_check_wp_version() ) {
		add_action( 'admin_notices', 'tikh_version_notice' );
		return;
	}

	// Load dependencies.
	require_once TIKH_PLUGIN_DIR . 'includes/helpers.php';
	require_once TIKH_PLUGIN_DIR . 'includes/class-tikh-loader.php';
	require_once TIKH_PLUGIN_DIR . 'includes/class-tikh-i18n.php';

	// Load modules.
	require_once TIKH_PLUGIN_DIR . 'modules/class-tikh-bordro.php';
	require_once TIKH_PLUGIN_DIR . 'modules/class-tikh-zam.php';
	require_once TIKH_PLUGIN_DIR . 'modules/class-tikh-gelir-vergisi.php';
	require_once TIKH_PLUGIN_DIR . 'modules/class-tikh-izin.php';

	// Load public class.
	require_once TIKH_PLUGIN_DIR . 'public/class-tikh-public.php';

	// Load admin class.
	if ( is_admin() ) {
		require_once TIKH_PLUGIN_DIR . 'admin/class-tikh-admin.php';
	}

	// Set up internationalization.
	$i18n = new TIKH_I18n();
	add_action( 'init', array( $i18n, 'load_plugin_textdomain' ) );

	// Initialize modules - register shortcodes directly.
	$bordro = new TIKH_Bordro();
	add_action( 'init', array( $bordro, 'register_shortcode' ) );

	$zam = new TIKH_Zam();
	add_action( 'init', array( $zam, 'register_shortcode' ) );

	$gelir_vergisi = new TIKH_Gelir_Vergisi();
	add_action( 'init', array( $gelir_vergisi, 'register_shortcode' ) );

	$izin = new TIKH_Izin();
	add_action( 'init', array( $izin, 'register_shortcode' ) );

	// Initialize public - enqueue assets.
	$public = new TIKH_Public();
	add_action( 'wp_enqueue_scripts', array( $public, 'enqueue_styles' ) );
	add_action( 'wp_enqueue_scripts', array( $public, 'enqueue_scripts' ) );

	// Initialize admin.
	if ( is_admin() ) {
		$admin = new TIKH_Admin();
		add_action( 'admin_menu', array( $admin, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $admin, 'enqueue_styles' ) );
	}
}
add_action( 'plugins_loaded', 'tikh_init' );

/**
 * Plugin activation hook.
 *
 * @return void
 */
function tikh_activate() {
	// Check requirements.
	if ( ! tikh_check_php_version() || ! tikh_check_wp_version() ) {
		deactivate_plugins( TIKH_PLUGIN_BASENAME );
		wp_die(
			esc_html__( 'Türkiye İK Hesaplama Araçları requires PHP 7.4+ and WordPress 5.8+.', 'turkiye-ik-hesaplama' ),
			'Plugin Activation Error',
			array( 'back_link' => true )
		);
	}

	// Set activation flag.
	add_option( 'tikh_activated', true );

	// Flush rewrite rules.
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'tikh_activate' );

/**
 * Plugin deactivation hook.
 *
 * @return void
 */
function tikh_deactivate() {
	// Flush rewrite rules.
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'tikh_deactivate' );
