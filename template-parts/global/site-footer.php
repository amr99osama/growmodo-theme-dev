<?php
/**
 * Global site footer (Figma Estatein): brand + email signup, link columns, bottom bar.
 *
 * @package realestate
 */

$re_social = (array) re_option( 'social_links' );
$re_copy   = re_option( 'footer_copyright' );
$re_copy   = $re_copy ? str_replace( '{year}', gmdate( 'Y' ), $re_copy ) : sprintf( '@%s Estatein. All Rights Reserved.', gmdate( 'Y' ) );

$re_cols = array(
	array( 'Home', array( 'Hero Section', 'Features', 'Properties', 'Testimonials', "FAQ's" ) ),
	array( 'About Us', array( 'Our Story', 'Our Works', 'How It Works', 'Our Team', 'Our Clients' ) ),
	array( 'Properties', array( 'Portfolio', 'Categories' ) ),
	array( 'Services', array( 'Valuation Mastery', 'Strategic Marketing', 'Negotiation Wizardry', 'Closing Success', 'Property Management' ) ),
	array( 'Contact Us', array( 'Contact Form', 'Our Offices' ) ),
);
$re_socials = array(
	'facebook' => $re_social['facebook'] ?? '#',
	'linkedin' => $re_social['linkedin'] ?? '#',
	'x'        => $re_social['x'] ?? '#',
	'youtube'  => $re_social['youtube'] ?? '#',
);
?>
<footer class="site-footer" role="contentinfo">
	<div class="site-footer__top">
		<div class="container site-footer__top-inner">
			<div class="site-footer__brand">
				<a class="site-footer__logo site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Estatein">
					<?php echo re_logo_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG. ?>
					<span class="site-logo__text">Estatein</span>
				</a>
				<form class="site-footer__subscribe" method="post" action="#">
					<span class="site-footer__subscribe-icon"><?php re_icon( 'mail' ); ?></span>
					<input type="email" name="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'realestate' ); ?>" aria-label="<?php esc_attr_e( 'Email address', 'realestate' ); ?>">
					<button type="submit" class="site-footer__subscribe-btn" aria-label="<?php esc_attr_e( 'Subscribe', 'realestate' ); ?>"><?php re_icon( 'arrow-right' ); ?></button>
				</form>
			</div>

			<nav class="site-footer__cols" aria-label="<?php esc_attr_e( 'Footer', 'realestate' ); ?>">
				<?php foreach ( $re_cols as $re_col ) : ?>
					<div class="site-footer__col">
						<h3 class="site-footer__col-title"><?php echo esc_html( $re_col[0] ); ?></h3>
						<ul>
							<?php foreach ( $re_col[1] as $re_label ) : ?>
								<li><a href="#"><?php echo esc_html( $re_label ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endforeach; ?>
			</nav>
		</div>
	</div>

	<div class="site-footer__bar">
		<div class="container site-footer__bar-inner">
			<div class="site-footer__legal">
				<span><?php echo esc_html( $re_copy ); ?></span>
				<a href="#"><?php esc_html_e( 'Terms &amp; Conditions', 'realestate' ); ?></a>
			</div>
			<div class="site-footer__social">
				<?php foreach ( $re_socials as $re_key => $re_url ) : ?>
					<a class="site-footer__social-btn" href="<?php echo esc_url( $re_url ); ?>" aria-label="<?php echo esc_attr( ucfirst( $re_key ) ); ?>"<?php echo '#' !== $re_url ? ' target="_blank" rel="noopener"' : ''; ?>>
						<?php re_icon( $re_key ); ?>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</footer>
