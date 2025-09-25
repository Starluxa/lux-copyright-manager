<?php
/**
 * Rendering engine for Lux Copyright Manager.
 *
 * This file contains the core rendering function that generates the HTML
 * output for the copyright notice based on provided attributes.
 *
 * @package lux-copyright-manager
 */

/**
 * This file contains the core rendering engine for the Lux Copyright Manager plugin.
 *
 * The lcm_lux_get_copyright_html() function is the central rendering engine that
 * generates HTML output for both the shortcode and block implementations. This
 * single function ensures consistency between both methods while providing
 * extensive customization options. It handles:
 *
 * 1. Date Processing:
 *    - Automatic year updating using GMT time for consistency
 *    - Date range support (e.g., "2020-2025")
 *    - Customizable date separators
 *
 * 2. Content Composition:
 *    - Copyright symbol display
 *    - Customizable text before and after the date
 *    - Site title and tagline integration
 *    - Privacy policy link generation
 *    - Custom URL support for both site title and privacy link
 *
 * 3. Advanced Features:
 *    - SEO Schema.org structured data implementation
 *    - Background image styling support
 *    - CSS class management for consistent styling
 *    - HTML escaping for security
 *    - Responsive markup generation
 *
 * 4. Key Implementation Details:
 *    - Uses GMT time for consistent year display across servers
 *    - Implements proper HTML escaping with esc_html() and wp_kses_post()
 *    - Supports Schema.org microdata for search engine optimization
 *    - Handles backward compatibility for attribute naming
 *    - Provides fallbacks for missing data (e.g., default privacy text)
 *    - Generates semantic HTML with proper wrapper elements
 *
 * The function accepts a comprehensive array of attributes that control all
 * aspects of the copyright display, making it highly flexible while maintaining
 * a single source of truth for the rendering logic. This approach eliminates
 * duplication between the shortcode and block implementations.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates the copyright HTML based on a set of attributes.
 * This is the central rendering engine for the entire plugin.
 *
 * @param array $attributes The combined block and global attributes.
 * @return string The rendered HTML of the copyright notice.
 */
