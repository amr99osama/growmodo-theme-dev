<?php
/**
 * Generic archive (category, tag, date, author) for the blog.
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="page-hero">
		<div class="container">
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
			<h1 class="page-hero__title"><?php the_archive_title(); ?></h1>
			<?php the_archive_description( '<p class="text-muted">', '</p>' ); ?>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="post-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/cards/post-card', null, array( 'post_id' => get_the_ID() ) );
					endwhile;
					?>
				</div>
				<?php get_template_part( 'template-parts/components/pagination' ); ?>
			<?php else : ?>
				<p><?php esc_html_e( 'Nothing found in this archive.', 'realestate' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
