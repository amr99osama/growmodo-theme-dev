<?php
/**
 * SEO: meta tags, canonical, robots, and JSON-LD structured data.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plain-text excerpt trimmed for meta tags.
 *
 * @param string $text Raw text.
 * @param int    $max  Maximum length.
 * @return string
 */
function re_seo_trim( string $text, int $max = 155 ): string {
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( $text ) ) );
	if ( strlen( $text ) <= $max ) {
		return $text;
	}
	return rtrim( substr( $text, 0, $max - 1 ), " \t\n\r\0\x0B.,;:-" ) . '…';
}

/**
 * Current page meta description.
 *
 * @return string
 */
function re_seo_description(): string {
	if ( is_front_page() ) {
		return re_seo_trim( (string) re_field( 'hero_subcopy', get_option( 'page_on_front' ) ) ?: __( 'Explore premium properties, expert real estate services, trusted agents, and helpful guidance for buying, selling, or investing with Estatein.', 'realestate' ) );
	}

	if ( is_singular() ) {
		$post = get_post();
		if ( $post ) {
			if ( is_page_template( 'page-contact.php' ) ) {
				return __( 'Contact Estatein to speak with our real estate team, schedule a consultation, or get answers about buying, selling, and property services.', 'realestate' );
			}
			$source = has_excerpt( $post ) ? get_the_excerpt( $post ) : $post->post_content;
			if ( $source ) {
				return re_seo_trim( $source );
			}
			return re_seo_trim( sprintf( __( 'Learn more about %1$s at Estatein, including helpful real estate guidance, services, listings, and ways to connect with our team.', 'realestate' ), get_the_title( $post ) ) );
		}
	}

	if ( is_post_type_archive( 'property' ) ) {
		return __( 'Browse Estatein property listings by type, location, price, bedrooms, and availability to find your next home or investment.', 'realestate' );
	}

	if ( is_post_type_archive( 'agent' ) ) {
		return __( 'Meet Estatein real estate agents and connect with local experts who can help you buy, sell, rent, or invest confidently.', 'realestate' );
	}

	if ( is_post_type_archive( 'faq' ) ) {
		return __( 'Frequently asked questions about Estatein — searching, buying, selling, property services, and contacting an agent.', 'realestate' );
	}

	if ( is_tax() ) {
		$term = get_queried_object();
		if ( $term instanceof WP_Term && $term->description ) {
			return re_seo_trim( $term->description );
		}
	}

	return re_seo_trim( get_bloginfo( 'description' ) ?: __( 'Estatein helps buyers, sellers, and investors discover exceptional real estate opportunities with clarity and confidence.', 'realestate' ) );
}

/**
 * Brand logo URL (used in Organization schema and as a social fallback).
 *
 * @return string
 */
function re_seo_logo(): string {
	return RE_URI . '/assets/img/logo.svg';
}

/**
 * Best social image for the current page.
 *
 * @return string
 */
function re_seo_image(): string {
	if ( is_singular() && has_post_thumbnail() ) {
		$image = get_the_post_thumbnail_url( get_the_ID(), 're_hero' );
		if ( $image ) {
			return $image;
		}
	}

	$front_image = re_field( 'hero_image', get_option( 'page_on_front' ) );
	if ( is_array( $front_image ) && ! empty( $front_image['url'] ) ) {
		return (string) $front_image['url'];
	}

	return RE_URI . '/assets/img/hero-building.webp';
}

/**
 * Canonical URL for the current request.
 *
 * @return string
 */
function re_seo_canonical(): string {
	if ( is_front_page() ) {
		return home_url( '/' );
	}
	if ( is_singular() ) {
		return (string) get_permalink();
	}
	if ( is_post_type_archive() ) {
		$pt = get_query_var( 'post_type' );
		$pt = is_array( $pt ) ? reset( $pt ) : $pt;
		$link = $pt ? get_post_type_archive_link( $pt ) : '';
		if ( $link ) {
			return (string) $link;
		}
	}
	if ( is_tax() || is_category() || is_tag() ) {
		$link = get_term_link( get_queried_object() );
		if ( ! is_wp_error( $link ) ) {
			return (string) $link;
		}
	}
	return home_url( add_query_arg( array(), $GLOBALS['wp']->request ?? '' ) );
}

/**
 * Print SEO meta tags. Owns the canonical (WP core's rel_canonical is removed).
 */
