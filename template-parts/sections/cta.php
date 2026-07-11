<?php
/**
 * CTA banner — "Start Your Real Estate Journey Today" (Figma).
 *
 * @package realestate
 */

$re_heading = re_field( 'cta_heading' ) ?: __( 'Start Your Real Estate Journey Today', 'realestate' );
$re_subcopy = re_field( 'cta_subcopy' ) ?: __( "Your dream property is just a click away. Whether you're looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way. Take the first step towards your real estate goals and explore our available properties or get in touch with our team for personalized assistance.", 'realestate' );
$re_button  = re_field( 'cta_button' );
$re_btn_url = ! empty( $re_button['url'] ) ? $re_button['url'] : (string) get_post_type_archive_link( 'property' );
$re_btn_lbl = ! empty( $re_button['title'] ) ? $re_button['title'] : __( 'Explore Properties', 'realestate' );
?>
<section class="re-cta">
	<div class="container re-cta__inner">
		<div class="re-cta__text">
			<h2 class="re-cta__title"><?php echo esc_html( $re_heading ); ?></h2>
			<p class="re-cta__subcopy"><?php echo esc_html( $re_subcopy ); ?></p>
		</div>
		<a class="btn btn--primary re-cta__btn" href="<?php echo esc_url( $re_btn_url ); ?>"><?php echo esc_html( $re_btn_lbl ); ?></a>
	</div>
</section>
