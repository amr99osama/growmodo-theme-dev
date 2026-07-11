<?php
/**
 * Single agent.
 *
 * @package realestate
 */

get_header();
while ( have_posts() ) :
	the_post();
	$re_id     = get_the_ID();
	$re_role   = re_field( 'role', $re_id );
	$re_phone  = re_field( 'phone', $re_id );
	$re_email  = re_field( 'email', $re_id );
	$re_wa     = re_field( 'whatsapp', $re_id );
	$re_bio    = re_field( 'bio', $re_id );
	$re_social = (array) re_field( 'social_links', $re_id );
	$re_social_map = array( 'facebook' => 'facebook', 'instagram' => 'instagram', 'linkedin' => 'linkedin', 'x' => 'x' );

	$re_listings = new WP_Query( array(
		'post_type'      => 'property',
		'posts_per_page' => 6,
		'no_found_rows'  => true,
		'meta_query'     => array(
			array( 'key' => 'agent', 'value' => $re_id, 'compare' => '=' ),
		),
	) );
	?>
	<main id="main" class="site-main single-agent">
		<div class="container">
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

			<div class="single-agent__header">
				<div class="single-agent__media">
					<img src="<?php echo esc_url( re_thumb_url( $re_id, 're_card' ) ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
				</div>
				<div class="single-agent__intro">
					<h1 class="single-agent__name"><?php the_title(); ?></h1>
					<?php if ( $re_role ) : ?><p class="single-agent__role"><?php echo esc_html( $re_role ); ?></p><?php endif; ?>
					<?php if ( $re_bio ) : ?><div class="single-agent__bio"><?php echo wp_kses_post( $re_bio ); ?></div><?php endif; ?>

					<div class="single-agent__actions">
						<?php if ( $re_phone ) : ?><a class="btn btn--outline" href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $re_phone ) ); ?>"><?php re_icon( 'phone' ); ?><?php echo esc_html( $re_phone ); ?></a><?php endif; ?>
						<?php if ( $re_email ) : ?><a class="btn btn--primary" href="mailto:<?php echo esc_attr( $re_email ); ?>"><?php re_icon( 'mail' ); ?><?php esc_html_e( 'Email', 'realestate' ); ?></a><?php endif; ?>
						<?php if ( $re_wa ) : ?><a class="btn btn--outline" target="_blank" rel="noopener" href="https://wa.me/<?php echo esc_attr( preg_replace( '/[^0-9]/', '', $re_wa ) ); ?>"><?php re_icon( 'whatsapp' ); ?><?php esc_html_e( 'WhatsApp', 'realestate' ); ?></a><?php endif; ?>
					</div>

					<?php if ( array_filter( $re_social ) ) : ?>
						<div class="social-links">
							<?php foreach ( $re_social_map as $re_key => $re_icon ) : ?>
								<?php if ( ! empty( $re_social[ $re_key ] ) ) : ?>
									<a href="<?php echo esc_url( $re_social[ $re_key ] ); ?>" target="_blank" rel="noopener" aria-label="<?php echo esc_attr( ucfirst( $re_key ) ); ?>"><?php re_icon( $re_icon ); ?></a>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>

			<?php if ( $re_listings->have_posts() ) : ?>
				<section class="single-agent__listings section">
					<h2><?php esc_html_e( 'Listings by this agent', 'realestate' ); ?></h2>
					<div class="grid grid--cards">
						<?php
						while ( $re_listings->have_posts() ) :
							$re_listings->the_post();
							re_card( 'property-card', array( 'post_id' => get_the_ID() ) );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
