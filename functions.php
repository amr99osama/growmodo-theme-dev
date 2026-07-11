<?php
/**
 * Real Estate theme bootstrap.
 *
 * Loads the inc/ modules in dependency order. Each module is a focused unit:
 * setup (theme supports), enqueue (Vite manifest assets), acf (Local JSON + options),
 * cpt (custom post types), taxonomy, helpers (render utilities), icons (inline SVG).
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'RE_DIR', get_template_directory() );
define( 'RE_URI', get_template_directory_uri() );
define( 'RE_VER', '1.1.0' );

foreach ( array( 'setup', 'enqueue', 'acf', 'cpt', 'taxonomy', 'query', 'helpers', 'icons', 'seo', 'contact', 'seed' ) as $re_module ) {
	$re_file = RE_DIR . "/inc/{$re_module}.php";
	if ( is_readable( $re_file ) ) {
		require_once $re_file;
	}
}
unset( $re_module, $re_file );
