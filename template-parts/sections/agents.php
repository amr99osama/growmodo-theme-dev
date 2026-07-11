<?php
/**
 * Agents / team section (agent CPT).
 *
 * @package realestate
 */

$re_query = new WP_Query( array(
	'post_type'           => 'agent',
	'posts_per_page'      => 4,
	'no_found_rows'       => true,
	'ignore_sticky_posts' => true,
) );

if ( ! $re_query->have_posts() ) {
	return;
}
?>
<section class="section agents">
	<div class="container">
		<div class="agents__head">
			<?php
			get_template_part( 'template-parts/components/section-heading', null, array(
				'eyebrow' => re_field( 'agents_eyebrow' ),
				'heading' => re_field( 'agents_heading' ) ?: __( 'Meet Our Agents', 'realestate' ),
				'subcopy' => re_field( 'agents_subcopy' ),
			) );
			?>
			<a class="btn btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'agent' ) ); ?>">
				<?php esc_html_e( 'All agents', 'realestate' ); ?><?php re_icon( 'arrow-right' ); ?>
			</a>
		</div>

		<div class="grid grid--4">
			<?php
			while ( $re_query->have_posts() ) :
				$re_query->the_post();
				re_card( 'agent-card', array( 'post_id' => get_the_ID() ) );
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
</section>
