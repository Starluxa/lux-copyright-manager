<?php
/**
 * Shortcode handler for Lux Copyright Manager.
 *
 * This file contains the main shortcode handler function that processes
 * [lux_copyright_manager] shortcodes and generates the appropriate HTML output.
 *
 * @package lux-copyright-manager
 */

/**
 * This file handles the shortcode implementation for the Lux Copyright Manager plugin.
 *
 * The [lux_copyright_manager] shortcode provides a flexible way to add automatically
 * updating copyright information to WordPress content. This file contains:
 *
 * 1. lcm_lux_copyright_shortcode() - The main shortcode handler function that:
 *    - Processes shortcode attributes with sensible defaults
 *    - Maps shortcode attributes (snake_case) to block attributes (camelCase)
 *    - Converts string boolean values to actual booleans for consistency
 *    - Handles automatic linking behavior for site title and privacy link
 *    - Uses the shared rendering engine from render.php
 *    - Wraps output in a consistent CSS class for styling
 *    - Implements error handling with fallback messages
 *
 * 2. Key Features:
 *    - Extensive customization options (date ranges, site info, privacy link, etc.)
 *    - Consistent behavior between shortcode and block implementations
 *    - Proper boolean value handling from shortcode attributes
 *    - Automatic linking behavior that matches the block implementation
 *    - Error handling with logging when WP_DEBUG is enabled
 *    - Shared rendering engine with the block implementation
 *
 * 3. Supported Attributes:
 *    - show_symbol: Display the copyright symbol (©)
 *    - show_starting_year: Enable date range display
 *    - starting_year: Starting year for date ranges
 *    - show_site_title: Display the site title
 *    - link_site_title: Make site title a link
 *    - custom_site_title_url: Custom URL for site title link
 *    - show_tagline: Display the site tagline
 *    - show_privacy_link: Display a privacy policy link
 *    - link_privacy_link: Make privacy link a link
 *    - custom_privacy_url: Custom URL for privacy link
 *    - custom_privacy_text: Custom text for privacy link
 *    - custom_separator: Separator for date ranges
 *    - custom_before_text: Text before the copyright date
 *    - custom_after_text: Text after the copyright date
 *    - enable_schema: Enable SEO Schema.org structured data
 *
 * The shortcode integrates seamlessly with the block implementation by using
 * the same rendering engine, ensuring consistency across both methods.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shortcode handler for [lux_copyright_manager]
 *
 * Processes the lux_copyright_manager shortcode and generates the copyright HTML output.
 *
 * @param array $atts Shortcode attributes.
 * @return string HTML output of the copyright notice.
 */
function lcm_lux_copyright_shortcode( $atts ) {
	// Load the attribute mapping and rendering functions
	if ( ! file_exists( __DIR__ . '/attributes.php' ) || ! file_exists( __DIR__ . '/render.php' ) ) {
		return '<!-- Lux Copyright Manager shortcode error: Missing required files -->';
	}
	
	require_once __DIR__ . '/attributes.php';
	require_once __DIR__ . '/render.php';

	// Parse shortcode attributes with defaults
	$atts = shortcode_atts(
		array(
			'show_symbol'         => true,
			'show_starting_year'  => false,
			'starting_year'       => '',
			'show_site_title'     => false,
			'link_site_title'     => null,
			'custom_site_title_url' => '',
			'show_tagline'        => false,
			'show_privacy_link'   => false,
			'link_privacy_link'   => null,
			'custom_privacy_url'  => '',
			'custom_privacy_text' => '',
			'custom_separator'    => '–',
			'custom_before_text'  => 'Copyright',
			'custom_after_text'   => '',
			'enable_schema'       => false,
		),
		$atts,
		'lux_copyright'
	);

	// Map shortcode attributes (snake_case) to block attributes (camelCase)
	$mapped_atts = lcm_lux_map_shortcode_atts( $atts );

	// Convert string boolean values to actual booleans (using the mapped keys)
	$boolean_fields = array( 'showSymbol', 'showStartingYear', 'showSiteTitle', 'linkSiteTitle', 'showTagline', 'showPrivacyLink', 'linkPrivacyLink', 'enableSchema' );
	foreach ( $boolean_fields as $field ) {
		if ( isset( $mapped_atts[ $field ] ) && ( is_string( $mapped_atts[ $field ] ) || is_null( $mapped_atts[ $field ] ) ) ) {
			$mapped_atts[ $field ] = filter_var( $mapped_atts[ $field ], FILTER_VALIDATE_BOOLEAN );
		}
	}

	// Automatically link site title when showSiteTitle is enabled (to match block behavior)
	if ( ! empty( $mapped_atts['showSiteTitle'] ) && ( ! isset( $atts['link_site_title'] ) || $atts['link_site_title'] === null ) ) {
		$mapped_atts['linkSiteTitle'] = true;
	}

	// Automatically link privacy link when showPrivacyLink is enabled (to match block behavior)
	if ( ! empty( $mapped_atts['showPrivacyLink'] ) && ( ! isset( $atts['link_privacy_link'] ) || $atts['link_privacy_link'] === null ) ) {
		$mapped_atts['linkPrivacyLink'] = true;
	}

	// Use the same rendering function as the block
	try {
		$html = lcm_lux_get_copyright_html( $mapped_atts );
		// Add a wrapper div with block classes to ensure proper alignment
		return '<div class="wp-block-lux-copyright-year">' . $html . '</div>';
	} catch ( Exception $e ) {
		// Return a fallback message
		return '<!-- Lux Copyright Manager shortcode error -->';
	}
}

// Register the shortcode
add_shortcode( 'lux_copyright_manager', 'lcm_lux_copyright_shortcode' );