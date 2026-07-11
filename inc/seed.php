<?php
/**
 * Demo content seeder (dev only). Populates CPTs, taxonomies, home sections,
 * and site settings so the design renders fully for pixel QA.
 *
 * Run via:  Tools → "RE Seed Demo"  (WP_DEBUG required)
 *      or:  php wp-cli.phar re seed [--force]
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Whether seeding is allowed to run in this environment.
 */
function re_seed_allowed(): bool {
	return ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'WP_CLI' ) && WP_CLI );
}

/**
 * Insert a term if missing and return its ID.
 *
 * @param string $name     Term name.
 * @param string $taxonomy Taxonomy.
 * @return int
 */
function re_seed_term( string $name, string $taxonomy ): int {
	$existing = term_exists( $name, $taxonomy );
	if ( $existing ) {
		return (int) ( is_array( $existing ) ? $existing['term_id'] : $existing );
	}
	$new = wp_insert_term( $name, $taxonomy );
	return is_wp_error( $new ) ? 0 : (int) $new['term_id'];
}

/**
 * Import a theme demo image (assets/img/demo/) into the media library once and
 * return its attachment ID. Cached in the `re_demo_images` option so repeated
 * seeds reuse the same attachment instead of bloating the library.
 *
 * @param string $rel File name under assets/img/demo/.
 * @return int Attachment ID, or 0 on failure.
 */
