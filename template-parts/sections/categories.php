<?php
/**
 * Property categories (property_type terms) section.
 *
 * @package realestate
 */

$re_terms = get_terms( array(
	'taxonomy'   => 'property_type',
	'hide_empty' => false,
	'number'     => 8,
) );

if ( empty( $re_terms ) || is_wp_error( $re_terms ) ) {
	return;
}
?>
<section class="section categories">
	<div class="container">
		<?php
		get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => re_field( 'categories_eyebrow' ),
			'heading' => re_field( 'categories_heading' ) ?: __( 'Browse by Category', 'realestate' ),
			'subcopy' => re_field( 'categories_subcopy' ),
			'center'  => true,
		) );
		?>

		<div class="categories__grid grid grid--4">
			<?php foreach ( $re_terms as $re_term ) : ?>
				<a class="category-card surface" href="<?php echo esc_url( get_term_link( $re_term ) ); ?>">
					<span class="category-card__icon"><?php re_icon( 'area' ); ?></span>
					<span class="category-card__name"><?php echo esc_html( $re_term->name ); ?></span>
					<span class="category-card__count"><?php echo esc_html( sprintf( _n( '%d property', '%d properties', $re_term->count, 'realestate' ), $re_term->count ) ); ?></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
