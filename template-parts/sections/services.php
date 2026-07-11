<?php
/**
 * Services section (fixed set of service items).
 *
 * @package realestate
 */

$re_services = (array) re_field( 'services' );
$re_items = array();
for ( $i = 1; $i <= 4; $i++ ) {
	if ( ! empty( $re_services[ "service_{$i}_title" ] ) ) {
		$re_items[] = array(
			'icon'  => $re_services[ "service_{$i}_icon" ] ?? 'check',
			'title' => $re_services[ "service_{$i}_title" ],
			'text'  => $re_services[ "service_{$i}_text" ] ?? '',
		);
	}
}

if ( empty( $re_items ) ) {
	return;
}
?>
<section class="section services">
	<div class="container">
		<?php
		get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => re_field( 'services_eyebrow' ),
			'heading' => re_field( 'services_heading' ) ?: __( 'What We Offer', 'realestate' ),
			'subcopy' => re_field( 'services_subcopy' ),
			'center'  => true,
		) );
		?>

		<div class="services__grid grid grid--4">
			<?php foreach ( $re_items as $re_item ) : ?>
				<article class="service-card surface">
					<span class="service-card__icon"><?php re_icon( $re_item['icon'] ?: 'check' ); ?></span>
					<h3 class="service-card__title"><?php echo esc_html( $re_item['title'] ); ?></h3>
					<?php if ( $re_item['text'] ) : ?>
						<p class="service-card__text"><?php echo esc_html( $re_item['text'] ); ?></p>
					<?php endif; ?>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
