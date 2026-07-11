<?php
/**
 * Global site header: promo strip + navigation bar (logo, primary nav, CTA, mobile toggle).
 *
 * @package realestate
 */

$re_logo    = re_option( 'site_logo' );
$re_cta     = re_option( 'header_cta' );
$re_cta_url = ! empty( $re_cta['url'] ) ? $re_cta['url'] : home_url( '/contact/' );
$re_cta_lbl = ! empty( $re_cta['title'] ) ? $re_cta['title'] : __( 'Contact Us', 'realestate' );
?>
<header class="site-header" data-site-header>
	<div class="site-header__promo" data-promo>
		<div class="container site-header__promo-inner">
			<p class="site-header__promo-text">
				<span class="site-header__promo-spark" aria-hidden="true">&#10024;</span><?php esc_html_e( 'Discover Your Dream Property with Estatein', 'realestate' ); ?>
			</p>
			<a class="site-header__promo-link" href="<?php echo esc_url( home_url( '/properties/' ) ); ?>"><?php esc_html_e( 'Learn More', 'realestate' ); ?></a>
			<button class="site-header__promo-close" type="button" data-promo-close aria-label="<?php esc_attr_e( 'Dismiss announcement', 'realestate' ); ?>">
				<?php re_icon( 'x', 'site-header__promo-close-icon' ); ?>
			</button>
		</div>
	</div>

	<div class="site-header__bar">
		<div class="container site-header__inner">
			<a class="site-header__logo site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Estatein">
				<?php
				if ( ! empty( $re_logo['url'] ) ) {
					printf( '<img src="%1$s" alt="%2$s" width="160" height="38">', esc_url( $re_logo['url'] ), esc_attr( $re_logo['alt'] ?: 'Estatein' ) );
				} else {
					echo re_logo_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG.
					echo '<span class="site-logo__text">Estatein</span>';
				}
				?>
			</a>

			<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'realestate' ); ?>">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'primary-nav__list',
						'depth'          => 2,
						'fallback_cb'    => 're_nav_fallback',
					)
				);
				?>
			</nav>

			<div class="header-actions">
				<a class="btn btn--dark header-actions__cta" href="<?php echo esc_url( $re_cta_url ); ?>"<?php echo ! empty( $re_cta['target'] ) ? ' target="' . esc_attr( $re_cta['target'] ) . '" rel="noopener"' : ''; ?>>
					<?php echo esc_html( $re_cta_lbl ); ?>
				</a>
				<button class="menu-toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="mobile-menu" aria-label="<?php esc_attr_e( 'Toggle menu', 'realestate' ); ?>">
					<span></span><span></span><span></span>
				</button>
			</div>
		</div>
	</div>

</header>

<?php
// Mobile menu overlay lives OUTSIDE the sticky header so its off-screen
// (translateX(100%)) closed state cannot stretch the header past the viewport.
get_template_part( 'template-parts/global/mobile-menu' );
?>
