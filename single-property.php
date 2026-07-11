<?php
/**
 * Single property.
 *
 * @package realestate
 */

get_header();
while ( have_posts() ) :
	the_post();
	$re_id = get_the_ID();

	$re_price        = re_price( re_field( 'price', $re_id ) );
	$re_status       = re_property_status( $re_id );
	$re_location     = re_property_location( $re_id );
	$re_bedrooms     = (int) re_field( 'bedrooms', $re_id );
	$re_bathrooms    = (int) re_field( 'bathrooms', $re_id );
	$re_area         = (float) re_field( 'area_sqft', $re_id );
	$re_features     = re_property_key_features( $re_id );
	$re_pricing      = re_property_pricing_details( $re_id );
	$re_inquiry_prop = re_property_inquiry_label( $re_id );
	$re_images       = re_property_gallery_images( $re_id );

	$re_content = trim( get_the_content() );
	$re_faqs    = new WP_Query(
		array(
			'post_type'      => 'faq',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
			'orderby'        => 'date',
			'order'          => 'ASC',
		)
	);
	$re_faq_count = (int) $re_faqs->post_count;
	?>
	<main id="main" class="site-main single-property">
		<div class="single-property__shell">
			<?php get_template_part( 'template-parts/components/breadcrumbs' ); ?>

			<header class="single-property__hero">
				<div class="single-property__hero-copy">
					<h1 class="single-property__title"><?php the_title(); ?></h1>
					<?php if ( $re_location ) : ?>
						<p class="single-property__location"><?php re_icon( 'pin' ); ?><span><?php echo esc_html( $re_location ); ?></span></p>
					<?php endif; ?>
				</div>
				<?php if ( $re_price ) : ?>
					<div class="single-property__hero-price">
						<span><?php esc_html_e( 'Listing Price', 'realestate' ); ?></span>
						<strong><?php echo esc_html( $re_price ); ?></strong>
					</div>
				<?php endif; ?>
			</header>

			<?php if ( $re_images ) : ?>
				<section class="single-property__gallery" data-gallery aria-label="<?php esc_attr_e( 'Property gallery', 'realestate' ); ?>">
					<div class="single-property__gallery-frame">
						<div class="single-property__gallery-thumbs">
							<?php foreach ( $re_images as $re_idx => $re_src ) : ?>
								<button type="button" class="single-property__thumb<?php echo 0 === $re_idx ? ' is-active' : ''; ?>" data-gallery-thumb="<?php echo esc_url( $re_src ); ?>" data-gallery-index="<?php echo esc_attr( $re_idx ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show image %d', 'realestate' ), $re_idx + 1 ) ); ?>">
									<img src="<?php echo esc_url( $re_src ); ?>" alt="" width="156" height="74" loading="lazy" decoding="async">
								</button>
							<?php endforeach; ?>
						</div>
						<div class="single-property__gallery-stage">
							<div class="single-property__gallery-main">
								<img src="<?php echo esc_url( $re_images[0] ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" width="748" height="500" decoding="async" data-gallery-main>
							</div>
							<div class="single-property__gallery-side">
								<img src="<?php echo esc_url( $re_images[1] ?? $re_images[0] ); ?>" alt="" width="748" height="500" loading="lazy" decoding="async" data-gallery-side>
							</div>
						</div>
						<div class="single-property__gallery-controls">
							<button type="button" data-gallery-prev aria-label="<?php esc_attr_e( 'Previous image', 'realestate' ); ?>"><?php re_icon( 'arrow-right', 'single-property__gallery-prev' ); ?></button>
							<span class="single-property__gallery-dots" aria-label="<?php esc_attr_e( 'Gallery pagination', 'realestate' ); ?>">
								<?php foreach ( $re_images as $re_idx => $re_src ) : ?>
									<button type="button" class="<?php echo 0 === $re_idx ? 'is-active' : ''; ?>" data-gallery-dot="<?php echo esc_attr( $re_idx ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show image %d', 'realestate' ), $re_idx + 1 ) ); ?>"></button>
								<?php endforeach; ?>
							</span>
							<button type="button" data-gallery-next aria-label="<?php esc_attr_e( 'Next image', 'realestate' ); ?>"><?php re_icon( 'arrow-right' ); ?></button>
						</div>
					</div>
				</section>
			<?php endif; ?>

			<section class="property-overview">
				<div class="property-overview__description">
					<article class="property-detail-card property-detail-card--description">
						<div class="property-detail-card__copy">
							<h2><?php esc_html_e( 'Description', 'realestate' ); ?></h2>
							<div class="property-detail-card__text">
								<?php
								if ( $re_content ) {
									the_content();
								} else {
									echo '<p>' . esc_html__( 'Discover a refined property with generous living spaces, considered details, and a location designed for everyday comfort.', 'realestate' ) . '</p>';
								}
								?>
							</div>
						</div>
						<div class="property-detail-card__stats">
							<div class="property-stat">
								<span class="property-stat__label"><?php re_icon( 'bed' ); ?><?php esc_html_e( 'Bedrooms', 'realestate' ); ?></span>
								<strong><?php echo esc_html( str_pad( (string) max( 0, $re_bedrooms ), 2, '0', STR_PAD_LEFT ) ); ?></strong>
							</div>
							<div class="property-stat">
								<span class="property-stat__label"><?php re_icon( 'bath' ); ?><?php esc_html_e( 'Bathrooms', 'realestate' ); ?></span>
								<strong><?php echo esc_html( str_pad( (string) max( 0, $re_bathrooms ), 2, '0', STR_PAD_LEFT ) ); ?></strong>
							</div>
							<div class="property-stat property-stat--wide">
								<span class="property-stat__label"><?php re_icon( 'area' ); ?><?php esc_html_e( 'Area', 'realestate' ); ?></span>
								<strong><?php echo esc_html( $re_area ? number_format_i18n( $re_area ) . ' ' . __( 'Square Feet', 'realestate' ) : __( 'Available on request', 'realestate' ) ); ?></strong>
							</div>
						</div>
					</article>

					<article class="property-detail-card property-detail-card--features">
						<h2><?php esc_html_e( 'Key Features and Amenities', 'realestate' ); ?></h2>
						<ul class="property-feature-list" role="list">
							<?php foreach ( $re_features as $re_feature ) : ?>
								<li><?php re_icon( 'star' ); ?><span><?php echo esc_html( $re_feature ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</article>
				</div>
			</section>

			<section class="property-inquiry">
				<div class="property-section-heading property-section-heading--inquiry">
					<span class="property-section-heading__spark" aria-hidden="true"></span>
					<h2><?php printf( esc_html__( 'Inquire About %s', 'realestate' ), esc_html( get_the_title() ) ); ?></h2>
					<p><?php esc_html_e( 'Interested in this property? Fill out the form below, and our real estate experts will get back to you with more details, including scheduling a viewing and answering any questions you may have.', 'realestate' ); ?></p>
				</div>
				<form class="property-inquiry__form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<input type="hidden" name="action" value="re_contact">
					<?php wp_nonce_field( 're_contact', 're_contact_nonce' ); ?>
					<input class="screen-reader-text" type="text" name="re_website" tabindex="-1" autocomplete="off">

					<div class="property-form-grid">
						<label>
							<span><?php esc_html_e( 'First Name', 'realestate' ); ?></span>
							<input name="re_name" type="text" placeholder="<?php esc_attr_e( 'Enter First Name', 'realestate' ); ?>" required>
						</label>
						<label>
							<span><?php esc_html_e( 'Last Name', 'realestate' ); ?></span>
							<input name="re_last_name" type="text" placeholder="<?php esc_attr_e( 'Enter Last Name', 'realestate' ); ?>">
						</label>
						<label>
							<span><?php esc_html_e( 'Email', 'realestate' ); ?></span>
							<input name="re_email" type="email" placeholder="<?php esc_attr_e( 'Enter your Email', 'realestate' ); ?>" required>
						</label>
						<label>
							<span><?php esc_html_e( 'Phone', 'realestate' ); ?></span>
							<input name="re_phone" type="tel" placeholder="<?php esc_attr_e( 'Enter Phone Number', 'realestate' ); ?>">
						</label>
					</div>
					<label class="property-form-field property-form-field--selected">
						<span><?php esc_html_e( 'Selected Property', 'realestate' ); ?></span>
						<input type="text" value="<?php echo esc_attr( $re_inquiry_prop ); ?>" readonly>
						<?php re_icon( 'pin' ); ?>
					</label>
					<label class="property-form-field">
						<span><?php esc_html_e( 'Message', 'realestate' ); ?></span>
						<textarea name="re_message" placeholder="<?php esc_attr_e( 'Enter your Message here..', 'realestate' ); ?>" required></textarea>
					</label>
					<div class="property-inquiry__actions">
						<label class="property-inquiry__terms">
							<input type="checkbox" required>
							<span><?php esc_html_e( 'I agree with Terms of Use and Privacy Policy', 'realestate' ); ?></span>
						</label>
						<button class="btn btn--primary" type="submit"><?php esc_html_e( 'Send Your Message', 'realestate' ); ?></button>
					</div>
				</form>
			</section>

			<section class="property-pricing">
				<div class="property-section-heading">
					<span class="property-section-heading__spark" aria-hidden="true"></span>
					<h2><?php esc_html_e( 'Comprehensive Pricing Details', 'realestate' ); ?></h2>
					<p><?php printf( esc_html__( 'At Estatein, transparency is key. We want you to have a clear understanding of all costs associated with your property investment. Below, we break down the pricing for %s to help you make an informed decision.', 'realestate' ), esc_html( get_the_title() ) ); ?></p>
				</div>

				<div class="property-pricing__note">
					<strong><?php esc_html_e( 'Note', 'realestate' ); ?></strong>
					<p><?php echo esc_html( $re_pricing['note'] ); ?></p>
				</div>

				<div class="property-pricing__grid">
					<div class="property-pricing__price">
						<span><?php esc_html_e( 'Listing Price', 'realestate' ); ?></span>
						<strong><?php echo esc_html( $re_pricing['listing_price'] ); ?></strong>
					</div>
					<div class="property-pricing__cards">
						<?php foreach ( $re_pricing['sections'] as $re_section ) : ?>
							<article class="pricing-card">
								<header class="pricing-card__header">
									<h3><?php echo esc_html( $re_section['title'] ); ?></h3>
									<a class="pricing-card__more" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Learn More', 'realestate' ); ?></a>
								</header>
								<div class="pricing-card__items">
									<?php foreach ( $re_section['items'] as $re_item ) : ?>
										<div class="pricing-card__item">
											<span class="pricing-card__label"><?php echo esc_html( $re_item['label'] ); ?></span>
											<div class="pricing-card__value-row">
												<strong><?php echo esc_html( $re_item['value'] ); ?></strong>
												<?php if ( ! empty( $re_item['note'] ) ) : ?>
													<span><?php echo esc_html( $re_item['note'] ); ?></span>
												<?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<?php if ( $re_faqs->have_posts() ) : ?>
				<section class="property-faq">
					<div class="property-section-heading">
						<span class="property-section-heading__spark" aria-hidden="true"></span>
						<h2><?php esc_html_e( 'Frequently Asked Questions', 'realestate' ); ?></h2>
						<p><?php esc_html_e( "Find answers to common questions about Estatein's services, property listings, and the real estate process. We're here to provide clarity and assist you every step of the way.", 'realestate' ); ?></p>
					</div>
					<a class="property-faq__all" href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>"><?php esc_html_e( "View All FAQ's", 'realestate' ); ?></a>
					<div class="property-faq__cards" data-carousel>
						<?php
						while ( $re_faqs->have_posts() ) :
							$re_faqs->the_post();
							?>
							<article class="property-faq-card">
								<h3><?php the_title(); ?></h3>
								<p><?php echo esc_html( re_field( 'answer_teaser', get_the_ID() ) ?: wp_trim_words( wp_strip_all_tags( get_the_content() ), 18 ) ); ?></p>
								<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'realestate' ); ?></a>
							</article>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<div class="property-faq__pager">
						<span><strong data-pager-current>01</strong> <?php esc_html_e( 'of', 'realestate' ); ?> <span data-pager-total><?php echo esc_html( str_pad( (string) max( 1, $re_faq_count ), 2, '0', STR_PAD_LEFT ) ); ?></span></span>
						<div class="property-faq__dots" data-carousel-dots aria-label="<?php esc_attr_e( 'FAQ pagination', 'realestate' ); ?>">
							<?php for ( $re_idx = 0; $re_idx < $re_faq_count; $re_idx++ ) : ?>
								<button type="button" class="<?php echo 0 === $re_idx ? 'is-active' : ''; ?>" data-carousel-dot="<?php echo esc_attr( $re_idx ); ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Show FAQ %d', 'realestate' ), $re_idx + 1 ) ); ?>"></button>
							<?php endfor; ?>
						</div>
						<div class="property-faq__buttons">
							<button type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous FAQ', 'realestate' ); ?>"><?php re_icon( 'arrow-right', 'property-faq__prev' ); ?></button>
							<button type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Next FAQ', 'realestate' ); ?>"><?php re_icon( 'arrow-right' ); ?></button>
						</div>
					</div>
				</section>
			<?php endif; ?>
		</div>
	</main>
	<?php
endwhile;
get_footer();
