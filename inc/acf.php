<?php
/**
 * ACF integration: Local JSON sync + a free-compatible global settings store.
 *
 * NOTE: ACF *free* (6.8.x) does NOT provide Options Pages (acf_add_options_page
 * is Pro-only). Instead we use the standard free pattern: a dedicated
 * "Site Settings" Page using the `page-site-settings.php` template, with the
 * global field group assigned to that template. re_option() reads from it.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RE_SETTINGS_TEMPLATE = 'page-site-settings.php';

/**
 * Save field group JSON into the theme's acf-json/ directory.
 *
 * @return string
 */
function re_acf_json_save_point(): string {
	$dir = RE_DIR . '/acf-json';
	if ( ! is_dir( $dir ) ) {
		wp_mkdir_p( $dir );
	}
	return $dir;
}
add_filter( 'acf/settings/save_json', 're_acf_json_save_point' );

/**
 * Load field groups from the theme's acf-json/ directory.
 *
 * @param array<int,string> $paths Existing load paths.
 * @return array<int,string>
 */
function re_acf_json_load_point( array $paths ): array {
	$paths[] = RE_DIR . '/acf-json';
	return $paths;
}
add_filter( 'acf/settings/load_json', 're_acf_json_load_point' );

/**
 * Ensure the "Site Settings" container page exists; return its ID.
 * Idempotent — creates the page once and caches its ID in an option.
 *
 * @return int Page ID, or 0 on failure.
 */
function re_ensure_settings_page(): int {
	$stored = (int) get_option( 're_settings_page_id' );
	if ( $stored && 'page' === get_post_type( $stored ) ) {
		return $stored;
	}

	$id = wp_insert_post( array(
		'post_title'  => __( 'Site Settings', 'realestate' ),
		'post_name'   => 're-site-settings',
		'post_type'   => 'page',
		'post_status' => 'publish',
		'meta_input'  => array( '_wp_page_template' => RE_SETTINGS_TEMPLATE ),
	) );

	if ( $id && ! is_wp_error( $id ) ) {
		update_option( 're_settings_page_id', (int) $id );
		return (int) $id;
	}
	return 0;
}
add_action( 'after_switch_theme', 're_ensure_settings_page' );

/**
 * Lazily create the settings page in admin if it is missing.
 */
function re_settings_page_admin_guard(): void {
	if ( ! get_option( 're_settings_page_id' ) ) {
		re_ensure_settings_page();
	}
}
add_action( 'admin_init', 're_settings_page_admin_guard' );

/**
 * Get the Site Settings page ID (0 if not yet created).
 *
 * @return int
 */
function re_settings_page_id(): int {
	return (int) get_option( 're_settings_page_id' );
}

/**
 * Admin notice if ACF is not active — the content model depends on it.
 */
function re_acf_missing_notice(): void {
	if ( function_exists( 'get_field' ) ) {
		return;
	}
	echo '<div class="notice notice-error"><p><strong>Real Estate theme:</strong> ';
	echo esc_html__( 'Advanced Custom Fields (free) 6.2+ is required. Please install and activate it.', 'realestate' );
	echo '</p></div>';
}
add_action( 'admin_notices', 're_acf_missing_notice' );