function re_seed_image_id( string $rel ): int {
	$map = (array) get_option( 're_demo_images', array() );
	if ( ! empty( $map[ $rel ] ) && 'attachment' === get_post_type( (int) $map[ $rel ] ) ) {
		return (int) $map[ $rel ];
	}
	$file = RE_DIR . '/assets/img/demo/' . $rel;
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( $rel );
	if ( ! $tmp || ! @copy( $file, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => basename( $rel ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	$map[ $rel ] = (int) $id;
	update_option( 're_demo_images', $map );
	return (int) $id;
}

/**
 * Import a static theme image (assets/img/) into the media library once.
 *
 * @param string $rel File name under assets/img/.
 * @return int Attachment ID, or 0 on failure.
 */
function re_seed_static_image_id( string $rel ): int {
	$map = (array) get_option( 're_demo_images', array() );
	$key = 'static/' . $rel;
	if ( ! empty( $map[ $key ] ) && 'attachment' === get_post_type( (int) $map[ $key ] ) ) {
		return (int) $map[ $key ];
	}
	$file = RE_DIR . '/assets/img/' . $rel;
	if ( ! is_readable( $file ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$tmp = wp_tempnam( $rel );
	if ( ! $tmp || ! @copy( $file, $tmp ) ) {
		return 0;
	}
	$id = media_handle_sideload( array( 'name' => basename( $rel ), 'tmp_name' => $tmp ), 0 );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	$map[ $key ] = (int) $id;
	update_option( 're_demo_images', $map );
	return (int) $id;
}

/**
 * Run the demo seeder.
 *
 * @param bool $force Recreate even if already seeded.
 * @return array<string,int> Summary counts.
 */
function re_seed_run( bool $force = false ): array {
	if ( ! $force && get_option( 're_seeded' ) ) {
		return array( 'skipped' => 1 );
	}

	$summary = array( 'properties' => 0, 'agents' => 0, 'testimonials' => 0, 'faqs' => 0 );

	// On --force, clear existing CPT content first so re-seeding is idempotent (no duplicates).
	if ( $force ) {
		foreach ( array( 'property', 'agent', 'testimonial', 'faq' ) as $re_pt ) {
			foreach ( (array) get_posts( array( 'post_type' => $re_pt, 'posts_per_page' => -1, 'post_status' => 'any', 'fields' => 'ids' ) ) as $re_del ) {
				wp_delete_post( (int) $re_del, true );
			}
		}
	}

	// Taxonomies.
	$types = array( 'Apartment', 'Villa', 'House', 'Penthouse', 'Studio', 'Office' );
	$locs  = array( 'Downtown', 'Seaside', 'Suburbs', 'Old Town', 'Hillside' );
	foreach ( $types as $t ) { re_seed_term( $t, 'property_type' ); }
	foreach ( $locs as $l ) { re_seed_term( $l, 'property_location' ); }

	// Agents.
	$agent_ids = array();
	$agents = array(
		array( 'Olivia Bennett', 'Senior Advisor', '+1 555 0111', 'olivia@example.com' ),
		array( 'Marcus Reed', 'Property Consultant', '+1 555 0122', 'marcus@example.com' ),
		array( 'Sofia Marin', 'Luxury Specialist', '+1 555 0133', 'sofia@example.com' ),
		array( 'Daniel Cole', 'Rentals Lead', '+1 555 0144', 'daniel@example.com' ),
	);
	foreach ( $agents as $a ) {
		$id = wp_insert_post( array(
			'post_type'   => 'agent',
			'post_title'  => $a[0],
			'post_status' => 'publish',
			'post_content'=> 'Dedicated real estate professional helping clients find the perfect property.',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_field( 'role', $a[1], $id );
			update_field( 'phone', $a[2], $id );
			update_field( 'email', $a[3], $id );
			update_field( 'social_links', array( 'facebook' => 'https://facebook.com', 'instagram' => 'https://instagram.com', 'linkedin' => 'https://linkedin.com', 'x' => 'https://x.com' ), $id );
			$agent_ids[] = $id;
			$summary['agents']++;
		}
	}

	// Testimonials.
	$testimonials = array(
		array( 'Exceptional Service!', 'Our experience with Estatein was outstanding. Their team\'s dedication and professionalism made finding our dream home a breeze. Highly recommended!', 'Wade Warren', 'USA, California' ),
		array( 'Efficient and Reliable', 'Estatein provided us with top-notch service. They helped us sell our property quickly and at a great price. We couldn\'t be happier with the results.', 'Emelie Thomson', 'USA, Florida' ),
		array( 'Trusted Advisors', 'The Estatein team guided us through the entire buying process. Their knowledge and commitment to our needs were impressive. Thank you for your support!', 'John Mans', 'USA, Nevada' ),
		array( 'A Great Experience', 'From start to finish, Estatein made buying our first home simple and stress-free. We felt supported at every step.', 'Sophie Bennett', 'USA, Texas' ),
		array( 'Highly Professional', 'Responsive, honest, and incredibly knowledgeable about the local market. We secured a fantastic deal thanks to them.', 'Michael Reed', 'USA, Illinois' ),
		array( 'Above and Beyond', 'They handled every detail with care. The whole process felt effortless and genuinely enjoyable.', 'Ava Johnson', 'USA, Oregon' ),
	);
	foreach ( $testimonials as $ti => $tm ) {
		$id = wp_insert_post( array(
			'post_type'   => 'testimonial',
			'post_title'  => $tm[0],
			'post_status' => 'publish',
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_field( 'quote', $tm[1], $id );
			update_field( 'author_name', $tm[2], $id );
			update_field( 'author_role', $tm[3], $id );
			update_field( 'rating', 5, $id );
			$re_aimg = re_seed_image_id( 'avatar-' . ( ( $ti % 3 ) + 1 ) . '.webp' );
			if ( $re_aimg ) {
				set_post_thumbnail( $id, $re_aimg );
			}
			$summary['testimonials']++;
		}
	}

	// FAQs.
	$faqs = array(
		array( 'How do I search for properties on Estatein?', 'Use the search bar or the Properties page to filter by location, price, type, and more. Save your favourites to compare them side by side.' ),
		array( 'What documents do I need to sell my property through Estatein?', 'Typically proof of ownership, a valid ID, and any relevant compliance certificates. Your Estatein agent will confirm the exact list for your area.' ),
		array( 'How can I contact an Estatein agent?', 'Reach out from the Contact page, call our office, or message an agent directly on any listing. We reply within one business day.' ),
		array( 'Is Estatein available in my area?', 'We operate across all major neighbourhoods and are expanding fast. Enter your location in search to see live listings near you.' ),
		array( 'Are there any fees for buyers?', 'Browsing and enquiring is completely free. Any transaction fees are disclosed upfront with full transparency — no hidden costs.' ),
		array( 'Can I schedule a property viewing online?', 'Yes. Pick an available slot on any listing and confirm — you will get an instant confirmation and a reminder before your visit.' ),
	);
	foreach ( $faqs as $f ) {
		$id = wp_insert_post( array(
			'post_type'    => 'faq',
			'post_title'   => $f[0],
			'post_status'  => 'publish',
			'post_content' => $f[1],
		) );
		if ( $id && ! is_wp_error( $id ) ) {
			update_field( 'answer_teaser', wp_trim_words( $f[1], 18 ), $id );
			$summary['faqs']++;
		}
	}

	// Properties. First three mirror the Figma Featured Properties cards.
	$properties = array(
		array(
			'title'       => 'Seaside Serenity Villa',
			'description' => 'A stunning 4-bedroom, 3-bathroom villa in a peaceful seaside neighborhood. Wake up to ocean air, spacious interiors, and private outdoor living designed for relaxed luxury.',
			'price'       => 550000,
			'bedrooms'    => 4,
			'bathrooms'   => 3,
			'area_sqft'   => 2500,
			'type'        => 'Villa',
			'location'    => 'Seaside',
		),
		array(
			'title'       => 'Metropolitan Haven',
			'description' => 'A chic and fully furnished 2-bedroom apartment with panoramic city views. Modern finishes, walkable amenities, and a refined plan make this a standout urban retreat.',
			'price'       => 550000,
			'bedrooms'    => 2,
			'bathrooms'   => 2,
			'area_sqft'   => 1500,
			'type'        => 'Apartment',
			'location'    => 'Downtown',
		),
		array(
			'title'       => 'Rustic Retreat Cottage',
			'description' => 'An elegant 3-bedroom cottage surrounded by calm hillside views. Warm materials, generous living space, and a private garden create a peaceful everyday escape.',
			'price'       => 550000,
			'bedrooms'    => 3,
			'bathrooms'   => 3,
			'area_sqft'   => 1800,
			'type'        => 'House',
			'location'    => 'Hillside',
		),
		array( 'title' => 'Modern Downtown Apartment', 'price' => 425000, 'bedrooms' => 2, 'bathrooms' => 1, 'area_sqft' => 980, 'type' => 'Apartment', 'location' => 'Downtown' ),
		array( 'title' => 'Skyline Penthouse Suite', 'price' => 875000, 'bedrooms' => 3, 'bathrooms' => 3, 'area_sqft' => 2100, 'type' => 'Penthouse', 'location' => 'Downtown' ),
		array( 'title' => 'Minimalist City Studio', 'price' => 210000, 'bedrooms' => 1, 'bathrooms' => 1, 'area_sqft' => 720, 'type' => 'Studio', 'location' => 'Old Town' ),
		array( 'title' => 'Executive Office Space', 'price' => 625000, 'bedrooms' => 2, 'bathrooms' => 2, 'area_sqft' => 1860, 'type' => 'Office', 'location' => 'Downtown' ),
		array( 'title' => 'Garden Family Home', 'price' => 495000, 'bedrooms' => 4, 'bathrooms' => 2, 'area_sqft' => 2200, 'type' => 'House', 'location' => 'Suburbs' ),
		array( 'title' => 'Beachfront Condo', 'price' => 715000, 'bedrooms' => 3, 'bathrooms' => 2, 'area_sqft' => 1740, 'type' => 'Apartment', 'location' => 'Seaside' ),
	);
	$statuses = array( 'For Sale', 'For Rent', 'Sold' );
	foreach ( $properties as $i => $property ) {
		$title       = $property['title'];
		$description = $property['description'] ?? 'This stunning property offers spacious interiors, premium finishes, and an unbeatable location. Floor-to-ceiling windows fill the home with natural light, while the open-plan layout is perfect for modern living and entertaining.';
		$id = wp_insert_post( array(
			'post_type'    => 'property',
			'post_title'   => $title,
			'post_status'  => 'publish',
			'post_content' => $description,
			'menu_order'   => $i + 1,
		) );
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		update_field( 'price', $property['price'], $id );
		update_field( 'price_period', '', $id );
		update_field( 'bedrooms', $property['bedrooms'], $id );
		update_field( 'bathrooms', $property['bathrooms'], $id );
		update_field( 'area_sqft', $property['area_sqft'], $id );
		update_field( 'garage', $i % 3, $id );
		update_field( 'year_built', 2012 + ( $i % 10 ), $id );
		update_field( 'address', ( 100 + $i ) . ' ' . $property['location'] . ' Ave', $id );
		update_field( 'amenities', array(
			'air_conditioning' => true,
			'heating'          => true,
			'parking'          => 0 === $i % 2,
			'swimming_pool'    => 0 === $i % 3,
			'gym'              => 0 === $i % 4,
			'security'         => true,
			'balcony'          => true,
			'elevator'         => 0 === $i % 2,
		), $id );
		if ( $agent_ids ) {
			update_field( 'agent', $agent_ids[ $i % count( $agent_ids ) ], $id );
		}
		wp_set_object_terms( $id, $property['type'], 'property_type' );
		wp_set_object_terms( $id, $property['location'], 'property_location' );
		wp_set_object_terms( $id, $statuses[ $i % count( $statuses ) ], 'property_status' );
		$re_pimg = re_seed_image_id( 'property-' . ( ( $i % 3 ) + 1 ) . '.webp' );
		if ( $re_pimg ) {
			set_post_thumbnail( $id, $re_pimg );
		}
		$summary['properties']++;
	}

	// Home page sections.
	$front = (int) get_option( 'page_on_front' );
	if ( $front ) {
		update_field( 'hero_eyebrow', '', $front );
		update_field( 'hero_heading', 'Discover Your Dream Property with Estatein', $front );
		update_field( 'hero_subcopy', 'Your journey to finding the perfect property begins here. Explore our listings to find the home that matches your dreams.', $front );
		update_field( 'hero_primary_btn', array( 'title' => 'Browse Properties', 'url' => get_post_type_archive_link( 'property' ), 'target' => '' ), $front );
		update_field( 'hero_secondary_btn', array( 'title' => 'Learn More', 'url' => home_url( '/about-us/' ), 'target' => '' ), $front );
		update_field( 'hero_show_search', 0, $front );
		$re_hero_img = re_seed_static_image_id( 'hero-building.webp' );
		if ( $re_hero_img ) {
			update_field( 'hero_image', $re_hero_img, $front );
		}
		update_field( 'hero_kpis', array(
			'kpi_1_value' => '200+', 'kpi_1_label' => 'Happy Customers',
			'kpi_2_value' => '10k+', 'kpi_2_label' => 'Properties For Clients',
			'kpi_3_value' => '16+', 'kpi_3_label' => 'Years of Experience',
		), $front );
		$re_existing_vp = (array) re_field( 'hero_valueprops', $front );
		update_field( 'hero_valueprops', array(
			'vp_1_icon' => $re_existing_vp['vp_1_icon'] ?? '',
			'vp_1_title' => 'Find Your Dream Home', 'vp_1_text' => 'Explore a curated selection of homes matched to your lifestyle and budget.',
			'vp_2_icon' => $re_existing_vp['vp_2_icon'] ?? '',
			'vp_2_title' => 'Unlock Property Value', 'vp_2_text' => 'Get an accurate, data-driven valuation and sell for what your property is worth.',
			'vp_3_icon' => $re_existing_vp['vp_3_icon'] ?? '',
			'vp_3_title' => 'Effortless Property Management', 'vp_3_text' => 'We handle listings, tenants, and paperwork so you do not have to.',
			'vp_4_icon' => $re_existing_vp['vp_4_icon'] ?? '',
			'vp_4_title' => 'Smart Investments, Informed Decisions', 'vp_4_text' => 'Market insights and expert guidance to grow your real estate portfolio.',
		), $front );
		update_field( 'featured_eyebrow', 'Handpicked', $front );
		update_field( 'featured_heading', 'Featured Properties', $front );
		update_field( 'featured_subcopy', 'Explore our handpicked selection of featured properties. Each listing offers a glimpse into exceptional homes and investments available through Estatein.', $front );
		update_field( 'featured_count', 3, $front );
		update_field( 'categories_heading', 'Browse by Category', $front );
		update_field( 'categories_subcopy', 'Find the property type that fits your lifestyle.', $front );
		update_field( 'stats_heading', 'Trusted by thousands', $front );
		update_field( 'stats', array(
			'stat_1_value' => '1200', 'stat_1_label' => 'Properties Sold',
			'stat_2_value' => '850', 'stat_2_label' => 'Happy Families',
			'stat_3_value' => '98', 'stat_3_label' => '% Satisfaction',
			'stat_4_value' => '15', 'stat_4_label' => 'Years of Trust',
		), $front );
		update_field( 'about_eyebrow', 'Why choose us', $front );
		update_field( 'about_heading', 'A smarter way to buy, sell, and rent', $front );
		update_field( 'about_body', '<p>We combine deep local expertise with a client-first approach to make every move effortless and rewarding.</p>', $front );
		update_field( 'about_features', array(
			'feature_1_title' => 'Verified Listings', 'feature_1_text' => 'Every property is checked for accuracy.',
			'feature_2_title' => 'Expert Agents', 'feature_2_text' => 'Local specialists who know the market.',
			'feature_3_title' => 'Transparent Pricing', 'feature_3_text' => 'No hidden fees, ever.',
			'feature_4_title' => 'End-to-End Support', 'feature_4_text' => 'We guide you from search to keys.',
		), $front );
		update_field( 'services_heading', 'What we offer', $front );
		update_field( 'services_subcopy', 'Full-service real estate, tailored to you.', $front );
		update_field( 'services', array(
			'service_1_icon' => 'search', 'service_1_title' => 'Buy a Home', 'service_1_text' => 'Find and secure your ideal property.',
			'service_2_icon' => 'pin', 'service_2_title' => 'Rent a Home', 'service_2_text' => 'Flexible rentals in prime locations.',
			'service_3_icon' => 'star', 'service_3_title' => 'Sell a Home', 'service_3_text' => 'Get the best value for your property.',
			'service_4_icon' => 'check', 'service_4_title' => 'Property Management', 'service_4_text' => 'We handle the details for you.',
		), $front );
		update_field( 'testimonials_heading', 'What Our Clients Say', $front );
		update_field( 'testimonials_subcopy', 'Read the success stories and heartfelt testimonials from our valued clients. Discover why they chose Estatein for their real estate needs.', $front );
		update_field( 'faq_heading', 'Frequently Asked Questions', $front );
		update_field( 'faq_subcopy', "Find answers to common questions about Estatein's services, property listings, and the real estate process. We're here to provide clarity and assist you every step of the way.", $front );
		update_field( 'agents_heading', 'Meet our agents', $front );
		update_field( 'agents_subcopy', 'A team dedicated to your success.', $front );
		update_field( 'blog_heading', 'From the blog', $front );
		update_field( 'blog_subcopy', 'Guides, tips, and market insights.', $front );
		update_field( 'cta_heading', 'Start Your Real Estate Journey Today', $front );
		update_field( 'cta_subcopy', "Your dream property is just a click away. Whether you're looking for a new home, a strategic investment, or expert real estate advice, Estatein is here to assist you every step of the way.", $front );
		update_field( 'cta_button', array( 'title' => 'Explore Properties', 'url' => get_post_type_archive_link( 'property' ), 'target' => '' ), $front );
	}

	// Site settings.
	$settings = function_exists( 're_settings_page_id' ) ? re_settings_page_id() : 0;
	if ( $settings ) {
		update_field( 'header_cta', array( 'title' => 'Contact Us', 'url' => home_url( '/contact/' ), 'target' => '' ), $settings );
		update_field( 'contact_phone', '+1 555 0100', $settings );
		update_field( 'contact_email', 'hello@estatein.com', $settings );
		update_field( 'contact_address', "123 Market Street\nMetropolis, 10001", $settings );
		update_field( 'office_hours', 'Mon–Fri, 9am–6pm', $settings );
		update_field( 'social_links', array( 'facebook' => 'https://facebook.com', 'linkedin' => 'https://linkedin.com', 'x' => 'https://x.com', 'youtube' => 'https://youtube.com', 'instagram' => 'https://instagram.com' ), $settings );
		update_field( 'footer_about', 'Your trusted partner in finding the perfect property.', $settings );
		update_field( 'footer_links_heading', 'Quick Links', $settings );
		update_field( 'footer_copyright', '@2023 Estatein. All Rights Reserved.', $settings );
	}

	update_option( 're_seeded', gmdate( 'c' ) );
	return $summary;
}

/**
 * Tools → RE Seed Demo admin page.
 */
function re_seed_admin_menu(): void {
	if ( ! re_seed_allowed() ) {
		return;
	}
	add_management_page( __( 'RE Seed Demo', 'realestate' ), __( 'RE Seed Demo', 'realestate' ), 'manage_options', 're-seed', 're_seed_admin_page' );
}
add_action( 'admin_menu', 're_seed_admin_menu' );

/**
 * Render + handle the seeder admin page.
 */
function re_seed_admin_page(): void {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$done = '';
	if ( isset( $_POST['re_seed_go'] ) && check_admin_referer( 're_seed' ) ) {
		$summary = re_seed_run( true );
		$done    = sprintf( 'Seeded: %d properties, %d agents, %d testimonials, %d FAQs.', $summary['properties'] ?? 0, $summary['agents'] ?? 0, $summary['testimonials'] ?? 0, $summary['faqs'] ?? 0 );
	}
	echo '<div class="wrap"><h1>' . esc_html__( 'RE Seed Demo', 'realestate' ) . '</h1>';
	echo '<p>' . esc_html__( 'Populates demo properties, agents, testimonials, home sections, and site settings. Dev use only.', 'realestate' ) . '</p>';
	if ( $done ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $done ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 're_seed' );
	echo '<p><button class="button button-primary" name="re_seed_go" value="1">' . esc_html__( 'Run Seeder (recreate)', 'realestate' ) . '</button></p>';
	echo '</form></div>';
}

// WP-CLI: php wp-cli.phar re seed [--force]
if ( defined( 'WP_CLI' ) && WP_CLI ) {
	WP_CLI::add_command( 're seed', function ( $args, $assoc ) {
		$summary = re_seed_run( isset( $assoc['force'] ) );
		WP_CLI::success( 'Seeded ' . wp_json_encode( $summary ) );
	} );
}
