<?php
/**
 * Agent card.
 *
 * @param array $args { @type int $post_id }
 * @package realestate
 */

$re_id = $args['post_id'] ?? get_the_ID();
if ( ! $re_id ) {
	return;
}

$re_role   = re_field( 'role', $re_id );
$re_social = (array) re_field( 'social_links', $re_id );
$re_social_map = array( 'facebook' => 'facebook', 'instagram' => 'instagram', 'linkedin' => 'linkedin', 'x' => 'x' );
?>
<article class="agent-card surface">
	<a class="agent-card__media" href="<?php echo esc_url( get_permalink( $re_id ) ); ?>">
		<img src="<?php echo esc_url( re_thumb_url( $re_id, 're_card' ) ); ?>" alt="<?php echo esc_attr( get_the_title( $re_id ) ); ?>" loading="lazy">
	</a>
	<div class="agent-card__body">
		<h3 class="agent-card__name">
			<a href="<?php echo esc_url( get_permalink( $re_id ) ); ?>"><?php echo esc_html( get_the_title( $re_id ) ); ?></a>
		</h3>
		<?php if ( $re_role ) : ?>
			<p class="agent-card__role"><?php echo esc_html( $re_role ); ?></p>
		<?php endif; ?>

		<?php if ( array_filter( $re_social ) ) : ?>
			<div class="social-links agent-card__social">
				<?php foreach ( $re_social_map as $re_key => $re_icon ) : ?>
					<?php if ( ! empty( $re_social[ $re_key ] ) ) : ?>
						<a href="<?php echo esc_url( $re_social[ $re_key ] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $re_key ) ); ?>"><?php re_icon( $re_icon ); ?></a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
