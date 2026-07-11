<?php
/**
 * Property taxonomy archives (property_type, property_location, property_status).
 * Reuses the property archive layout.
 *
 * @package realestate
 */

get_header();
$re_term = get_queried_object();
?>
<main id="main" class="site-main">
	<div class="page-hero">
		<div class="container">
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
			<h1 class="page-hero__title"><?php single_term_title(); ?></h1>
			<?php if ( ! empty( $re_term->description ) ) : ?>
				<p class="text-muted"><?php echo esc_html( $re_term->description ); ?></p>
			<?php endif; ?>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<?php get_template_part( 'template-parts/global/archive-filters' ); ?>
			<?php get_template_part( 'template-parts/global/property-results' ); ?>
		</div>
	</div>
</main>
<?php
get_footer();
