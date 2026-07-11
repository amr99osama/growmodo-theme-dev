<?php
/**
 * Property archive filter bar (GET form).
 *
 * @package realestate
 */

$re_action = get_post_type_archive_link( 'property' );
$re_get     = function ( $k ) { return isset( $_GET[ $k ] ) ? sanitize_text_field( wp_unslash( $_GET[ $k ] ) ) : ''; }; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<form class="archive-filters surface" method="get" action="<?php echo esc_url( $re_action ); ?>">
	<div class="archive-filters__field">
		<label class="screen-reader-text" for="f-type"><?php esc_html_e( 'Type', 'realestate' ); ?></label>
		<?php
		wp_dropdown_categories( array(
			'taxonomy'        => 'property_type',
			'name'            => 'property_type',
			'id'              => 'f-type',
			'show_option_all' => __( 'All Types', 'realestate' ),
			'value_field'     => 'slug',
			'selected'        => get_query_var( 'property_type' ),
			'hide_empty'      => false,
		) );
		?>
	</div>
	<div class="archive-filters__field">
		<label class="screen-reader-text" for="f-loc"><?php esc_html_e( 'Location', 'realestate' ); ?></label>
		<?php
		wp_dropdown_categories( array(
			'taxonomy'        => 'property_location',
			'name'            => 'property_location',
			'id'              => 'f-loc',
			'show_option_all' => __( 'All Locations', 'realestate' ),
			'value_field'     => 'slug',
			'selected'        => get_query_var( 'property_location' ),
			'hide_empty'      => false,
		) );
		?>
	</div>
	<div class="archive-filters__field">
		<label class="screen-reader-text" for="f-status"><?php esc_html_e( 'Status', 'realestate' ); ?></label>
		<?php
		wp_dropdown_categories( array(
			'taxonomy'        => 'property_status',
			'name'            => 'property_status',
			'id'              => 'f-status',
			'show_option_all' => __( 'Any Status', 'realestate' ),
			'value_field'     => 'slug',
			'selected'        => get_query_var( 'property_status' ),
			'hide_empty'      => false,
		) );
		?>
	</div>
	<div class="archive-filters__field">
		<label class="screen-reader-text" for="f-beds"><?php esc_html_e( 'Beds', 'realestate' ); ?></label>
		<select name="beds" id="f-beds">
			<option value=""><?php esc_html_e( 'Beds', 'realestate' ); ?></option>
			<?php foreach ( array( 1, 2, 3, 4, 5 ) as $re_b ) : ?>
				<option value="<?php echo esc_attr( $re_b ); ?>" <?php selected( $re_get( 'beds' ), (string) $re_b ); ?>><?php echo esc_html( $re_b ); ?>+</option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="archive-filters__field">
		<label class="screen-reader-text" for="f-max"><?php esc_html_e( 'Max price', 'realestate' ); ?></label>
		<input type="number" name="max_price" id="f-max" min="0" step="1000" placeholder="<?php esc_attr_e( 'Max price', 'realestate' ); ?>" value="<?php echo esc_attr( $re_get( 'max_price' ) ); ?>">
	</div>
	<button class="btn btn--primary" type="submit"><?php re_icon( 'search' ); ?><span><?php esc_html_e( 'Filter', 'realestate' ); ?></span></button>
</form>
