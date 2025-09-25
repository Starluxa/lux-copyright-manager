<?php
/**
 * Attribute mapping functions for Lux Copyright Manager.
 *
 * This file contains functions for mapping shortcode attributes from snake_case
 * to camelCase to maintain compatibility with the block implementation.
 *
 * @package lux-copyright-manager
 */

/**
 * This file handles attribute mapping between the shortcode and block implementations.
 *
 * The Lux Copyright Manager plugin supports both shortcodes and Gutenberg blocks, which use
 * different naming conventions for their attributes:
 * - Shortcodes use snake_case (e.g., 'show_site_title')
 * - Blocks use camelCase (e.g., 'showSiteTitle')
 *
 * This file provides utility functions to convert between these naming conventions,
 * ensuring that both implementations can share the same rendering logic. It contains:
 *
 * 1. lcm_lux_snake_to_camel() - Converts snake_case strings to camelCase
 *    - Handles empty strings gracefully
 *    - Properly capitalizes each word after the first
 *
 * 2. lcm_lux_map_shortcode_atts() - Maps an array of shortcode attributes to block attributes
 *    - Takes an associative array with snake_case keys
 *    - Returns an associative array with camelCase keys
 *    - Preserves all values unchanged, only converting the keys
 *
 * This attribute mapping is essential for maintaining a single rendering engine
 * that works for both the shortcode and block implementations, reducing code
 * duplication and ensuring consistency between the two approaches.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Converts snake_case string to camelCase.
 *
 * @param string $str The snake_case string to convert.
 * @return string The camelCase string.
 */
function lcm_lux_snake_to_camel( $str ) {
	// Handle empty strings
	if ( empty( $str ) ) {
		return $str;
	}

	// Convert snake_case to camelCase
	$parts = explode( '_', $str );
	$camel = $parts[0];
	for ( $i = 1; $i < count( $parts ); $i++ ) {
		$camel .= ucfirst( $parts[ $i ] );
	}
	return $camel;
}

/**
 * Maps shortcode attributes (snake_case) to block attributes (camelCase).
 *
 * @param array $atts The shortcode attributes.
 * @return array The mapped attributes.
 */
function lcm_lux_map_shortcode_atts( $atts ) {
	$mapped = array();
	foreach ( $atts as $key => $value ) {
		$mapped[ lcm_lux_snake_to_camel( $key ) ] = $value;
	}
	return $mapped;
}