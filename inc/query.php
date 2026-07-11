<?php
/**
 * Front-end query tuning for the property archive & taxonomy views.
 * Applies price/bedroom filters (from GET) and archive page size.
 * Type/location/status filtering is handled natively via the public
 * taxonomy query vars (?property_type=slug, etc.).
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Is this main query a property listing view?
 *
 * @param WP_Query $q Query.
 * @return bool
 */
function re_is_property_query( WP_Query $q ): bool {
	return $q->is_post_type_archive( 'property' )
		|| $q->is_tax( array( 'property_type', 'property_location', 'property_status' ) );
}

/**
 * Apply archive size + meta filters to the property listing.
 *
 * @param WP_Query $q Query.
 */
function re_filter_property_query( WP_Query $q ): void {
	if ( is_admin() || ! $q->is_main_query() || ! re_is_property_query( $q ) ) {
		return;
	}

	$q->set( 'posts_per_page', 9 );

	$meta = array();

	$min = isset( $_GET['min_price'] ) ? (float) $_GET['min_price'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$max = isset( $_GET['max_price'] ) ? (float) $_GET['max_price'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $min > 0 ) {
		$meta[] = array( 'key' => 'price', 'value' => $min, 'type' => 'NUMERIC', 'compare' => '>=' );
	}
	if ( $max > 0 ) {
		$meta[] = array( 'key' => 'price', 'value' => $max, 'type' => 'NUMERIC', 'compare' => '<=' );
	}

	$beds = isset( $_GET['beds'] ) ? (int) $_GET['beds'] : 0; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( $beds > 0 ) {
		$meta[] = array( 'key' => 'bedrooms', 'value' => $beds, 'type' => 'NUMERIC', 'compare' => '>=' );
	}

	if ( $meta ) {
		if ( count( $meta ) > 1 ) {
			$meta['relation'] = 'AND';
		}
		$q->set( 'meta_query', $meta );
	}

	// Optional sort by price.
	$orderby = isset( $_GET['orderby'] ) ? sanitize_key( wp_unslash( $_GET['orderby'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( 'price_asc' === $orderby ) {
		$q->set( 'meta_key', 'price' );
		$q->set( 'orderby', 'meta_value_num' );
		$q->set( 'order', 'ASC' );
	} elseif ( 'price_desc' === $orderby ) {
		$q->set( 'meta_key', 'price' );
		$q->set( 'orderby', 'meta_value_num' );
		$q->set( 'order', 'DESC' );
	}
}
add_action( 'pre_get_posts', 're_filter_property_query' );
