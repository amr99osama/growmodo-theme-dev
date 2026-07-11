<?php
/**
 * Property archive (listings).
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="page-hero">
		<div class="container">
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
			<h1 class="page-hero__title"><?php post_type_archive_title(); ?></h1>
			<p class="text-muted"><?php esc_html_e( 'Browse our latest listings and find your next home.', 'realestate' ); ?></p>
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
