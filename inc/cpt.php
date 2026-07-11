<?php
/**
 * Custom Post Types: property, agent, testimonial.
 *
 * Repeatable collections live in CPTs (free-ACF has no Repeater/Flexible),
 * looped in templates and cards.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the theme's custom post types.
 */
function re_register_post_types(): void {
	register_post_type( 'property', array(
		'labels'       => array(
			'name'               => __( 'Properties', 'realestate' ),
			'singular_name'      => __( 'Property', 'realestate' ),
			'add_new_item'       => __( 'Add Property', 'realestate' ),
			'edit_item'          => __( 'Edit Property', 'realestate' ),
			'new_item'           => __( 'New Property', 'realestate' ),
			'view_item'          => __( 'View Property', 'realestate' ),
			'search_items'       => __( 'Search Properties', 'realestate' ),
			'not_found'          => __( 'No properties found', 'realestate' ),
			'all_items'          => __( 'All Properties', 'realestate' ),
			'menu_name'          => __( 'Properties', 'realestate' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-building',
		'menu_position'=> 5,
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite'      => array( 'slug' => 'properties', 'with_front' => false ),
		'show_in_rest' => true,
	) );

	register_post_type( 'agent', array(
		'labels'       => array(
			'name'          => __( 'Agents', 'realestate' ),
			'singular_name' => __( 'Agent', 'realestate' ),
			'add_new_item'  => __( 'Add Agent', 'realestate' ),
			'edit_item'     => __( 'Edit Agent', 'realestate' ),
			'all_items'     => __( 'All Agents', 'realestate' ),
			'menu_name'     => __( 'Agents', 'realestate' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-groups',
		'menu_position'=> 6,
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'rewrite'      => array( 'slug' => 'agents', 'with_front' => false ),
		'show_in_rest' => true,
	) );

	register_post_type( 'testimonial', array(
		'labels'       => array(
			'name'          => __( 'Testimonials', 'realestate' ),
			'singular_name' => __( 'Testimonial', 'realestate' ),
			'add_new_item'  => __( 'Add Testimonial', 'realestate' ),
			'edit_item'     => __( 'Edit Testimonial', 'realestate' ),
			'all_items'     => __( 'All Testimonials', 'realestate' ),
			'menu_name'     => __( 'Testimonials', 'realestate' ),
		),
		'public'       => false,
		'show_ui'      => true,
		'menu_icon'    => 'dashicons-format-quote',
		'menu_position'=> 7,
		'supports'     => array( 'title', 'editor', 'thumbnail' ),
		'show_in_rest' => true,
	) );

	register_post_type( 'faq', array(
		'labels'       => array(
			'name'          => __( 'FAQs', 'realestate' ),
			'singular_name' => __( 'FAQ', 'realestate' ),
			'add_new_item'  => __( 'Add FAQ', 'realestate' ),
			'edit_item'     => __( 'Edit FAQ', 'realestate' ),
			'all_items'     => __( 'All FAQs', 'realestate' ),
			'menu_name'     => __( 'FAQs', 'realestate' ),
		),
		'public'       => true,
		'has_archive'  => true,
		'menu_icon'    => 'dashicons-editor-help',
		'menu_position'=> 8,
		'supports'     => array( 'title', 'editor' ),
		'rewrite'      => array( 'slug' => 'faqs', 'with_front' => false ),
		'show_in_rest' => true,
	) );
}
add_action( 'init', 're_register_post_types' );

/**
 * Flush rewrite rules once after CPTs are registered (self-clearing).
 */
function re_maybe_flush_rewrites(): void {
	if ( get_option( 're_rewrites_flushed' ) === RE_VER ) {
		return;
	}
	re_register_post_types();
	if ( function_exists( 're_register_taxonomies' ) ) {
		re_register_taxonomies();
	}
	flush_rewrite_rules( false );
	update_option( 're_rewrites_flushed', RE_VER );
}
add_action( 'init', 're_maybe_flush_rewrites', 99 );
