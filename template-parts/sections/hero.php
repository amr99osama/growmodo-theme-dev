<?php
/**
 * Home hero: headline + subcopy + CTAs + stats, then the 4 value-prop cards.
 * Content (copy/image) comes from the "Home Page Sections" ACF group; falls
 * back to the Figma design copy so the layout renders before content is seeded.
 *
 * @package realestate
 */

$re_heading = re_field( 'hero_heading' ) ?: 'Discover Your Dream Property with Estatein';
$re_subcopy = re_field( 'hero_subcopy' ) ?: 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.';
$re_primary = re_field( 'hero_primary_btn' );
$re_second  = re_field( 'hero_secondary_btn' );
$re_image   = re_field( 'hero_image' );
$re_kpis    = (array) re_field( 'hero_kpis' );
$re_vp      = (array) re_field( 'hero_valueprops' );
$re_vp_icon = array( 'vp-home', 'vp-value', 'vp-manage', 'vp-invest' );

$re_img_url = ! empty( $re_image['url'] ) ? $re_image['url'] : RE_URI . '/assets/img/hero-building.webp';
$re_img_alt = ! empty( $re_image['alt'] ) ? $re_image['alt'] : __( 'Modern high-rise property', 'realestate' );
?>
<section class="hero">
	<div class="hero__top">
		<div class="hero__content">
			<div class="hero__text">
				<h1 class="hero__title"><?php echo esc_html( $re_heading ); ?></h1>
				<p class="hero__subcopy"><?php echo esc_html( $re_subcopy ); ?></p>
				<span class="hero__badge" aria-hidden="true">
					<svg class="hero__badge-ring" viewBox="0 0 175 175">
						<defs><path id="reHeroBadgePath" d="M87.5,87.5 m-71.8,0 a71.8,71.8 0 1,1 143.6,0 a71.8,71.8 0 1,1 -143.6,0" /></defs>
						<text><textPath href="#reHeroBadgePath">Discover Your Dream Property   Discover Your Dream Property   </textPath></text>
					</svg>
					<img class="hero__badge-spark" src="<?php echo esc_url( RE_URI . '/assets/img/hero-badge-spark.svg' ); ?>" alt="" width="24" height="24" loading="eager" decoding="async">
					<span class="hero__badge-arrow"><?php re_icon( 'arrow-up-right' ); ?></span>
				</span>
			</div>

			<div class="hero__actions">
				<?php
				re_link( ! empty( $re_second['url'] ) ? $re_second : array( 'title' => __( 'Learn More', 'realestate' ), 'url' => home_url( '/about-us/' ) ), 'btn btn--dark', __( 'Learn More', 'realestate' ) );
				re_link( ! empty( $re_primary['url'] ) ? $re_primary : array( 'title' => __( 'Browse Properties', 'realestate' ), 'url' => (string) get_post_type_archive_link( 'property' ) ), 'btn btn--primary', __( 'Browse Properties', 'realestate' ) );
				?>
			</div>

			<?php if ( array_filter( $re_kpis ) ) : ?>
				<ul class="hero__stats" role="list">
					<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
						<?php if ( ! empty( $re_kpis[ "kpi_{$i}_value" ] ) ) : ?>
							<li class="hero__stat">
								<span class="hero__stat-value"><?php echo esc_html( $re_kpis[ "kpi_{$i}_value" ] ); ?></span>
								<span class="hero__stat-label"><?php echo esc_html( $re_kpis[ "kpi_{$i}_label" ] ?? '' ); ?></span>
							</li>
						<?php endif; ?>
					<?php endfor; ?>
				</ul>
			<?php endif; ?>
		</div>

		<div class="hero__media">
			<img src="<?php echo esc_url( $re_img_url ); ?>" alt="<?php echo esc_attr( $re_img_alt ); ?>" width="1200" height="1062" fetchpriority="high" decoding="async">
		</div>
	</div>

	<div class="hero__valueprops">
		<div class="hero__vp-strip">
			<div class="hero__vp-grid">
				<?php
				for ( $i = 1; $i <= 4; $i++ ) :
					$re_vp_title = $re_vp[ "vp_{$i}_title" ] ?? '';
					if ( ! $re_vp_title ) {
						continue;
					}
					$re_vp_icon_image = $re_vp[ "vp_{$i}_icon" ] ?? array();
					$re_vp_icon_url   = '';
					$re_vp_icon_alt   = '';
					if ( is_array( $re_vp_icon_image ) && ! empty( $re_vp_icon_image['url'] ) ) {
						$re_vp_icon_url = $re_vp_icon_image['url'];
						$re_vp_icon_alt = $re_vp_icon_image['alt'] ?? '';
					} elseif ( is_numeric( $re_vp_icon_image ) ) {
						$re_vp_icon_url = (string) wp_get_attachment_image_url( (int) $re_vp_icon_image, 'thumbnail' );
						$re_vp_icon_alt = (string) get_post_meta( (int) $re_vp_icon_image, '_wp_attachment_image_alt', true );
					}
					?>
					<article class="hero__vp">
						<?php if ( $re_vp_icon_url ) : ?>
							<img class="hero__vp-icon hero__vp-icon--image" src="<?php echo esc_url( $re_vp_icon_url ); ?>" alt="<?php echo esc_attr( $re_vp_icon_alt ); ?>" loading="lazy" decoding="async" width="34" height="34">
						<?php else : ?>
							<?php echo re_fill_icon( $re_vp_icon[ $i - 1 ], 'hero__vp-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG. ?>
						<?php endif; ?>
						<h3 class="hero__vp-title"><?php echo esc_html( $re_vp_title ); ?></h3>
						<span class="hero__vp-arrow" aria-hidden="true"><?php re_icon( 'arrow-up-right' ); ?></span>
					</article>
				<?php endfor; ?>
			</div>
		</div>
	</div>
</section>
