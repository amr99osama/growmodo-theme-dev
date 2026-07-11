<?php
/**
 * Lightweight breadcrumbs (no plugin dependency).
 *
 * @package realestate
 */

$re_sep   = '<span class="breadcrumbs__sep" aria-hidden="true">/</span>';
$re_items = array( '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'realestate' ) . '</a>' );

if ( is_singular( 'property' ) ) {
	$re_items[] = '<a href="' . esc_url( get_post_type_archive_link( 'property' ) ) . '">' . esc_html__( 'Properties', 'realestate' ) . '</a>';
	$re_items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
} elseif ( is_singular( 'agent' ) ) {
	$re_items[] = '<a href="' . esc_url( get_post_type_archive_link( 'agent' ) ) . '">' . esc_html__( 'Agents', 'realestate' ) . '</a>';
	$re_items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
} elseif ( is_post_type_archive() ) {
	$re_items[] = '<span aria-current="page">' . esc_html( post_type_archive_title( '', false ) ) . '</span>';
} elseif ( is_tax() || is_category() || is_tag() ) {
	$re_items[] = '<span aria-current="page">' . esc_html( single_term_title( '', false ) ) . '</span>';
} elseif ( is_singular() ) {
	$re_items[] = '<span aria-current="page">' . esc_html( get_the_title() ) . '</span>';
} elseif ( is_search() ) {
	$re_items[] = '<span aria-current="page">' . esc_html__( 'Search', 'realestate' ) . '</span>';
}
?>
<nav class="breadcrumbs" aria-label="<?php esc_attr_e( 'Breadcrumb', 'realestate' ); ?>">
	<?php echo implode( ' ' . $re_sep . ' ', $re_items ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- items escaped above. ?>
</nav>
