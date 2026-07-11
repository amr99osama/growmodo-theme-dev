<?php
/**
 * Numbered pagination for archives/search.
 *
 * @package realestate
 */

$re_links = paginate_links( array(
	'type'      => 'array',
	'prev_text' => __( 'Prev', 'realestate' ),
	'next_text' => __( 'Next', 'realestate' ),
	'mid_size'  => 1,
) );

if ( empty( $re_links ) ) {
	return;
}
?>
<nav class="pagination" aria-label="<?php esc_attr_e( 'Pagination', 'realestate' ); ?>">
	<ul class="pagination__list">
		<?php foreach ( $re_links as $re_link ) : ?>
			<li><?php echo wp_kses_post( $re_link ); ?></li>
		<?php endforeach; ?>
	</ul>
</nav>