function lcm_lux_get_copyright_html( $attributes ) {
	// Timezone handling: Currently hardcoded to server time.
	// Future enhancement could support visitor timezone detection.
	$timezone_type = 'server';

	// Build CSS styles for the wrapper element.
	// Priority: custom wrapper styles > background image > no styling.
	$style_attr = '';
	if ( ! empty( $attributes['wrapper_styles'] ) ) {
		$style_attr = $attributes['wrapper_styles'];
	} elseif ( ! empty( $attributes['bgImageUrl'] ) ) {
		$style_attr = 'background-image: url(' . esc_url( $attributes['bgImageUrl'] ) . '); background-size: cover; background-position: center;';
	}

	// Prepare wrapper element attributes including styles and Schema.org data.
	$wrapper_args = array( 'style' => $style_attr );
	// Add the block-specific CSS class to ensure consistent styling between block and shortcode
	$wrapper_args['class'] = 'wp-block-lux-copyright-year';
	
	if ( ! empty( $attributes['enableSchema'] ) ) {
		// Add Schema.org structured data attributes for SEO enhancement.
		// itemscope indicates this element contains item properties.
		// itemtype specifies the vocabulary (CreativeWork for copyright content).
		$wrapper_args['itemscope'] = '';
		$wrapper_args['itemtype']  = 'https://schema.org/CreativeWork';
	}
	$wrapper_attributes = get_block_wrapper_attributes( $wrapper_args );

	if ( 'visitor' === $timezone_type ) {
		// Future enhancement: Detect and use visitor's local timezone.
		// For now, fall back to server time to maintain functionality.
		return '<p ' . $wrapper_attributes . '>' . esc_html__( 'Placeholder for Visitor Time', 'lux-copyright-manager' ) . '</p>';
	} else {
		// Server-side rendering: Use GMT time for consistent copyright dates.
		$current_year = gmdate( 'Y' );
		$separator    = isset( $attributes['customSeparator'] ) ? $attributes['customSeparator'] : '–';
		$display_date = $current_year;

		if ( ! empty( $attributes['showStartingYear'] ) && ! empty( $attributes['startingYear'] ) ) {
			$display_date = esc_html( $attributes['startingYear'] ) . $separator . $current_year;
		}

		$before_text = ! empty( $attributes['customBeforeText'] ) ? $attributes['customBeforeText'] . ' ' : '';
		$after_text  = ! empty( $attributes['customAfterText'] ) ? ' ' . $attributes['customAfterText'] : '';
		$symbol      = ! empty( $attributes['showSymbol'] ) ? '© ' : '';

		$site_title_html = '';
		if ( ! empty( $attributes['showSiteTitle'] ) ) {
			$site_title = get_bloginfo( 'name' );
			if ( ! empty( $attributes['linkSiteTitle'] ) ) {
				// Check if custom URL is provided and not empty
				// Handle both naming conventions for backward compatibility
				if ( ! empty( $attributes['customSiteTitleUrl'] ) ) {
					$link_url = $attributes['customSiteTitleUrl'];
				} elseif ( ! empty( $attributes['customSiteTitleURL'] ) ) {
					// Fallback for uppercase naming convention
					$link_url = $attributes['customSiteTitleURL'];
				} else {
					$link_url = home_url( '/' );
				}
				$site_title_html = ' <a href="' . esc_url( $link_url ) . '">' . esc_html( $site_title ) . '</a>';
			} else {
				$site_title_html = ' ' . esc_html( $site_title );
			}
		}

		$tagline_html = '';
		if ( ! empty( $attributes['showTagline'] ) ) {
			$tagline = get_bloginfo( 'description' );
			if ( $tagline ) {
				// Add tagline with separator for visual hierarchy.
				// Only show if tagline exists to avoid empty separators.
				$tagline_html = ' - ' . esc_html( $tagline );
			}
		}

		$privacy_link_html = '';
		if ( ! empty( $attributes['showPrivacyLink'] ) ) {
			if ( ! empty( $attributes['linkPrivacyLink'] ) ) {
				// Check if custom URL is provided and not empty
				if ( ! empty( $attributes['customPrivacyUrl'] ) ) {
					$link_url = $attributes['customPrivacyUrl'];
				} else {
					$link_url = get_privacy_policy_url();
				}
				
				// Only display the privacy link if we have a valid URL
				if ( ! empty( $link_url ) ) {
					$link_text = ! empty( $attributes['customPrivacyText'] )
						? $attributes['customPrivacyText']
						: wp_strip_all_tags( get_the_privacy_policy_link( '', '' ) );

					if ( ! $link_text ) {
						$link_text = esc_html__( 'Privacy Policy', 'lux-copyright-manager' );
					}

					$privacy_link_html = ' | <a href="' . esc_url( $link_url ) . '">' . esc_html( $link_text ) . '</a>';
				} else {
					// If no valid URL, just show the text without a link
					$link_text = ! empty( $attributes['customPrivacyText'] )
						? $attributes['customPrivacyText']
						: esc_html__( 'Privacy Policy', 'lux-copyright-manager' );
					$privacy_link_html = ' | ' . esc_html( $link_text );
				}
			} else {
				// Not linking, just show the text
				$link_text = ! empty( $attributes['customPrivacyText'] )
					? $attributes['customPrivacyText']
					: esc_html__( 'Privacy Policy', 'lux-copyright-manager' );
				$privacy_link_html = ' | ' . esc_html( $link_text );
			}
		}

		// Prepare the year/date portion of the copyright notice.
		$year_html = esc_html( $display_date );
		if ( ! empty( $attributes['enableSchema'] ) ) {
			$year_html = '<span itemprop="copyrightYear">' . $year_html . '</span>';
		}

	$holder_html = $site_title_html . $tagline_html;
		if ( ! empty( $attributes['enableSchema'] ) && trim( $holder_html ) !== '' ) {
			// Wrap copyright holder information with Schema.org microdata.
			// Only add the itemprop if there's actual content to avoid empty spans.
			// This helps search engines understand who holds the copyright.
			$holder_html = '<span itemprop="copyrightHolder">' . $holder_html . '</span>';
		}

		// Finally, build the string:
		$before_text_escaped = esc_html( $before_text );
		$symbol_escaped = esc_html( $symbol );
		$after_text_escaped = esc_html( $after_text );
		
		$final_html = $before_text_escaped . $symbol_escaped . $year_html . $after_text_escaped . $holder_html . $privacy_link_html;

		return '<p ' . $wrapper_attributes . '>' . wp_kses_post( $final_html ) . '</p>';
	}
}