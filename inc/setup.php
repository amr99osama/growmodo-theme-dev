<?php
/**
 * Theme setup: supports, menus, image sizes.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme supports and navigation menus.
 */
function re_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'custom-logo', array(
		'height'      => 48,
		'width'       => 180,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	) );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'realestate' ),
		'footer'  => __( 'Footer Menu', 'realestate' ),
	) );

	// Image sizes — dimensions are PLACEHOLDERS; align to Figma card/hero sizes.
	add_image_size( 're_card', 640, 460, true );      // property/agent/post card
	add_image_size( 're_card_wide', 800, 500, true ); // featured / list wide card
	add_image_size( 're_hero', 1600, 1000, false );   // hero / cover
}
add_action( 'after_setup_theme', 're_setup' );

/**
 * Content width for embeds.
 */
function re_content_width(): void {
	$GLOBALS['content_width'] = 1240;
}
add_action( 'after_setup_theme', 're_content_width', 0 );

/**
 * Add readable names for the theme image sizes in the media picker.
 *
 * @param array<string,string> $sizes Existing size labels.
 * @return array<string,string>
 */
function re_image_size_names( array $sizes ): array {
	return array_merge( $sizes, array(
		're_card'      => __( 'Card', 'realestate' ),
		're_card_wide' => __( 'Card (wide)', 'realestate' ),
		're_hero'      => __( 'Hero', 'realestate' ),
	) );
}
add_filter( 'image_size_names_choose', 're_image_size_names' );

/**
 * Allow administrators to upload SVG icons for editable design icon fields.
 *
 * @param array<string,string> $mimes Allowed mime types.
 * @return array<string,string>
 */
function re_allow_admin_svg_uploads( array $mimes ): array {
	if ( current_user_can( 'manage_options' ) ) {
		$mimes['svg'] = 'image/svg+xml';
	}
	return $mimes;
}
add_filter( 'upload_mimes', 're_allow_admin_svg_uploads' );
