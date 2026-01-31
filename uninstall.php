<?php
/**
 * Uninstall script.
 *
 * Fired when the plugin is uninstalled.
 *
 * @package TurkiyeIKHesaplama
 * @since   1.0.0
 */

// If uninstall not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete plugin options.
delete_option( 'tikh_activated' );
delete_option( 'tikh_version' );

// Clean up any transients.
delete_transient( 'tikh_cache' );

/**
 * Note: This plugin does not store any user data or create custom database tables.
 * If you extend this plugin to store data, add cleanup code here.
 */
