<?php
/**
 * Agent archive.
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
			<p class="text-muted"><?php esc_html_e( 'Meet the team ready to help you buy, sell, or rent.', 'realestate' ); ?></p>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="grid grid--4">
					<?php
					while ( have_posts() ) :
						the_post();
						re_card( 'agent-card', array( 'post_id' => get_the_ID() ) );
					endwhile;
					?>
				</div>
				<?php get_template_part( 'template-parts/components/pagination' ); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'No agents found.', 'realestate' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
