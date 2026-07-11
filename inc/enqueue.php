<?php
/**
 * Asset loading. Reads the Vite manifest (dist/.vite/manifest.json) and enqueues
 * the hashed CSS/JS. Supports an optional Vite dev-server mode for HMR.
 *
 * Dev mode: define( 'RE_VITE_DEV', true ) in wp-config.php while running
 * `npm run dev` to load assets from http://127.0.0.1:5173 with hot reload.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

const RE_VITE_DEV_URL = 'http://127.0.0.1:5173';
const RE_VITE_ENTRY   = 'src/js/main.js';

/**
 * Parse and cache the Vite manifest.
 *
 * @return array<string,array<string,mixed>>
 */
function re_vite_manifest(): array {
	static $manifest = null;
	if ( null !== $manifest ) {
		return $manifest;
	}
	$file     = RE_DIR . '/dist/.vite/manifest.json';
	$manifest = is_readable( $file )
		? (array) json_decode( (string) file_get_contents( $file ), true )
		: array();
	return $manifest;
}

/**
 * Whether the Vite dev server should be used.
 */
function re_is_vite_dev(): bool {
	return defined( 'RE_VITE_DEV' ) && RE_VITE_DEV;
}

/**
 * Enqueue theme assets (front end).
 */
function re_enqueue_assets(): void {
	if ( re_is_vite_dev() ) {
		// HMR: load the Vite client + entry as ES modules from the dev server.
		wp_enqueue_script( 're-vite-client', RE_VITE_DEV_URL . '/@vite/client', array(), null, false );
		wp_enqueue_script( 're-main', RE_VITE_DEV_URL . '/' . RE_VITE_ENTRY, array(), null, true );
		return;
	}

	$manifest = re_vite_manifest();
	$entry    = $manifest[ RE_VITE_ENTRY ] ?? null;
	if ( ! $entry || empty( $entry['file'] ) ) {
		return; // Build not run yet — run `npm run build`.
	}

	$base = RE_URI . '/dist/';

	foreach ( (array) ( $entry['css'] ?? array() ) as $css ) {
		wp_enqueue_style( 're-' . sanitize_title( $css ), $base . $css, array(), RE_VER );
	}

	wp_enqueue_script( 're-main', $base . $entry['file'], array(), RE_VER, true );
}
add_action( 'wp_enqueue_scripts', 're_enqueue_assets' );

/**
 * Mark theme scripts as ES modules (required for Vite output and dev server).
 *
 * @param string $tag    Script tag HTML.
 * @param string $handle Script handle.
 * @return string
 */
function re_script_module_type( string $tag, string $handle ): string {
	if ( in_array( $handle, array( 're-main', 're-vite-client' ), true ) ) {
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 're_script_module_type', 10, 2 );

/**
 * Preload the primary Urbanist weight (self-hosted) to cut first-paint text delay.
 * Vite fingerprints the font into dist/assets/, so resolve the hashed file at runtime.
 */
function re_preload_fonts(): void {
	static $href = null;
	if ( null === $href ) {
		$href    = '';
		$matches = glob( RE_DIR . '/dist/assets/urbanist-400*.woff2' );
		if ( $matches ) {
			$href = RE_URI . '/dist/assets/' . basename( $matches[0] );
		}
	}
	if ( $href ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( $href )
		);
	}
}
add_action( 'wp_head', 're_preload_fonts', 1 );
