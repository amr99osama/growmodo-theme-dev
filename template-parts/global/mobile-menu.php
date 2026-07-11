<?php
/**
 * Mobile menu — full-screen off-canvas overlay with its own logo + close button
 * (toggled by nav.js; open-state class lives on <body>).
 *
 * @package realestate
 */

$re_cta     = re_option( 'header_cta' );
$re_cta_url = ! empty( $re_cta['url'] ) ? $re_cta['url'] : home_url( '/contact/' );
$re_cta_lbl = ! empty( $re_cta['title'] ) ? $re_cta['title'] : __( 'Contact Us', 'realestate' );
?>
<div class="mobile-menu" id="mobile-menu" data-mobile-menu>
	<div class="mobile-menu__bar">
		<div class="container mobile-menu__bar-inner">
			<a class="site-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" aria-label="Estatein">
				<?php echo re_logo_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG. ?>
				<span class="site-logo__text">Estatein</span>
			</a>
			<button class="mobile-menu__close" type="button" data-menu-close aria-label="<?php esc_attr_e( 'Close menu', 'realestate' ); ?>">
				<?php re_icon( 'x' ); ?>
			</button>
		</div>
	</div>
	<nav class="mobile-menu__nav container" aria-label="<?php esc_attr_e( 'Mobile', 'realestate' ); ?>">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'container'      => false,
				'menu_class'     => 'mobile-menu__list',
				'depth'          => 2,
				'fallback_cb'    => 're_nav_fallback',
			)
		);
		?>
		<a class="btn btn--primary btn--block" href="<?php echo esc_url( $re_cta_url ); ?>">
			<?php echo esc_html( $re_cta_lbl ); ?>
		</a>
	</nav>
</div>
