<?php
/**
 * Render helpers used across templates and template-parts.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Safe ACF field getter (returns null when ACF is inactive).
 *
 * @param string    $name    Field name.
 * @param int|false $post_id Optional post ID.
 * @return mixed|null
 */
function re_field( string $name, $post_id = false ) {
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : null;
}

/**
 * Get a value from the global "Site Settings" page (free-ACF options store).
 *
 * @param string $name Field name.
 * @return mixed|null
 */
function re_option( string $name ) {
	if ( ! function_exists( 'get_field' ) || ! function_exists( 're_settings_page_id' ) ) {
		return null;
	}
	$page_id = re_settings_page_id();
	return $page_id ? get_field( $name, $page_id ) : null;
}

/**
 * Render a section template-part with args.
 *
 * @param string              $slug Section slug (file in template-parts/sections/).
 * @param array<string,mixed> $args Passed as $args to the part.
 */
function re_section( string $slug, array $args = array() ): void {
	get_template_part( 'template-parts/sections/' . $slug, null, $args );
}

/**
 * Render a card template-part with args.
 *
 * @param string              $slug Card slug (file in template-parts/cards/).
 * @param array<string,mixed> $args Passed as $args to the part.
 */
function re_card( string $slug, array $args = array() ): void {
	get_template_part( 'template-parts/cards/' . $slug, null, $args );
}

/**
 * Render a component template-part with args.
 *
 * @param string              $slug Component slug (file in template-parts/components/).
 * @param array<string,mixed> $args Passed as $args to the part.
 */
function re_component( string $slug, array $args = array() ): void {
	get_template_part( 'template-parts/components/' . $slug, null, $args );
}

/**
 * Render the shared carousel pager ("NN of NN" + prev/next buttons).
 * carousel.js updates the current index live and confirms the total from the DOM.
 *
 * @param int $total Number of items in the carousel.
 */
function re_pager( int $total ): void {
	$total = max( 1, $total );
	?>
	<div class="re-pager">
		<p class="re-pager__count"><span data-pager-current>01</span> <?php esc_html_e( 'of', 'realestate' ); ?> <span data-pager-total><?php echo esc_html( str_pad( (string) $total, 2, '0', STR_PAD_LEFT ) ); ?></span></p>
		<div class="re-pager__btns">
			<button class="re-pager__btn" type="button" data-carousel-prev aria-label="<?php esc_attr_e( 'Previous', 'realestate' ); ?>"><?php re_icon( 'arrow-right', 're-pager__ico re-pager__ico--prev' ); ?></button>
			<button class="re-pager__btn re-pager__btn--next" type="button" data-carousel-next aria-label="<?php esc_attr_e( 'Next', 'realestate' ); ?>"><?php re_icon( 'arrow-right' ); ?></button>
		</div>
	</div>
	<?php
}

/**
 * Format a numeric price. Empty/zero returns ''.
 *
 * @param mixed  $amount Raw price value.
 * @param string $symbol Currency symbol.
 * @return string
 */
function re_price( $amount, string $symbol = '$' ): string {
	$amount = (float) $amount;
	if ( $amount <= 0 ) {
		return '';
	}
	return $symbol . number_format_i18n( $amount );
}

/**
 * Echo an ACF link array as an anchor.
 *
 * @param array<string,mixed>|null $link  ACF link field { title, url, target }.
 * @param string                   $class Extra CSS classes.
 * @param string                   $fallback_text Fallback text if title empty.
 */
function re_link( $link, string $class = 'btn btn--primary', string $fallback_text = '' ): void {
	if ( empty( $link['url'] ) ) {
		return;
	}
	$title  = $link['title'] ?? $fallback_text;
	$target = $link['target'] ?? '';
	printf(
		'<a class="%1$s" href="%2$s"%3$s>%4$s</a>',
		esc_attr( $class ),
		esc_url( $link['url'] ),
		$target ? ' target="' . esc_attr( $target ) . '" rel="noopener"' : '',
		esc_html( $title )
	);
}

/**
 * Property spec row values keyed for the card/single spec list.
 *
 * @param int $post_id Property ID.
 * @return array<string,string> icon => label
 */
