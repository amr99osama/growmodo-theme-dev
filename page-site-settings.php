<?php
/**
 * Template Name: Site Settings
 *
 * Container page for global theme settings (header, footer, contact, socials).
 * It only stores ACF fields — it is never shown publicly, so any front-end
 * visit redirects home.
 *
 * @package realestate
 */

if ( ! is_admin() ) {
	wp_safe_redirect( home_url( '/' ) );
	exit;
}
