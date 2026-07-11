<?php
/**
 * About / Why-us section.
 *
 * @package realestate
 */

$re_heading  = re_field( 'about_heading' );
$re_body     = re_field( 'about_body' );
$re_image    = re_field( 'about_image' );
$re_features = (array) re_field( 'about_features' );

if ( ! $re_heading && ! $re_body && empty( $re_image['url'] ) ) {
	return;
}
?>
<section class="section about">
	<div class="container about__inner">
		<?php if ( ! empty( $re_image['url'] ) ) : ?>
			<div class="about__media">
				<img src="<?php echo esc_url( $re_image['url'] ); ?>" alt="<?php echo esc_attr( $re_image['alt'] ?? '' ); ?>" loading="lazy">
			</div>
		<?php endif; ?>

		<div class="about__content">
			<?php if ( re_field( 'about_eyebrow' ) ) : ?>
				<span class="eyebrow"><?php echo esc_html( re_field( 'about_eyebrow' ) ); ?></span>
			<?php endif; ?>
			<?php if ( $re_heading ) : ?>
				<h2 class="about__title"><?php echo esc_html( $re_heading ); ?></h2>
			<?php endif; ?>
			<?php if ( $re_body ) : ?>
				<div class="about__body"><?php echo wp_kses_post( $re_body ); ?></div>
			<?php endif; ?>

			<?php if ( array_filter( $re_features ) ) : ?>
				<ul class="about__features" role="list">
					<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
						<?php if ( ! empty( $re_features[ "feature_{$i}_title" ] ) ) : ?>
							<li class="about__feature">
								<span class="about__feature-icon"><?php re_icon( 'check' ); ?></span>
								<div>
									<h3 class="about__feature-title"><?php echo esc_html( $re_features[ "feature_{$i}_title" ] ); ?></h3>
									<?php if ( ! empty( $re_features[ "feature_{$i}_text" ] ) ) : ?>
										<p><?php echo esc_html( $re_features[ "feature_{$i}_text" ] ); ?></p>
									<?php endif; ?>
								</div>
							</li>
						<?php endif; ?>
					<?php endfor; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</section>