function re_property_specs( int $post_id ): array {
	$specs = array();
	$beds  = re_field( 'bedrooms', $post_id );
	$baths = re_field( 'bathrooms', $post_id );
	$area  = re_field( 'area_sqft', $post_id );
	$gar   = re_field( 'garage', $post_id );

	if ( $beds )  { $specs['bed']  = sprintf( _n( '%d Bed', '%d Beds', (int) $beds, 'realestate' ), (int) $beds ); }
	if ( $baths ) { $specs['bath'] = sprintf( _n( '%d Bath', '%d Baths', (int) $baths, 'realestate' ), (int) $baths ); }
	if ( $area )  { $specs['area'] = number_format_i18n( (float) $area ) . ' ' . __( 'sqft', 'realestate' ); }
	if ( $gar )   { $specs['garage'] = sprintf( _n( '%d Garage', '%d Garages', (int) $gar, 'realestate' ), (int) $gar ); }

	return $specs;
}

/**
 * Normalize an ACF/attachment/image URL value into a usable image URL.
 *
 * @param mixed  $source Raw image source.
 * @param string $size   Image size.
 * @return string
 */
function re_gallery_image_source_url( $source, string $size = 're_hero' ): string {
	if ( is_array( $source ) ) {
		if ( ! empty( $source['sizes'][ $size ] ) ) {
			return (string) $source['sizes'][ $size ];
		}
		if ( ! empty( $source['url'] ) ) {
			return (string) $source['url'];
		}
		if ( ! empty( $source['ID'] ) ) {
			$source = $source['ID'];
		}
	}

	if ( is_numeric( $source ) ) {
		$url = wp_get_attachment_image_url( (int) $source, $size );
		return $url ? $url : '';
	}

	if ( is_string( $source ) && filter_var( $source, FILTER_VALIDATE_URL ) ) {
		return $source;
	}

	return '';
}

/**
 * Unlimited gallery image list for property detail pages.
 *
 * @param int $post_id Property ID.
 * @return array<int,string>
 */
function re_property_gallery_images( int $post_id ): array {
	$images = array();
	$add    = static function ( $source ) use ( &$images ) {
		$url = re_gallery_image_source_url( $source );
		if ( $url && ! in_array( $url, $images, true ) ) {
			$images[] = $url;
		}
	};

	if ( has_post_thumbnail( $post_id ) ) {
		$add( get_post_thumbnail_id( $post_id ) );
	}

	$gallery = (array) re_field( 'gallery', $post_id );
	if ( $gallery ) {
		uksort(
			$gallery,
			static function ( $a, $b ) {
				preg_match( '/(\d+)$/', (string) $a, $a_match );
				preg_match( '/(\d+)$/', (string) $b, $b_match );
				return (int) ( $a_match[1] ?? 0 ) <=> (int) ( $b_match[1] ?? 0 );
			}
		);

		foreach ( $gallery as $image ) {
			$add( $image );
		}
	}

	$unlimited = (string) re_field( 'gallery_images_unlimited', $post_id );
	if ( $unlimited ) {
		$lines = preg_split( '/\r\n|\r|\n/', $unlimited );
		foreach ( (array) $lines as $line ) {
			$add( trim( (string) $line ) );
		}
	}

	foreach ( get_attached_media( 'image', $post_id ) as $attachment ) {
		$add( $attachment->ID );
	}

	if ( ! $images ) {
		$images[] = re_thumb_url( $post_id, 're_hero' );
	}

	return $images;
}

/**
 * One-key-feature-per-line property feature list for the Figma detail page.
 *
 * @param int $post_id Property ID.
 * @return array<int,string>
 */
function re_property_key_features( int $post_id ): array {
	$raw = (string) re_field( 'key_features', $post_id );
	if ( $raw ) {
		$features = preg_split( '/\r\n|\r|\n/', $raw );
		$features = array_values( array_filter( array_map( 'trim', (array) $features ) ) );
		if ( $features ) {
			return $features;
		}
	}

	return array(
		__( 'Expansive oceanfront terrace for outdoor entertaining', 'realestate' ),
		__( 'Gourmet kitchen with top-of-the-line appliances', 'realestate' ),
		__( 'Private beach access for morning strolls and sunset views', 'realestate' ),
		__( 'Master suite with a spa-inspired bathroom and ocean-facing balcony', 'realestate' ),
		__( 'Private garage and ample storage space', 'realestate' ),
	);
}

