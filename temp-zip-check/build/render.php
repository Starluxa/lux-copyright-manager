<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * PHP file to use when rendering the block type on the server to show on the front end.
 *
 * @package lux-copyright-manager
 */

/**
 * This file handles server-side rendering for the Lux Copyright Manager block.
 *
 * When the block is displayed on the frontend of a WordPress site, this file
 * is responsible for generating the HTML output. It serves as a bridge between
 * the block editor's saved attributes and the shared rendering engine. Key
 * responsibilities include:
 *
 * 1. Server-Side Rendering:
 *    - Acts as the entry point for block rendering on the frontend
 *    - Merges block attributes with global settings
 *    - Processes background image styling
 *    - Delegates to the shared lcm_lux_get_copyright_html() function
 *
 * 2. Key Features:
 *    - Lazy loading of the shared rendering function
 *    - Global settings management
 *    - Background image CSS generation
 *    - Attribute merging for consistent behavior
 *    - Direct output of rendered HTML
 *
 * 3. Implementation Details:
 *    - Checks if lux_get_copyright_html() exists before including it
 *    - Merges global settings with block-specific attributes
 *    - Processes background image URLs into CSS styles
 *    - Uses WordPress escaping functions for security
 *    - Maintains consistency with shortcode implementation
 *
 * This approach ensures that both the block and shortcode implementations
 * share the same rendering logic, reducing code duplication and ensuring
 * consistent output regardless of which method is used to add copyright
 * information to a site.
 */

if ( ! function_exists( 'lcm_lux_get_copyright_html' ) ) {
	require_once __DIR__ . '/../includes/shortcode/render.php';
}

$global_settings = array(
	'timezone_type' => 'server',
);

$merged_attributes = array_merge( $global_settings, $attributes );

// Handle background image styling
$wrapper_styles = array();
if ( ! empty( $attributes['bgImageUrl'] ) ) {
    $wrapper_styles[] = 'background-image: url(' . esc_url( $attributes['bgImageUrl'] ) . ');';
    $wrapper_styles[] = 'background-size: cover;';
    $wrapper_styles[] = 'background-position: center;';
}

$merged_attributes['wrapper_styles'] = implode( ' ', $wrapper_styles );

echo wp_kses_post( lcm_lux_get_copyright_html( $merged_attributes ) );
