<?php
/**
 * Single blog post.
 *
 * @package realestate
 */

get_header();
while ( have_posts() ) :
	the_post();
	?>
	<main id="main" class="site-main single-post">
		<div class="page-hero">
			<div class="container container--narrow">
				<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
				<div class="single-post__meta">
					<?php
					$re_cats = get_the_category();
					if ( $re_cats ) {
						echo '<a class="single-post__cat" href="' . esc_url( get_category_link( $re_cats[0] ) ) . '">' . esc_html( $re_cats[0]->name ) . '</a>';
					}
					?>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
				</div>
				<h1 class="page-hero__title"><?php the_title(); ?></h1>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="container container--narrow single-post__featured">
				<?php the_post_thumbnail( 're_hero', array( 'loading' => 'eager' ) ); ?>
			</div>
		<?php endif; ?>

		<div class="section">
			<div class="container container--narrow">
				<div class="entry-content">
					<?php
					the_content();
					wp_link_pages();
					?>
				</div>

				<?php if ( has_tag() ) : ?>
					<div class="single-post__tags"><?php the_tags( '', '' ); ?></div>
				<?php endif; ?>

				<?php
				if ( comments_open() || get_comments_number() ) {
					comments_template();
				}
				?>
			</div>
		</div>
	</main>
	<?php
endwhile;
get_footer();
