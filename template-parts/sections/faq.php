<?php
/**
 * Frequently Asked Questions section (Figma). Header + 3 FAQ cards + pager.
 * FAQs come from the `faq` CPT.
 *
 * @package realestate
 */

$re_query = new WP_Query( array(
	'post_type'           => 'faq',
	'posts_per_page'      => -1,
	'orderby'             => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
) );

if ( ! $re_query->have_posts() ) {
	return;
}

$re_total   = (int) wp_count_posts( 'faq' )->publish;
$re_archive = (string) get_post_type_archive_link( 'faq' );
?>
<section class="section re-faq">
	<div class="container">
		<div class="re-sec-head-row">
			<div class="re-sec-head">
				<h2 class="re-sec-head__title"><?php echo esc_html( re_field( 'faq_heading' ) ?: __( 'Frequently Asked Questions', 'realestate' ) ); ?></h2>
				<p class="re-sec-head__text"><?php echo esc_html( re_field( 'faq_subcopy' ) ?: __( "Find answers to common questions about Estatein's services, property listings, and the real estate process. We're here to provide clarity and assist you every step of the way.", 'realestate' ) ); ?></p>
			</div>
			<a class="btn btn--dark re-sec-head__action" href="<?php echo esc_url( $re_archive ); ?>"><?php esc_html_e( "View All FAQ's", 'realestate' ); ?></a>
		</div>

		<div class="re-carousel" data-carousel>
			<?php
			while ( $re_query->have_posts() ) :
				$re_query->the_post();
				$re_teaser = re_field( 'answer_teaser' ) ?: wp_trim_words( wp_strip_all_tags( get_the_content() ), 18, '…' );
				?>
				<article class="faq-card">
					<h3 class="faq-card__q"><?php the_title(); ?></h3>
					<p class="faq-card__a"><?php echo esc_html( $re_teaser ); ?></p>
					<a class="btn btn--dark faq-card__more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'realestate' ); ?></a>
				</article>
			<?php endwhile; wp_reset_postdata(); ?>
		</div>

		<?php re_pager( $re_query->post_count ); ?>
	</div>
</section>
