<?php
/**
 * Front page: composes the home sections. Editable content comes from the
 * "Home Page Sections" ACF group (group_re_home) assigned to the front page.
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main">
	<?php
	re_section( 'hero' );
	re_section( 'featured-properties' );
	re_section( 'testimonials' );
	re_section( 'faq' );
	re_section( 'cta' );
	// Old categories/stats/about/services/agents/blog-teaser are intentionally
	// not on the Figma home (their part files remain for other templates).
	?>
</main>
<?php
get_footer();
