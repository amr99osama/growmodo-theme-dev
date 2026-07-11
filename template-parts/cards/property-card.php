<?php
/**
 * Property card (Figma Featured Properties layout).
 *
 * @param array $args { @type int $post_id }
 * @package realestate
 */

$re_id = $args['post_id'] ?? get_the_ID();
if ( ! $re_id ) {
	return;
}

$re_price   = re_price( re_field( 'price', $re_id ) );
$re_beds    = (int) re_field( 'bedrooms', $re_id );
$re_baths   = (int) re_field( 'bathrooms', $re_id );
$re_link    = get_permalink( $re_id );
$re_terms   = get_the_terms( $re_id, 'property_type' );
$re_type    = ( $re_terms && ! is_wp_error( $re_terms ) ) ? $re_terms[0]->name : '';
$re_excerpt = has_excerpt( $re_id )
	? get_the_excerpt( $re_id )
	: wp_trim_words( wp_strip_all_tags( (string) get_post_field( 'post_content', $re_id ) ), 16, '…' );
?>
<article class="property-card">
	<a class="property-card__media" href="<?php echo esc_url( $re_link ); ?>" tabindex="-1" aria-hidden="true">
		<img src="<?php echo esc_url( re_thumb_url( $re_id, 're_card' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $re_id ) ); ?>" loading="lazy" decoding="async" width="600" height="380">
	</a>
	<div class="property-card__body">
		<div class="property-card__text">
			<h3 class="property-card__title"><a href="<?php echo esc_url( $re_link ); ?>"><?php echo esc_html( get_the_title( $re_id ) ); ?></a></h3>
			<p class="property-card__desc"><?php echo esc_html( $re_excerpt ); ?> <a class="property-card__more" href="<?php echo esc_url( $re_link ); ?>"><?php esc_html_e( 'Read More', 'realestate' ); ?></a></p>
		</div>

		<?php if ( $re_beds || $re_baths || $re_type ) : ?>
			<ul class="property-card__tags" role="list">
				<?php if ( $re_beds ) : ?><li class="property-card__tag"><?php re_icon( 'bed' ); ?><span><?php printf( esc_html__( '%d-Bedroom', 'realestate' ), $re_beds ); ?></span></li><?php endif; ?>
				<?php if ( $re_baths ) : ?><li class="property-card__tag"><?php re_icon( 'bath' ); ?><span><?php printf( esc_html__( '%d-Bathroom', 'realestate' ), $re_baths ); ?></span></li><?php endif; ?>
				<?php if ( $re_type ) : ?><li class="property-card__tag"><?php re_icon( 'home' ); ?><span><?php echo esc_html( $re_type ); ?></span></li><?php endif; ?>
			</ul>
		<?php endif; ?>

		<div class="property-card__foot">
			<div class="property-card__price">
				<span class="property-card__price-label"><?php esc_html_e( 'Price', 'realestate' ); ?></span>
				<span class="property-card__price-value"><?php echo esc_html( $re_price ?: '—' ); ?></span>
			</div>
			<a class="btn btn--primary property-card__cta" href="<?php echo esc_url( $re_link ); ?>"><?php esc_html_e( 'View Property Details', 'realestate' ); ?></a>
		</div>
	</div>
</article>
