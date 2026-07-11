<?php
/**
 * Taxonomies for the property CPT: type, location, status.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register property taxonomies.
 */
function re_register_taxonomies(): void {
	register_taxonomy( 'property_type', 'property', array(
		'labels'            => array(
			'name'          => __( 'Property Types', 'realestate' ),
			'singular_name' => __( 'Property Type', 'realestate' ),
			'menu_name'     => __( 'Types', 'realestate' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'property-type', 'with_front' => false ),
	) );

	register_taxonomy( 'property_location', 'property', array(
		'labels'            => array(
			'name'          => __( 'Locations', 'realestate' ),
			'singular_name' => __( 'Location', 'realestate' ),
			'menu_name'     => __( 'Locations', 'realestate' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'location', 'with_front' => false ),
	) );

	register_taxonomy( 'property_status', 'property', array(
		'labels'            => array(
			'name'          => __( 'Statuses', 'realestate' ),
			'singular_name' => __( 'Status', 'realestate' ),
			'menu_name'     => __( 'Status', 'realestate' ),
		),
		'hierarchical'      => false,
		'public'            => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'status', 'with_front' => false ),
	) );
}
add_action( 'init', 're_register_taxonomies' );

/**
 * Seed default property_status terms (For Sale / For Rent / Sold) once.
 */
function re_seed_status_terms(): void {
	if ( get_option( 're_status_seeded' ) ) {
		return;
	}
	foreach ( array( 'For Sale', 'For Rent', 'Sold' ) as $term ) {
		if ( ! term_exists( $term, 'property_status' ) ) {
			wp_insert_term( $term, 'property_status' );
		}
	}
	update_option( 're_status_seeded', 1 );
}
add_action( 'init', 're_seed_status_terms', 20 );