/**
 * Format a Figma pricing value from ACF, with fallback text.
 *
 * @param mixed  $value    Raw ACF value.
 * @param string $fallback Fallback display value.
 * @return string
 */
function re_pricing_value( $value, string $fallback ): string {
	if ( '' === $value || null === $value ) {
		return $fallback;
	}
	if ( is_numeric( $value ) ) {
		return re_price( $value );
	}
	return (string) $value;
}

/**
 * Figma pricing cards for property detail pages.
 *
 * @param int $post_id Property ID.
 * @return array<string,mixed>
 */
function re_property_pricing_details( int $post_id ): array {
	$price = re_price( re_field( 'price', $post_id ) ) ?: '$1,250,000';
	$transfer_tax = re_pricing_value( re_field( 'price_transfer_tax', $post_id ), '$25,000' );
	$legal_fees = re_pricing_value( re_field( 'price_legal_fees', $post_id ), '$3,000' );
	$inspection = re_pricing_value( re_field( 'price_home_inspection', $post_id ), '$500' );
	$annual_insurance = re_pricing_value( re_field( 'price_property_insurance_annual', $post_id ), '$1,200' );
	$mortgage_fees = re_pricing_value( re_field( 'price_mortgage_fees', $post_id ), __( 'Varies', 'realestate' ) );
	$monthly_taxes = re_pricing_value( re_field( 'price_monthly_taxes', $post_id ), '$1,250' );
	$hoa_fee = re_pricing_value( re_field( 'price_hoa_fee', $post_id ), '$300' );
	$down_payment = re_pricing_value( re_field( 'price_down_payment', $post_id ), '$250,000' );
	$mortgage_amount = re_pricing_value( re_field( 'price_mortgage_amount', $post_id ), '$1,000,000' );
	$mortgage_payment = re_pricing_value( re_field( 'price_mortgage_payment', $post_id ), __( 'Varies based on terms and interest rate', 'realestate' ) );
	$monthly_insurance = re_pricing_value( re_field( 'price_property_insurance_monthly', $post_id ), '$100' );

	$down_note = (string) re_field( 'price_down_payment_note', $post_id );
	$mortgage_note = (string) re_field( 'price_mortgage_amount_note', $post_id );
	$payment_note = (string) re_field( 'price_mortgage_payment_note', $post_id );

	return array(
		'note' => (string) re_field( 'pricing_note', $post_id ) ?: __( 'The figures provided above are estimates and may vary depending on the property, location, and individual circumstances.', 'realestate' ),
		'listing_price' => $price,
		'sections' => array(
			array(
				'title' => __( 'Additional Fees', 'realestate' ),
				'items' => array(
					array( 'label' => __( 'Property Transfer Tax', 'realestate' ), 'value' => $transfer_tax, 'note' => __( 'Based on the sale price and local regulations', 'realestate' ) ),
					array( 'label' => __( 'Legal Fees', 'realestate' ), 'value' => $legal_fees, 'note' => __( 'Approximate cost for legal services, including title transfer', 'realestate' ) ),
					array( 'label' => __( 'Home Inspection', 'realestate' ), 'value' => $inspection, 'note' => __( 'Recommended for due diligence', 'realestate' ) ),
					array( 'label' => __( 'Property Insurance', 'realestate' ), 'value' => $annual_insurance, 'note' => __( 'Annual cost for comprehensive property insurance', 'realestate' ) ),
					array( 'label' => __( 'Mortgage Fees', 'realestate' ), 'value' => $mortgage_fees, 'note' => __( 'If applicable, consult with your lender for specific details', 'realestate' ) ),
				),
			),
			array(
				'title' => __( 'Monthly Costs', 'realestate' ),
				'items' => array(
					array( 'label' => __( 'Property Taxes', 'realestate' ), 'value' => $monthly_taxes, 'note' => __( 'Approximate monthly property tax based on the sale price and local rates', 'realestate' ) ),
					array( 'label' => __( "Homeowners' Association Fee", 'realestate' ), 'value' => $hoa_fee, 'note' => __( 'Monthly fee for common area maintenance and security', 'realestate' ) ),
				),
			),
			array(
				'title' => __( 'Total Initial Costs', 'realestate' ),
				'items' => array(
					array( 'label' => __( 'Listing Price', 'realestate' ), 'value' => $price, 'note' => '' ),
					array( 'label' => __( 'Additional Fees', 'realestate' ), 'value' => re_pricing_value( re_field( 'price_additional_fees_total', $post_id ), '$29,700' ), 'note' => __( 'Property transfer tax, legal fees, inspection, insurance', 'realestate' ) ),
					array( 'label' => __( 'Down Payment', 'realestate' ), 'value' => $down_payment, 'note' => $down_note ?: '20%' ),
					array( 'label' => __( 'Mortgage Amount', 'realestate' ), 'value' => $mortgage_amount, 'note' => $mortgage_note ?: __( 'If applicable', 'realestate' ) ),
				),
			),
			array(
				'title' => __( 'Monthly Expenses', 'realestate' ),
				'items' => array(
					array( 'label' => __( 'Property Taxes', 'realestate' ), 'value' => $monthly_taxes, 'note' => '' ),
					array( 'label' => __( "Homeowners' Association Fee", 'realestate' ), 'value' => $hoa_fee, 'note' => '' ),
					array( 'label' => __( 'Mortgage Payment', 'realestate' ), 'value' => $mortgage_payment, 'note' => $payment_note ?: __( 'If applicable', 'realestate' ) ),
					array( 'label' => __( 'Property Insurance', 'realestate' ), 'value' => $monthly_insurance, 'note' => __( 'Approximate monthly cost', 'realestate' ) ),
				),
			),
		),
	);
}

