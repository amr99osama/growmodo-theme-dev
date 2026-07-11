<?php
/**
 * Blog post card.
 *
 * @param array $args { @type int $post_id }
 * @package realestate
 */

$re_id = $args['post_id'] ?? get_the_ID();
?>
<article class="post-card surface">
	<a class="post-card__media" href="<?php echo esc_url( get_permalink( $re_id ) ); ?>">
		<img src="<?php echo esc_url( re_thumb_url( $re_id, 're_card' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $re_id ) ); ?>" loading="lazy">
	</a>
	<div class="post-card__body">
		<div class="post-card__meta">
			<?php
			$re_cats = get_the_category( $re_id );
			if ( $re_cats ) :
				?>
				<span class="post-card__cat"><?php echo esc_html( $re_cats[0]->name ); ?></span>
			<?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c', $re_id ) ); ?>"><?php echo esc_html( get_the_date( '', $re_id ) ); ?></time>
		</div>
		<h3 class="post-card__title">
			<a href="<?php echo esc_url( get_permalink( $re_id ) ); ?>"><?php echo esc_html( get_the_title( $re_id ) ); ?></a>
		</h3>
		<p class="post-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt( $re_id ), 18 ) ); ?></p>
	</div>
</article>
