<?php
/**
 * Blog posts index (the page assigned as "Posts page").
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="page-hero">
		<div class="container">
			<h1 class="page-hero__title"><?php echo esc_html( get_the_title( get_option( 'page_for_posts' ) ) ?: __( 'Blog', 'realestate' ) ); ?></h1>
			<p class="text-muted"><?php esc_html_e( 'Insights, guides, and market news.', 'realestate' ); ?></p>
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
				<p><?php esc_html_e( 'No posts yet.', 'realestate' ); ?></p>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