/**
 * Selected property label shown inside the inquiry form.
 *
 * @param int $post_id Property ID.
 * @return string
 */
function re_property_inquiry_label( int $post_id ): string {
	$label = (string) re_field( 'inquiry_property_label', $post_id );
	if ( $label ) {
		return $label;
	}

	$parts = array_filter( array( get_the_title( $post_id ), re_property_location( $post_id ) ) );
	return implode( ', ', $parts );
}

/**
 * First status term name for a property (badge text).
 *
 * @param int $post_id Property ID.
 * @return string
 */
function re_property_status( int $post_id ): string {
	$terms = get_the_terms( $post_id, 'property_status' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : '';
}

/**
 * First location term name for a property.
 *
 * @param int $post_id Property ID.
 * @return string
 */
function re_property_location( int $post_id ): string {
	$terms = get_the_terms( $post_id, 'property_location' );
	return ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : (string) re_field( 'address', $post_id );
}

/**
 * Escaped thumbnail URL with fallback to the theme placeholder.
 *
 * @param int    $post_id Post ID.
 * @param string $size    Image size.
 * @return string
 */
function re_thumb_url( int $post_id, string $size = 're_card' ): string {
	$url = get_the_post_thumbnail_url( $post_id, $size );
	return $url ? $url : RE_URI . '/assets/img/placeholder.svg';
}

/**
 * Default primary-nav items — wp_nav_menu fallback when no menu is assigned to
 * the 'primary' location. Mirrors the Figma header nav (Home/About Us/Properties/Services).
 *
 * @param array<string,mixed> $args wp_nav_menu args.
 */
function re_nav_fallback( array $args = array() ): void {
	$menu_class = $args['menu_class'] ?? 'primary-nav__list';
	$items      = array(
		array( 'label' => __( 'Home', 'realestate' ),       'url' => home_url( '/' ),                                'current' => is_front_page() ),
		array( 'label' => __( 'About Us', 'realestate' ),   'url' => home_url( '/about-us/' ),                       'current' => false ),
		array( 'label' => __( 'Properties', 'realestate' ), 'url' => (string) get_post_type_archive_link( 'property' ), 'current' => is_post_type_archive( 'property' ) || is_singular( 'property' ) ),
		array( 'label' => __( 'Services', 'realestate' ),   'url' => home_url( '/services/' ),                       'current' => false ),
	);
	echo '<ul class="' . esc_attr( $menu_class ) . '">';
	foreach ( $items as $it ) {
		printf(
			'<li class="menu-item%1$s"><a href="%2$s"%3$s>%4$s</a></li>',
			$it['current'] ? ' current-menu-item' : '',
			esc_url( $it['url'] ),
			$it['current'] ? ' aria-current="page"' : '',
			esc_html( $it['label'] )
		);
	}
	echo '</ul>';
}
