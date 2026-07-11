<?php
/**
 * Shared property results grid for the main query (archive + taxonomy).
 *
 * @package realestate
 */
?>
<div class="archive-results">
	<p class="archive-results__count">
		<?php
		global $wp_query;
		printf(
			esc_html( _n( '%s property found', '%s properties found', (int) $wp_query->found_posts, 'realestate' ) ),
			esc_html( number_format_i18n( (int) $wp_query->found_posts ) )
		);
		?>
	</p>

	<?php if ( have_posts() ) : ?>
		<div class="grid grid--cards">
			<?php
			while ( have_posts() ) :
				the_post();
				re_card( 'property-card', array( 'post_id' => get_the_ID() ) );
			endwhile;
			?>
		</div>
		<?php get_template_part( 'template-parts/components/pagination' ); ?>
	<?php else : ?>
		<div class="archive-empty surface">
			<p><?php esc_html_e( 'No properties match your search. Try adjusting the filters.', 'realestate' ); ?></p>
			<a class="btn btn--outline" href="<?php echo esc_url( get_post_type_archive_link( 'property' ) ); ?>"><?php esc_html_e( 'Reset filters', 'realestate' ); ?></a>
		</div>
	<?php endif; ?>
</div>
