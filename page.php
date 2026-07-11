<?php
/**
 * Default page template.
 *
 * @package realestate
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="site-main">
		<div class="page-hero">
			<div class="container">
				<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
			</div>
		</div>
		<div class="section">
			<div class="container container--narrow">
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>
			</div>
		</div>
	</main>
	<?php
endwhile;
get_footer();
