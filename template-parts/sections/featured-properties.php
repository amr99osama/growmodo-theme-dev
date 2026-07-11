<?php
/**
 * Featured Properties section (Figma). Header + 3-card carousel + pager.
 *
 * @package realestate
 */

$re_count = (int) ( re_field( 'featured_count' ) ?: 3 );
$re_query = new WP_Query( array(
	'post_type'           => 'property',
	'posts_per_page'      => max( 1, $re_count ),
	'orderby'             => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
) );

if ( ! $re_query->have_posts() ) {
	return;
}

$re_total   = (int) wp_count_posts( 'property' )->publish;
$re_archive = (string) get_post_type_archive_link( 'property' );
?>
<section class="section re-featured">
	<div class="container">
		<div class="re-sec-head-row">
			<div class="re-sec-head">
				<h2 class="re-sec-head__title"><?php echo esc_html( re_field( 'featured_heading' ) ?: __( 'Featured Properties', 'realestate' ) ); ?></h2>
				<p class="re-sec-head__text"><?php echo esc_html( re_field( 'featured_subcopy' ) ?: __( 'Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein.', 'realestate' ) ); ?></p>
			</div>
			<a class="btn btn--dark re-sec-head__action" href="<?php echo esc_url( $re_archive ); ?>"><?php esc_html_e( 'View All Properties', 'realestate' ); ?></a>
		</div>

		<div class="re-carousel" data-carousel>
			<?php
			while ( $re_query->have_posts() ) :
				$re_query->the_post();
				re_card( 'property-card', array( 'post_id' => get_the_ID() ) );
			endwhile;
			wp_reset_postdata();
			?>
		</div>

		<?php re_pager( $re_query->post_count ); ?>
	</div>
</section>
