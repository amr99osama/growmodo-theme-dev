<?php
/**
 * Template Name: Contact
 *
 * @package realestate
 */

get_header();

$re_phone   = re_option( 'contact_phone' );
$re_email   = re_option( 'contact_email' );
$re_address = re_option( 'contact_address' );
$re_hours   = re_option( 'office_hours' );
$re_map     = re_option( 'map_embed' );
$re_status  = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
?>
<main id="main" class="site-main">
	<div class="page-hero">
		<div class="container">
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>
			<h1 class="page-hero__title"><?php the_title(); ?></h1>
		</div>
	</div>

	<div class="section">
		<div class="container contact__layout">
			<div class="contact__info">
				<?php while ( have_posts() ) : the_post(); ?>
					<?php if ( get_the_content() ) : ?>
						<div class="entry-content"><?php the_content(); ?></div>
					<?php endif; ?>
				<?php endwhile; ?>

				<ul class="contact__list" role="list">
					<?php if ( $re_address ) : ?>
						<li><?php re_icon( 'pin' ); ?><span><?php echo wp_kses_post( $re_address ); ?></span></li>
					<?php endif; ?>
					<?php if ( $re_phone ) : ?>
						<li><?php re_icon( 'phone' ); ?><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $re_phone ) ); ?>"><?php echo esc_html( $re_phone ); ?></a></li>
					<?php endif; ?>
					<?php if ( $re_email ) : ?>
						<li><?php re_icon( 'mail' ); ?><a href="mailto:<?php echo esc_attr( $re_email ); ?>"><?php echo esc_html( $re_email ); ?></a></li>
					<?php endif; ?>
					<?php if ( $re_hours ) : ?>
						<li><?php re_icon( 'calendar' ); ?><span><?php echo esc_html( $re_hours ); ?></span></li>
					<?php endif; ?>
				</ul>

				<?php if ( $re_map ) : ?>
					<div class="contact__map"><?php echo wp_kses( $re_map, array( 'iframe' => array( 'src' => array(), 'width' => array(), 'height' => array(), 'style' => array(), 'loading' => array(), 'allowfullscreen' => array(), 'referrerpolicy' => array(), 'title' => array() ) ) ); ?></div>
				<?php endif; ?>
			</div>

			<div class="contact__form-wrap surface">
				<?php if ( 'sent' === $re_status ) : ?>
					<div class="contact__notice contact__notice--ok"><?php esc_html_e( 'Thanks! Your message has been sent.', 'realestate' ); ?></div>
				<?php elseif ( 'error' === $re_status ) : ?>
					<div class="contact__notice contact__notice--err"><?php esc_html_e( 'Please check the form and try again.', 'realestate' ); ?></div>
				<?php endif; ?>

				<form class="contact__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="re_contact">
					<?php wp_nonce_field( 're_contact', 're_contact_nonce' ); ?>
					<p class="contact__hp" aria-hidden="true"><label>Website<input type="text" name="re_website" tabindex="-1" autocomplete="off"></label></p>

					<div class="contact__row">
						<label>
							<span><?php esc_html_e( 'Name', 'realestate' ); ?></span>
							<input type="text" name="re_name" required>
						</label>
						<label>
							<span><?php esc_html_e( 'Email', 'realestate' ); ?></span>
							<input type="email" name="re_email" required>
						</label>
					</div>
					<label>
						<span><?php esc_html_e( 'Phone', 'realestate' ); ?></span>
						<input type="text" name="re_phone">
					</label>
					<label>
						<span><?php esc_html_e( 'Message', 'realestate' ); ?></span>
						<textarea name="re_message" rows="5" required></textarea>
					</label>
					<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Send Message', 'realestate' ); ?></button>
				</form>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();
