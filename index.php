<?php
/**
 * Fallback template. Real routing is handled by the specific templates
 * (front-page.php, archive-property.php, single-property.php, etc.).
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main container">
	<?php if ( have_posts() ) : ?>
		<div class="post-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/cards/post-card' );
			endwhile;
			?>
		</div>
		<?php get_template_part( 'template-parts/components/pagination' ); ?>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'realestate' ); ?></p>
	<?php endif; ?>
</main>
<?php
get_footer();
