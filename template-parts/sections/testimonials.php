<?php
/**
 * What Our Clients Say — testimonials section (Figma). Header + 3-card carousel + pager.
 *
 * @package realestate
 */

$re_query = new WP_Query( array(
	'post_type'           => 'testimonial',
	'posts_per_page'      => -1,
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
) );

if ( ! $re_query->have_posts() ) {
	return;
}

$re_total = (int) wp_count_posts( 'testimonial' )->publish;
?>
<section class="section re-testimonials">
	<div class="container">
		<div class="re-sec-head-row">
			<div class="re-sec-head">
				<h2 class="re-sec-head__title"><?php echo esc_html( re_field( 'testimonials_heading' ) ?: __( 'What Our Clients Say', 'realestate' ) ); ?></h2>
				<p class="re-sec-head__text"><?php echo esc_html( re_field( 'testimonials_subcopy' ) ?: __( 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.', 'realestate' ) ); ?></p>
			</div>
			<a class="btn btn--dark re-sec-head__action" href="#"><?php esc_html_e( 'View All Testimonials', 'realestate' ); ?></a>
		</div>

		<div class="re-carousel" data-carousel>
			<?php
			while ( $re_query->have_posts() ) :
				$re_query->the_post();
				re_card( 'testimonial-card', array( 'post_id' => get_the_ID() ) );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<?php re_pager( $re_query->post_count ); ?>
	</div>
</section>