function re_print_seo_tags(): void {
	if ( is_admin() || is_404() ) {
		return;
	}

	$title       = wp_get_document_title();
	$description = re_seo_description();
	$url         = trailingslashit( re_seo_canonical() );
	$image       = re_seo_image();

	printf( '<link rel="canonical" href="%s">' . "\n", esc_url( $url ) );
	printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:type" content="%s">' . "\n", is_singular() && ! is_front_page() ? 'article' : 'website' );
	printf( '<meta property="og:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta property="og:url" content="%s">' . "\n", esc_url( $url ) );
	printf( '<meta property="og:site_name" content="%s">' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:image" content="%s">' . "\n", esc_url( $image ) );
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	printf( '<meta name="twitter:title" content="%s">' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s">' . "\n", esc_attr( $description ) );
	printf( '<meta name="twitter:image" content="%s">' . "\n", esc_url( $image ) );
}
add_action( 'wp_head', 're_print_seo_tags', 4 );

// The theme owns the canonical for every page type — drop WP core's duplicate.
remove_action( 'wp_head', 'rel_canonical' );

/**
 * Keep public pages indexable and allow large image previews.
 *
 * @param array<string,mixed> $robots Robots directives.
 * @return array<string,mixed>
 */
function re_public_robots( array $robots ): array {
	if ( is_admin() || is_404() ) {
		return $robots;
	}

	unset( $robots['noindex'], $robots['nofollow'] );
	$robots['index']             = true;
	$robots['follow']            = true;
	$robots['max-image-preview'] = 'large';
	return $robots;
}
add_filter( 'wp_robots', 're_public_robots', 99 );

/**
 * Structured data (@graph): Organization + WebSite everywhere, plus page-specific
 * Product (property listing), FAQPage, and BreadcrumbList.
 */
function re_print_schema(): void {
	if ( is_admin() || is_404() ) {
		return;
	}

	$home  = home_url( '/' );
	$graph = array(
		array(
			'@type' => 'Organization',
			'@id'   => $home . '#organization',
			'name'  => get_bloginfo( 'name' ),
			'url'   => $home,
			'logo'  => array( '@type' => 'ImageObject', 'url' => re_seo_logo() ),
		),
		array(
			'@type'           => 'WebSite',
			'@id'             => $home . '#website',
			'name'            => get_bloginfo( 'name' ),
			'url'             => $home,
			'publisher'       => array( '@id' => $home . '#organization' ),
			'potentialAction' => array(
				'@type'       => 'SearchAction',
				'target'      => array( '@type' => 'EntryPoint', 'urlTemplate' => $home . '?s={search_term_string}' ),
				'query-input' => 'required name=search_term_string',
			),
		),
	);

	// Property listing → Product + Offer + specs.
	if ( is_singular( 'property' ) ) {
		$id      = (int) get_the_ID();
		$link    = get_permalink( $id );
		$price   = (float) re_field( 'price', $id );
		$listing = array(
			'@type'       => 'Product',
			'@id'         => $link . '#listing',
			'name'        => get_the_title( $id ),
			'description' => re_seo_description(),
			'url'         => $link,
			'image'       => re_seo_image(),
			'brand'       => array( '@type' => 'Brand', 'name' => get_bloginfo( 'name' ) ),
		);
		if ( $price > 0 ) {
			$listing['offers'] = array(
				'@type'         => 'Offer',
				'price'         => (string) $price,
				'priceCurrency' => 'USD',
				'availability'  => 'https://schema.org/InStock',
				'url'           => $link,
			);
		}
		$props = array();
		$beds  = (int) re_field( 'bedrooms', $id );
		$baths = (int) re_field( 'bathrooms', $id );
		$area  = (float) re_field( 'area_sqft', $id );
		$addr  = (string) re_field( 'address', $id );
		if ( $beds )  { $props[] = array( '@type' => 'PropertyValue', 'name' => 'Bedrooms', 'value' => $beds ); }
		if ( $baths ) { $props[] = array( '@type' => 'PropertyValue', 'name' => 'Bathrooms', 'value' => $baths ); }
		if ( $area )  { $props[] = array( '@type' => 'PropertyValue', 'name' => 'Floor area (sqft)', 'value' => $area ); }
		if ( $addr )  { $props[] = array( '@type' => 'PropertyValue', 'name' => 'Location', 'value' => $addr ); }
		if ( $props ) {
			$listing['additionalProperty'] = $props;
		}
		$graph[] = $listing;
	}

	// FAQ page (front page + faq archive) → FAQPage.
	if ( is_front_page() || is_post_type_archive( 'faq' ) ) {
		$faqs = get_posts( array(
			'post_type'      => 'faq',
			'posts_per_page' => 10,
			'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'ASC' ),
		) );
		$entities = array();
		foreach ( $faqs as $faq ) {
			$answer = re_field( 'answer_teaser', $faq->ID ) ?: $faq->post_content;
			$answer = trim( wp_strip_all_tags( (string) $answer ) );
			if ( ! $answer ) {
				continue;
			}
			$entities[] = array(
				'@type'          => 'Question',
				'name'           => get_the_title( $faq ),
				'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $answer ),
			);
		}
		if ( $entities ) {
			$graph[] = array( '@type' => 'FAQPage', 'mainEntity' => $entities );
		}
	}

	// Breadcrumbs on inner pages.
	if ( ! is_front_page() && ( is_singular() || is_post_type_archive() || is_tax() || is_category() || is_tag() ) ) {
		$crumbs = array( array( '@type' => 'ListItem', 'position' => 1, 'name' => __( 'Home', 'realestate' ), 'item' => $home ) );
		$pos    = 2;
		if ( is_singular( 'property' ) && get_post_type_archive_link( 'property' ) ) {
			$crumbs[] = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => __( 'Properties', 'realestate' ), 'item' => get_post_type_archive_link( 'property' ) );
		}
		$crumbs[] = array( '@type' => 'ListItem', 'position' => $pos, 'name' => wp_get_document_title() );
		$graph[]  = array( '@type' => 'BreadcrumbList', 'itemListElement' => $crumbs );
	}

	$schema = array( '@context' => 'https://schema.org', '@graph' => $graph );
	printf( '<script type="application/ld+json">%s</script>' . "\n", wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) );
}
add_action( 'wp_head', 're_print_schema', 30 );
