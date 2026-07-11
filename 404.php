<?php
/**
 * 404 not found.
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main error-404">
	<div class="container container--narrow error-404__inner">
		<span class="error-404__code">404</span>
		<h1 class="error-404__title"><?php esc_html_e( 'Page not found', 'realestate' ); ?></h1>
		<p class="text-muted"><?php esc_html_e( 'The page you are looking for doesn’t exist or has been moved.', 'realestate' ); ?></p>
		<?php get_search_form(); ?>
		<div class="error-404__actions">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'realestate' ); ?></a>
			<a class="btn btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Browse properties', 'realestate' ); ?></a>
		</div>
	</div>
</main>
<?php
get_footer();
