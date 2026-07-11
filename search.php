<?php
/**
 * Search results (properties + posts).
 *
 * @package realestate
 */

get_header();
?>
<main id="main" class="site-main">
	<div class="page-hero">
		<div class="container">
			<h1 class="page-hero__title">
				<?php printf( esc_html__( 'Search: %s', 'realestate' ), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?>
			</h1>
			<?php get_search_form(); ?>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<p class="archive-results__count">
					<?php
					global $wp_query;
					printf( esc_html( _n( '%s result', '%s results', (int) $wp_query->found_posts, 'realestate' ) ), esc_html( number_format_i18n( (int) $wp_query->found_posts ) ) );
					?>
				</p>
				<div class="grid grid--cards">
					<?php
					while ( have_posts() ) :
						the_post();
						if ( 'property' === get_post_type() ) {
							re_card( 'property-card', array( 'post_id' => get_the_ID() ) );
						} else {
							get_template_part( 'template-parts/cards/post-card', null, array( 'post_id' => get_the_ID() ) );
						}
					endwhile;
					?>
				</div>
				<?php get_template_part( 'template-parts/components/pagination' ); ?>
			<?php else : ?>
				<div class="archive-empty surface">
					<p><?php esc_html_e( 'No results found. Try a different search.', 'realestate' ); ?></p>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php
get_footer();
