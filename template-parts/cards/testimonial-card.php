<?php
/**
 * Testimonial card (Figma): star rating + title + quote + author.
 *
 * @param array $args { @type int $post_id }
 * @package realestate
 */

$re_id = $args['post_id'] ?? get_the_ID();
if ( ! $re_id ) {
	return;
}

$re_title  = get_the_title( $re_id );
$re_quote  = re_field( 'quote', $re_id ) ?: get_the_excerpt( $re_id );
$re_author = re_field( 'author_name', $re_id );
$re_role   = re_field( 'author_role', $re_id );
$re_rating = (int) ( re_field( 'rating', $re_id ) ?: 5 );
$re_avatar = re_field( 'avatar', $re_id );
$re_avatar_url = ! empty( $re_avatar['url'] )
	? $re_avatar['url']
	: ( get_the_post_thumbnail_url( $re_id, 'thumbnail' ) ?: RE_URI . '/assets/img/placeholder.svg' );
?>
<article class="testimonial-card">
	<ul class="testimonial-card__stars" role="img" aria-label="<?php echo esc_attr( sprintf( __( '%d out of 5 stars', 'realestate' ), $re_rating ) ); ?>">
		<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
			<li class="testimonial-card__star<?php echo $i <= $re_rating ? ' is-on' : ''; ?>"><?php re_icon( 'star' ); ?></li>
		<?php endfor; ?>
	</ul>

	<div class="testimonial-card__text">
		<?php if ( $re_title ) : ?>
			<h3 class="testimonial-card__title"><?php echo esc_html( $re_title ); ?></h3>
		<?php endif; ?>
		<?php if ( $re_quote ) : ?>
			<p class="testimonial-card__quote"><?php echo esc_html( $re_quote ); ?></p>
		<?php endif; ?>
	</div>

	<div class="testimonial-card__author">
		<img class="testimonial-card__avatar" src="<?php echo esc_url( $re_avatar_url ); ?>" alt="<?php echo esc_attr( $re_author ?: $re_title ); ?>" width="60" height="60" loading="lazy" decoding="async">
		<div class="testimonial-card__meta">
			<?php if ( $re_author ) : ?><span class="testimonial-card__name"><?php echo esc_html( $re_author ); ?></span><?php endif; ?>
			<?php if ( $re_role ) : ?><span class="testimonial-card__role"><?php echo esc_html( $re_role ); ?></span><?php endif; ?>
		</div>
	</div>
</article>
