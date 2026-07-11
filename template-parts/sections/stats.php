<?php
/**
 * Stats / counters section.
 *
 * @package realestate
 */

$re_stats = (array) re_field( 'stats' );
$re_items = array();
for ( $i = 1; $i <= 4; $i++ ) {
	if ( ! empty( $re_stats[ "stat_{$i}_value" ] ) ) {
		$re_items[] = array(
			'value' => $re_stats[ "stat_{$i}_value" ],
			'label' => $re_stats[ "stat_{$i}_label" ] ?? '',
		);
	}
}

if ( empty( $re_items ) ) {
	return;
}
?>
<section class="section section--tight stats">
	<div class="container">
		<?php if ( re_field( 'stats_heading' ) ) : ?>
			<h2 class="stats__heading"><?php echo esc_html( re_field( 'stats_heading' ) ); ?></h2>
		<?php endif; ?>
		<ul class="stats__grid" role="list">
			<?php foreach ( $re_items as $re_item ) : ?>
				<li class="stats__item">
					<span class="stats__value" data-countup><?php echo esc_html( $re_item['value'] ); ?></span>
					<span class="stats__label"><?php echo esc_html( $re_item['label'] ); ?></span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
