<?php
/**
 * Latest blog posts teaser section.
 *
 * @package realestate
 */

$re_query = new WP_Query( array(
	'post_type'           => 'post',
	'posts_per_page'      => 3,
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
) );

if ( ! $re_query->have_posts() ) {
	return;
}
?>
<section class="section blog-teaser">
	<div class="container">
		<div class="blog-teaser__head">
			<?php
			get_template_part( 'template-parts/components/section-heading', null, array(
				'eyebrow' => re_field( 'blog_eyebrow' ),
				'heading' => re_field( 'blog_heading' ) ?: __( 'From the Blog', 'realestate' ),
				'subcopy' => re_field( 'blog_subcopy' ),
			) );
			?>
			<a class="btn btn--ghost" href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/' ) ); ?>">
				<?php esc_html_e( 'All articles', 'realestate' ); ?><?php re_icon( 'arrow-right' ); ?>
			</a>
		</div>

		<div class="grid grid--3">
			<?php
			while ( $re_query->have_posts() ) :
				$re_query->the_post();
				re_card( 'post-card', array( 'post_id' => get_the_ID() ) );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
