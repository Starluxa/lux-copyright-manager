<?php
/**
 * Plugin Name:       Lux Copyright Manager
 * Description:       Display and automatically update your site's copyright date with a block and shortcode.
 * Version:           1.0.0
 * Requires at least: 6.2
 * Tested up to:      6.8
 * Requires PHP:      7.0
 * Author:            StarLux
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       lux-copyright-manager
 *
 * @package           lux-copyright-manager
 */

/**
 * This is the main plugin file that sets up the Lux Copyright Date Block.
 *
 * The plugin provides both a Gutenberg block and a shortcode for displaying
 * automatically updating copyright information in WordPress sites. It includes:
 *
 * 1. Block Editor Integration:
 *    - Registers a custom Gutenberg block for visual copyright insertion
 *    - Provides a settings page for configuring copyright display options
 *    - Supports dynamic copyright year updates
 *
 * 2. Shortcode Functionality:
 *    - Implements the [lux_copyright_manager] shortcode for use in posts/pages
 *    - Offers extensive customization options (date ranges, site title, privacy link, etc.)
 *    - Includes a user-friendly shortcode builder in the admin settings
 *
 * 3. Key Features:
 *    - Automatic year updating (uses GMT time for consistency)
 *    - Customizable date ranges (e.g., "2020-2025")
 *    - Site title and tagline integration
 *    - Privacy policy link support
 *    - SEO Schema.org structured data
 *    - Background image support for blocks
 *    - Responsive design controls
 *
 * 4. Admin Interface:
 *    - Dedicated settings page with tabbed navigation
 *    - Live preview functionality for shortcode customization
 *    - Comprehensive documentation and usage examples
 *    - Troubleshooting guidance
 *
 * The plugin follows WordPress coding standards and best practices, with proper
 * internationalization support and security measures.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Include the core rendering functions for the plugin.
 */
require_once __DIR__ . '/includes/shortcode/handler.php';

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function lcm_lux_copyright_year_block_init() {
	register_block_type( __DIR__ . '/build' );
}

add_action( 'init', 'lcm_lux_copyright_year_block_init' );

/**
	* Add admin menu page for the plugin
	*/
function lcm_lux_copyright_admin_menu() {
	add_options_page(
		__( 'Lux Copyright Manager Settings', 'lux-copyright-manager' ),
		__( 'Lux Copyright Manager', 'lux-copyright-manager' ),
		'manage_options',
 		'lux-copyright',
 		'lcm_lux_copyright_settings_page'
 	);
}
add_action( 'admin_menu', 'lcm_lux_copyright_admin_menu' );

/**
	* Admin settings page
	*/
function lcm_lux_copyright_settings_page() {
	$site_title = get_bloginfo('name');
	$site_tagline = get_bloginfo('description');
	$privacy_policy_url = get_privacy_policy_url();
	$privacy_policy_link = $privacy_policy_url ? '<a href="' . esc_url($privacy_policy_url) . '">Privacy Policy</a>' : 'Privacy Policy';
	
	$current_year = gmdate('Y');
	?>
	<div class="wrap lux-copyright-builder">
		<h1><?php esc_html_e( 'Lux Copyright Manager & Shortcode Builder', 'lux-copyright-manager' ); ?></h1>
		
		<div class="nav-tab-wrapper">
			<a href="#" class="nav-tab nav-tab-active" data-tab="builder"><?php esc_html_e( 'Shortcode Builder', 'lux-copyright-manager' ); ?></a>
			<a href="#" class="nav-tab" data-tab="documentation"><?php esc_html_e( 'Documentation', 'lux-copyright-manager' ); ?></a>
			<a href="#" class="nav-tab" data-tab="block"><?php esc_html_e( 'Block Instructions', 'lux-copyright-manager' ); ?></a>
		</div>
		
		<!-- Shortcode Builder Tab -->
		<div id="builder-tab" class="tab-content active">
			<div class="card">
				<h2><?php esc_html_e( 'Build Your Copyright Shortcode', 'lux-copyright-manager' ); ?></h2>
				<p><?php esc_html_e( 'Use the controls below to customize your copyright notice. See a live preview of how it will look on your site.', 'lux-copyright-manager' ); ?></p>
				
				<div class="builder-container">
					<div class="controls-section">
						<h3><?php esc_html_e( 'Basic Settings', 'lux-copyright-manager' ); ?></h3>
						
						<div class="control-group">
							<label class="control-label">
								<input type="checkbox" id="show_symbol" checked>
								<?php esc_html_e( 'Show Copyright Symbol (©)', 'lux-copyright-manager' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Display the copyright symbol before the date.', 'lux-copyright-manager' ); ?></p>
						</div>
						
						<div class="control-group">
							<label class="control-label">
								<input type="checkbox" id="show_starting_year">
								<?php esc_html_e( 'Show Date Range', 'lux-copyright-manager' ); ?>
							</label>
							<?php /* translators: %s is the current year */ ?>
							<p class="description"><?php echo esc_html( sprintf( _x( 'Display a range of years (e.g., 2020-%s).', 'date range example with current year', 'lux-copyright-manager' ), $current_year ) ); ?></p>
							
							<div class="sub-control" id="starting_year_control" style="display: none; margin-top: 10px;">
								<label for="starting_year"><?php esc_html_e( 'Starting Year:', 'lux-copyright-manager' ); ?></label>
								<input type="number" id="starting_year" min="1900" max="<?php echo esc_attr( $current_year ); ?>" value="<?php echo esc_attr( $current_year - 4 ); ?>" style="width: 100px;">
								
								<label for="custom_separator" style="margin-left: 20px;"><?php esc_html_e( 'Separator:', 'lux-copyright-manager' ); ?></label>
								<input type="text" id="custom_separator" value="–" style="width: 80px; margin-left: 5px;">
								<p class="description"><?php esc_html_e( 'Enter any separator you want (e.g., -, –, —, to)', 'lux-copyright-manager' ); ?></p>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">
								<input type="checkbox" id="enable_schema">
								<?php esc_html_e( 'Enable SEO Schema', 'lux-copyright-manager' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Add structured data for search engines.', 'lux-copyright-manager' ); ?></p>
						</div>
					</div>
					
					<div class="controls-section">
						<h3><?php esc_html_e( 'Text Customization', 'lux-copyright-manager' ); ?></h3>
						
						<div class="control-group">
							<label for="custom_before_text"><?php esc_html_e( 'Text Before Date:', 'lux-copyright-manager' ); ?></label>
							<input type="text" id="custom_before_text" value="Copyright" placeholder="<?php esc_attr_e( 'e.g., Copyright', 'lux-copyright-manager' ); ?>">
							<p class="description"><?php esc_html_e( 'Text to display before the copyright date.', 'lux-copyright-manager' ); ?></p>
						</div>
						
						<div class="control-group">
							<label for="custom_after_text"><?php esc_html_e( 'Text After Date:', 'lux-copyright-manager' ); ?></label>
							<input type="text" id="custom_after_text" placeholder="<?php esc_attr_e( 'e.g., All rights reserved', 'lux-copyright-manager' ); ?>">
							<p class="description"><?php esc_html_e( 'Text to display after the copyright date.', 'lux-copyright-manager' ); ?></p>
						</div>
					</div>
					
					<div class="controls-section">
						<h3><?php esc_html_e( 'Site Information', 'lux-copyright-manager' ); ?></h3>
						
						<div class="control-group">
							<label class="control-label">
								<input type="checkbox" id="show_site_title">
								<?php esc_html_e( 'Show Site Title', 'lux-copyright-manager' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Display your site title after the date.', 'lux-copyright-manager' ); ?></p>
							
							<div class="sub-control" id="link_site_title_control" style="display: none; margin-top: 10px;">
								<label class="control-label">
									<input type="checkbox" id="link_site_title" checked>
									<?php esc_html_e( 'Enable Link', 'lux-copyright-manager' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Make the site title a link.', 'lux-copyright-manager' ); ?></p>
								
								<label for="custom_site_title_url" style="margin-top: 10px; display: block;"><?php esc_html_e( 'Custom URL:', 'lux-copyright-manager' ); ?></label>
								<input type="url" id="custom_site_title_url" placeholder="<?php esc_attr_e( 'e.g., https://example.com', 'lux-copyright-manager' ); ?>" style="width: 100%; margin-top: 5px;">
								<p class="description"><?php esc_html_e( 'Enter a custom URL for the site title link. Leave blank to link to homepage.', 'lux-copyright-manager' ); ?></p>
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label">
								<input type="checkbox" id="show_tagline">
								<?php esc_html_e( 'Show Tagline', 'lux-copyright-manager' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Display your site tagline after the site title.', 'lux-copyright-manager' ); ?></p>
						</div>
						
						<div class="control-group">
							<label class="control-label">
								<input type="checkbox" id="show_privacy_link">
								<?php esc_html_e( 'Show Privacy Policy Link', 'lux-copyright-manager' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'Display a link to your privacy policy.', 'lux-copyright-manager' ); ?></p>
							
							<div class="sub-control" id="privacy_link_control" style="display: none; margin-top: 10px;">
								<label for="custom_privacy_text"><?php esc_html_e( 'Custom Link Text:', 'lux-copyright-manager' ); ?></label>
								<input type="text" id="custom_privacy_text" placeholder="<?php esc_attr_e( 'e.g., Privacy Policy', 'lux-copyright-manager' ); ?>">
								
								<label for="custom_privacy_url" style="margin-left: 20px;"><?php esc_html_e( 'Custom URL:', 'lux-copyright-manager' ); ?></label>
								<input type="url" id="custom_privacy_url" placeholder="<?php esc_attr_e( 'e.g., https://example.com/privacy', 'lux-copyright-manager' ); ?>">
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<div class="card">
				<h2><?php esc_html_e( 'Live Preview', 'lux-copyright-manager' ); ?></h2>
				<p><?php esc_html_e( 'See how your copyright notice will appear on your site:', 'lux-copyright-manager' ); ?></p>
				
				<div class="preview-container">
					<div id="shortcode-preview" class="preview-box">
						<p id="preview-content"><?php esc_html_e( 'Preview will appear here...', 'lux-copyright-manager' ); ?></p>
					</div>
					
					<div class="shortcode-output">
						<h3><?php esc_html_e( 'Your Shortcode', 'lux-copyright-manager' ); ?></h3>
						<div class="shortcode-container">
							<code id="generated-shortcode">[lux_copyright_manager]</code>
							<button class="button copy-button" id="copy-shortcode">
								<?php esc_html_e( 'Copy Shortcode', 'lux-copyright-manager' ); ?>
							</button>
						</div>
						<p class="description"><?php esc_html_e( 'Copy this shortcode and paste it into any post, page, or widget.', 'lux-copyright-manager' ); ?></p>
					</div>
				</div>
			</div>
			
			<div class="card">
				<h2><?php esc_html_e( 'Usage Examples', 'lux-copyright-manager' ); ?></h2>
				
				<div class="example-grid">
					<div class="example-item">
						<h4><?php esc_html_e( 'Basic Copyright', 'lux-copyright-manager' ); ?></h4>
						<p><code>[lux_copyright_manager]</code></p>
						<div class="example-preview">
							<p>Copyright © <?php echo esc_html( $current_year ); ?></p>
						</div>
						<p class="description"><?php esc_html_e( 'Simple copyright with current year.', 'lux-copyright-manager' ); ?></p>
					</div>
					
					<div class="example-item">
						<h4><?php esc_html_e( 'With Site Title', 'lux-copyright-manager' ); ?></h4>
						<p><code>[lux_copyright_manager show_site_title="true"]</code></p>
						<div class="example-preview">
							<p>Copyright © <?php echo esc_html( $current_year ); ?> <a href="<?php echo esc_url( home_url('/') ); ?>"><?php echo esc_html($site_title); ?></a></p>
						</div>
						<p class="description"><?php esc_html_e( 'Copyright with your site name (automatically linked to homepage).', 'lux-copyright-manager' ); ?></p>
					</div>
					
					<div class="example-item">
						<h4><?php esc_html_e( 'Date Range', 'lux-copyright-manager' ); ?></h4>
						<p><code>[lux_copyright_manager show_starting_year="true" starting_year="2020"]</code></p>
						<div class="example-preview">
							<p>Copyright © 2020–<?php echo esc_html( $current_year ); ?></p>
						</div>
						<p class="description"><?php esc_html_e( 'Copyright with year range.', 'lux-copyright-manager' ); ?></p>
					</div>
					
					<div class="example-item">
						<h4><?php esc_html_e( 'Full Featured', 'lux-copyright-manager' ); ?></h4>
						<p><code>[lux_copyright_manager show_symbol="true" show_starting_year="true" starting_year="2020" custom_after_text="All Rights Reserved" show_site_title="true" show_tagline="true" show_privacy_link="true"]</code></p>
						<div class="example-preview">
							<p>Copyright © 2020–<?php echo esc_html( $current_year ); ?> All Rights Reserved <a href="<?php echo esc_url( home_url('/') ); ?>"><?php echo esc_html($site_title); ?></a> - <?php echo esc_html($site_tagline); ?> | <a href="<?php echo esc_url($privacy_policy_url); ?>" target="_blank">Privacy Policy</a></p>
						</div>
						<p class="description"><?php esc_html_e( 'Fully featured copyright notice with automatic linking.', 'lux-copyright-manager' ); ?></p>
					</div>
					
					<div class="example-item">
						<h4><?php esc_html_e( 'With Custom Site Title URL', 'lux-copyright-manager' ); ?></h4>
						<p><code>[lux_copyright_manager show_site_title="true" custom_site_title_url="https://example.com"]</code></p>
						<div class="example-preview">
							<p>Copyright © <?php echo esc_html( $current_year ); ?> <a href="https://example.com"><?php echo esc_html($site_title); ?></a></p>
						</div>
						<p class="description"><?php esc_html_e( 'Copyright with site title linked to a custom URL.', 'lux-copyright-manager' ); ?></p>
					</div>
				</div>
			</div>
		</div>
		
		<!-- Documentation Tab -->
		<div id="documentation-tab" class="tab-content">
			<div class="card">
				<h2><?php esc_html_e( 'Shortcode Attributes', 'lux-copyright-manager' ); ?></h2>
				<p><?php esc_html_e( 'Detailed information about all available shortcode attributes:', 'lux-copyright-manager' ); ?></p>
				
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Attribute', 'lux-copyright-manager' ); ?></th>
							<th><?php esc_html_e( 'Description', 'lux-copyright-manager' ); ?></th>
							<th><?php esc_html_e( 'Default Value', 'lux-copyright-manager' ); ?></th>
							<th><?php esc_html_e( 'Example', 'lux-copyright-manager' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><code>show_symbol</code></td>
							<td><?php esc_html_e( 'Show the copyright symbol (©)', 'lux-copyright-manager' ); ?></td>
							<td><code>true</code></td>
							<td><code>show_symbol="false"</code></td>
						</tr>
						<tr>
							<td><code>show_starting_year</code></td>
							<?php /* translators: %s is the current year */ ?>
							<td><?php echo esc_html( sprintf( _x( 'Show a starting year to create a range (e.g., 2020-%s)', 'date range example with current year', 'lux-copyright-manager' ), $current_year ) ); ?></td>
							<td><code>false</code></td>
							<td><code>show_starting_year="true"</code></td>
						</tr>
						<tr>
							<td><code>starting_year</code></td>
							<td><?php esc_html_e( 'The starting year for the range (only used if show_starting_year is true)', 'lux-copyright-manager' ); ?></td>
							<td><?php esc_html_e( 'Current year', 'lux-copyright-manager' ); ?></td>
							<td><code>starting_year="2020"</code></td>
						</tr>
						<tr>
							<td><code>show_site_title</code></td>
							<td><?php esc_html_e( 'Show the site title', 'lux-copyright-manager' ); ?></td>
							<td><code>false</code></td>
							<td><code>show_site_title="true"</code></td>
						</tr>
						<tr>
							<td><code>show_tagline</code></td>
							<td><?php esc_html_e( 'Show the site tagline', 'lux-copyright-manager' ); ?></td>
							<td><code>false</code></td>
							<td><code>show_tagline="true"</code></td>
						</tr>
						<tr>
							<td><code>show_privacy_link</code></td>
							<td><?php esc_html_e( 'Show a link to the privacy policy', 'lux-copyright-manager' ); ?></td>
							<td><code>false</code></td>
							<td><code>show_privacy_link="true"</code></td>
						</tr>
						<tr>
							<td><code>custom_separator</code></td>
							<td><?php esc_html_e( 'Custom separator for date ranges (enter any separator you want)', 'lux-copyright-manager' ); ?></td>
							<td><code>–</code></td>
							<td><code>custom_separator="to"</code></td>
						</tr>
						<tr>
							<td><code>custom_before_text</code></td>
							<td><?php esc_html_e( 'Text to show before the date', 'lux-copyright-manager' ); ?></td>
							<td><code>Copyright</code></td>
							<td><code>custom_before_text="©"</code></td>
						</tr>
						<tr>
							<td><code>custom_after_text</code></td>
							<td><?php esc_html_e( 'Text to show after the date', 'lux-copyright-manager' ); ?></td>
							<td><code></code></td>
							<td><code>custom_after_text="All rights reserved"</code></td>
						</tr>
						<tr>
							<td><code>custom_site_title_url</code></td>
							<td><?php esc_html_e( 'Custom URL for the site title link', 'lux-copyright-manager' ); ?></td>
							<td><code></code></td>
							<td><code>custom_site_title_url="https://example.com"</code></td>
						</tr>
						<tr>
							<td><code>custom_privacy_url</code></td>
							<td><?php esc_html_e( 'Custom URL for the privacy policy link', 'lux-copyright-manager' ); ?></td>
							<td><code></code></td>
							<td><code>custom_privacy_url="https://example.com/privacy"</code></td>
						</tr>
						<tr>
							<td><code>enable_schema</code></td>
							<td><?php esc_html_e( 'Enable Schema.org structured data', 'lux-copyright-manager' ); ?></td>
							<td><code>false</code></td>
							<td><code>enable_schema="true"</code></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="card">
				<h2><?php esc_html_e( 'Troubleshooting', 'lux-copyright-manager' ); ?></h2>
				
				<div class="troubleshooting-section">
					<h3><?php esc_html_e( 'Privacy Policy Link Not Showing?', 'lux-copyright-manager' ); ?></h3>
					<p><?php esc_html_e( 'To display a privacy policy link, you need to set a privacy policy page in your WordPress settings:', 'lux-copyright-manager' ); ?></p>
					<ol>
						<li><?php esc_html_e( 'Go to Settings > Privacy', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Select or create a privacy policy page', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Save changes', 'lux-copyright-manager' ); ?></li>
					</ol>
					<p><?php esc_html_e( 'If you want to use a custom privacy policy link instead:', 'lux-copyright-manager' ); ?></p>
					<ul>
						<li><?php esc_html_e( 'Use the custom_privacy_url attribute', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Example: [lux_copyright_manager show_privacy_link="true" custom_privacy_url="https://example.com/privacy"]', 'lux-copyright-manager' ); ?></li>
					</ul>
				</div>
				
				<div class="troubleshooting-section">
					<h3><?php esc_html_e( 'Site Title or Tagline Not Showing?', 'lux-copyright-manager' ); ?></h3>
					<p><?php esc_html_e( 'To display your site title or tagline, check your WordPress settings:', 'lux-copyright-manager' ); ?></p>
					<ol>
						<li><?php esc_html_e( 'Go to Settings > General', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Check the "Site Title" and "Tagline" fields', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Save changes if you make any updates', 'lux-copyright-manager' ); ?></li>
					</ol>
				</div>
				
				<div class="troubleshooting-section">
					<h3><?php esc_html_e( 'Custom Site Title URL Not Working?', 'lux-copyright-manager' ); ?></h3>
					<p><?php esc_html_e( 'To use a custom URL for your site title link:', 'lux-copyright-manager' ); ?></p>
					<ol>
						<li><?php esc_html_e( 'Enable the "Show Site Title" option', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Enable the "Enable Link" option', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Enter your custom URL in the "Custom URL" field', 'lux-copyright-manager' ); ?></li>
					</ol>
					<p><?php esc_html_e( 'If you leave the custom URL field blank, the site title will link to your homepage.', 'lux-copyright-manager' ); ?></p>
				</div>
				
				<div class="troubleshooting-section">
					<h3><?php esc_html_e( 'Date Not Updating?', 'lux-copyright-manager' ); ?></h3>
					<p><?php esc_html_e( 'The copyright date updates automatically on January 1st. If it\'s not updating:', 'lux-copyright-manager' ); ?></p>
					<ul>
						<li><?php esc_html_e( 'Check that your server time is correct', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Clear any caching plugins or services', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'The date will update automatically at the start of the new year', 'lux-copyright-manager' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
		
		<!-- Block Instructions Tab -->
	<div id="block-tab" class="tab-content">
			<div class="card">
				<h2><?php esc_html_e( 'How to Use the Block', 'lux-copyright-manager' ); ?></h2>
				<p><?php esc_html_e( 'The block version offers the same features with a visual editor interface:', 'lux-copyright-manager' ); ?></p>
				
				<ol>
					<li><?php esc_html_e( 'Create or edit a post or page', 'lux-copyright-manager' ); ?></li>
					<li><?php esc_html_e( 'Click the "+" button to add a new block', 'lux-copyright-manager' ); ?></li>
					<li><?php esc_html_e( 'Search for "Lux Copyright Manager" or look under "Widgets"', 'lux-copyright-manager' ); ?></li>
					<li><?php esc_html_e( 'Click to add the block to your page', 'lux-copyright-manager' ); ?></li>
					<li><?php esc_html_e( 'Use the block settings in the right sidebar to customize your copyright notice', 'lux-copyright-manager' ); ?></li>
				</ol>
				
				<div class="block-features">
					<h3><?php esc_html_e( 'Block Features', 'lux-copyright-manager' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'Visual editor with real-time preview', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Advanced styling options', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Background image support', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'Responsive design controls', 'lux-copyright-manager' ); ?></li>
						<li><?php esc_html_e( 'SEO Schema integration', 'lux-copyright-manager' ); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	
	<style>
			/* Override WordPress .wrap class constraints to use full width */
			.wrap.lux-copyright-builder {
				max-width: none !important;
				width: 100% !important;
				padding: 0 !important;
				margin: 0 !important;
				box-sizing: border-box;
			}
			
			/* Ensure full width on all screen sizes */
			.lux-copyright-builder {
				max-width: 100% !important;
				width: 100% !important;
				padding: 0 20px !important;
				box-sizing: border-box !important;
				margin: 0 !important;
			}
			
			/* Override any inherited constraints from parent elements */
			.lux-copyright-builder * {
				box-sizing: border-box !important;
			}
			
			/* Ensure cards use full width */
			.lux-copyright-builder .card {
				max-width: 100% !important;
				width: 100% !important;
				margin: 0 0 20px 0 !important;
				box-sizing: border-box !important;
			}
			
			.lux-copyright-builder .nav-tab-wrapper {
				margin-bottom: 20px;
			}
			
			.lux-copyright-builder .tab-content {
				display: none;
			}
			
			.lux-copyright-builder .tab-content.active {
				display: block;
			}
			
			.lux-copyright-builder .card {
				margin-bottom: 20px;
				padding: 20px;
				background: white;
				border: 1px solid #ccd0d4;
				box-shadow: 0 1px 1px rgba(0,0,0,.04);
				box-sizing: border-box;
				width: 100%;
			}
			
			.lux-copyright-builder .card h2 {
				margin-top: 0;
			}
			
			.lux-copyright-builder .builder-container {
				display: grid;
				grid-template-columns: 1fr;
				gap: 20px;
				width: 100%;
			}
			
			@media (min-width: 768px) {
				.lux-copyright-builder .builder-container {
					grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
				}
			}
			
			@media (min-width: 1200px) {
				.lux-copyright-builder .builder-container {
					grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
				}
			}
			
			.lux-copyright-builder .controls-section {
				background: #f0f0f1;
				padding: 20px;
				border-radius: 4px;
				box-sizing: border-box;
				width: 100%;
			}
			
			.lux-copyright-builder .controls-section h3 {
				margin-top: 0;
			}
			
			.lux-copyright-builder .control-group {
				margin-bottom: 15px;
			}
			
			.lux-copyright-builder .control-label {
				font-weight: 600;
				display: block;
				margin-bottom: 5px;
			}
			
			.lux-copyright-builder .control-label input[type="checkbox"] {
				margin-right: 8px;
			}
			
			.lux-copyright-builder .sub-control {
				margin-left: 20px;
				padding: 15px;
				background: white;
				border-radius: 4px;
				margin-top: 10px;
			}
			
			.lux-copyright-builder .description {
				color: #666;
				font-size: 13px;
				margin: 5px 0;
				line-height: 1.4;
			}
			
			.lux-copyright-builder .preview-container {
				display: grid;
				grid-template-columns: 1fr;
				gap: 20px;
				width: 100%;
			}
			
			@media (min-width: 768px) {
				.lux-copyright-builder .preview-container {
					grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
				}
			}
			
			@media (min-width: 1200px) {
				.lux-copyright-builder .preview-container {
					grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
				}
			}
			
			.lux-copyright-builder .preview-box {
				border: 1px dashed #ccc;
				padding: 25px;
				min-height: 100px;
				display: flex;
				align-items: center;
				justify-content: center;
				background: #f9f9f9;
				box-sizing: border-box;
				width: 100%;
			}
			
			.lux-copyright-builder .preview-box p {
				margin: 0;
				font-size: 16px;
				text-align: center;
				line-height: 1.5;
			}
			
			.lux-copyright-builder .shortcode-output {
				display: flex;
				flex-direction: column;
				width: 100%;
			}
			
			.lux-copyright-builder .shortcode-container {
				display: flex;
				align-items: center;
				margin-bottom: 15px;
				background: #f0f0f1;
				padding: 15px;
				border-radius: 4px;
				box-sizing: border-box;
				width: 100%;
				flex-wrap: wrap;
			}
			
			.lux-copyright-builder .shortcode-container code {
				flex-grow: 1;
				padding: 10px;
				background: white;
				border: 1px solid #ddd;
				border-radius: 4px;
				font-size: 14px;
				overflow-x: auto;
				margin-right: 10px;
				min-width: 200px;
			}
			
			.lux-copyright-builder .shortcode-container .copy-button {
				height: 40px;
				white-space: nowrap;
				flex-shrink: 0;
			}
			
			@media (max-width: 767px) {
				.lux-copyright-builder .shortcode-container {
					flex-direction: column;
				}
				
				.lux-copyright-builder .shortcode-container code {
					margin-right: 0;
					margin-bottom: 10px;
					width: 100%;
				}
				
				.lux-copyright-builder .shortcode-container .copy-button {
					align-self: flex-end;
					width: 100%;
				}
			}
			
			.lux-copyright-builder .example-grid {
				display: grid;
				grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
				gap: 20px;
				width: 100%;
				word-wrap: break-word;
				overflow-wrap: break-word;
			}
			
			.lux-copyright-builder .example-item {
				border: 1px solid #ddd;
				border-radius: 4px;
				padding: 20px;
				background: #f9f9f9;
				box-sizing: border-box;
				width: 100%;
				overflow: hidden;
			}
			
			.lux-copyright-builder .example-item h4 {
				margin-top: 0;
			}
			
			.lux-copyright-builder .example-item code {
				background: #e0e0e0;
				padding: 4px 8px;
				border-radius: 3px;
				font-size: 13px;
				display: inline-block;
				margin: 5px 0;
				word-wrap: break-word;
				overflow-wrap: break-word;
				word-break: break-all;
			}
			
			.lux-copyright-builder .example-preview {
				padding: 15px;
				background: white;
				border: 1px solid #ddd;
				border-radius: 4px;
				margin: 15px 0;
				min-height: 50px;
				display: flex;
				align-items: center;
				box-sizing: border-box;
				width: 100%;
				word-wrap: break-word;
				overflow-wrap: break-word;
			}
			
			.lux-copyright-builder .example-preview p {
				margin: 0;
				line-height: 1.5;
				word-wrap: break-word;
				overflow-wrap: break-word;
				word-break: break-word;
			}
			
			.lux-copyright-builder table.widefat td,
			.lux-copyright-builder table.widefat th {
				padding: 10px 12px;
			}
			
			.lux-copyright-builder .troubleshooting-section {
				margin-bottom: 25px;
				padding-bottom: 25px;
				border-bottom: 1px solid #eee;
			}
			
			.lux-copyright-builder .troubleshooting-section:last-child {
				border-bottom: none;
				margin-bottom: 0;
				padding-bottom: 0;
			}
			
			.lux-copyright-builder .block-features ul {
				columns: 1;
				column-gap: 20px;
				margin: 15px 0;
				padding-left: 20px;
			}
			
			@media (min-width: 768px) {
				.lux-copyright-builder .block-features ul {
					columns: 2;
					column-gap: 40px;
				}
			}
			
			/* Copy button feedback styles */
			.lux-copyright-builder .copy-success {
				background: #46b450 !important;
				border-color: #369b3f !important;
				color: white !important;
			}
			
			.lux-copyright-builder .copy-error {
				background: #dc3232 !important;
				border-color: #b32d2e !important;
				color: white !important;
			}
			
			/* Improved responsive behavior for smaller screens */
			@media (max-width: 480px) {
				.lux-copyright-builder .card {
					padding: 15px;
				}
				
				.lux-copyright-builder .controls-section {
					padding: 15px;
				}
				
				.lux-copyright-builder .example-grid {
					grid-template-columns: 1fr;
				}
				
				.lux-copyright-builder .sub-control {
					margin-left: 10px;
					padding: 10px;
				}
			}
			
			/* Ensure proper spacing on larger screens */
			@media (min-width: 1400px) {
				.lux-copyright-builder {
					max-width: 100% !important;
					margin: 0 !important;
				}
			}
			
			/* Override WordPress admin mobile constraints */
			@media screen and (max-width: 782px) {
				.wrap.lux-copyright-builder {
					padding: 0 10px !important;
					margin: 0 !important;
				}
				
				.lux-copyright-builder {
					padding: 0 10px !important;
				}
			}
			
			/* Override WordPress admin mobile constraints */
			@media screen and (max-width: 600px) {
				.wrap.lux-copyright-builder {
					margin: 0 !important;
					padding: 0 5px !important;
				}
				
				.lux-copyright-builder {
					padding: 0 5px !important;
				}
			}
		</style>
	
	<script>
			document.addEventListener('DOMContentLoaded', function() {
				// Error handling wrapper
				try {
					// Tab switching with keyboard accessibility
					const tabs = document.querySelectorAll('.nav-tab');
					tabs.forEach(tab => {
						// Add keyboard support
						tab.addEventListener('keydown', function(e) {
							if (e.key === 'Enter' || e.key === ' ') {
								e.preventDefault();
								tab.click();
							}
						});
						
						tab.addEventListener('click', function(e) {
							e.preventDefault();
							
							// Remove active class from all tabs and content
							document.querySelectorAll('.nav-tab').forEach(t => t.classList.remove('nav-tab-active'));
							document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
							
							// Add active class to clicked tab
							this.classList.add('nav-tab-active');
							this.setAttribute('aria-selected', 'true');
							
							// Show corresponding content
							const tabId = this.getAttribute('data-tab');
							const tabContent = document.getElementById(tabId + '-tab');
							if (tabContent) {
								tabContent.classList.add('active');
								tabContent.setAttribute('aria-hidden', 'false');
								
								// Hide other tab contents
								document.querySelectorAll('.tab-content').forEach(c => {
									if (c.id !== tabId + '-tab') {
										c.setAttribute('aria-hidden', 'true');
									}
								});
							}
							
							// Update other tabs' aria-selected
							tabs.forEach(t => {
								if (t !== this) {
									t.setAttribute('aria-selected', 'false');
								}
							});
						});
					});
					
					// Get form elements
					const showSymbol = document.getElementById('show_symbol');
					const showStartingYear = document.getElementById('show_starting_year');
					const startingYear = document.getElementById('starting_year');
					const startingYearControl = document.getElementById('starting_year_control');
					const customSeparator = document.getElementById('custom_separator');
					const showSiteTitle = document.getElementById('show_site_title');
					const linkSiteTitle = document.getElementById('link_site_title');
					const linkSiteTitleControl = document.getElementById('link_site_title_control');
					const customSiteTitleUrl = document.getElementById('custom_site_title_url');
					const showTagline = document.getElementById('show_tagline');
					const showPrivacyLink = document.getElementById('show_privacy_link');
					const privacyLinkControl = document.getElementById('privacy_link_control');
					const customPrivacyText = document.getElementById('custom_privacy_text');
					const customPrivacyUrl = document.getElementById('custom_privacy_url');
					const customBeforeText = document.getElementById('custom_before_text');
					const customAfterText = document.getElementById('custom_after_text');
					const enableSchema = document.getElementById('enable_schema');
					
					// Preview and output elements
					const previewContent = document.getElementById('preview-content');
					const generatedShortcode = document.getElementById('generated-shortcode');
					const copyButton = document.getElementById('copy-shortcode');
					
					// Site information for preview
					const siteTitle = <?php echo json_encode($site_title); ?>;
					const siteTagline = <?php echo json_encode($site_tagline); ?>;
					const privacyPolicyUrl = <?php echo json_encode($privacy_policy_url); ?>;
					const currentYear = <?php echo json_encode($current_year); ?>;
					
					// Check if all required elements exist
					const requiredElements = [
						showSymbol, showStartingYear, startingYearControl, customSeparator,
						showSiteTitle, linkSiteTitleControl, showTagline, showPrivacyLink,
						privacyLinkControl, customPrivacyText, customPrivacyUrl, customBeforeText,
						customAfterText, enableSchema, previewContent, generatedShortcode, copyButton
					];
					
					// Event listeners for form elements with error handling
					if (showStartingYear && startingYearControl) {
						showStartingYear.addEventListener('change', function() {
							try {
								startingYearControl.style.display = this.checked ? 'block' : 'none';
								updatePreview();
							} catch (e) {
								console.error('Error in showStartingYear change handler:', e);
							}
						});
					}
					
					if (showSiteTitle && linkSiteTitleControl) {
						showSiteTitle.addEventListener('change', function() {
							try {
								linkSiteTitleControl.style.display = this.checked ? 'block' : 'none';
								updatePreview();
							} catch (e) {
								console.error('Error in showSiteTitle change handler:', e);
							}
						});
					}
					
					if (showPrivacyLink && privacyLinkControl) {
						showPrivacyLink.addEventListener('change', function() {
							try {
								privacyLinkControl.style.display = this.checked ? 'block' : 'none';
								updatePreview();
							} catch (e) {
								console.error('Error in showPrivacyLink change handler:', e);
							}
						});
					}
					
					// Add event listeners to all form elements with error handling
					const formElements = [
						showSymbol, showStartingYear, startingYear, customSeparator,
						showSiteTitle, linkSiteTitle, customSiteTitleUrl, showTagline, showPrivacyLink,
						customPrivacyText, customPrivacyUrl, customBeforeText, customAfterText, enableSchema
					];
					
					formElements.forEach(element => {
						if (element) {
							try {
								if (element.type === 'checkbox') {
									element.addEventListener('change', function() {
										try {
											updatePreview();
										} catch (e) {
											console.error('Error in updatePreview from checkbox change:', e);
										}
									});
								} else {
									element.addEventListener('input', function() {
										try {
											updatePreview();
										} catch (e) {
											console.error('Error in updatePreview from input change:', e);
										}
									});
								}
							} catch (e) {
								console.error('Error adding event listener to element:', element, e);
							}
						}
					});
					
					// Copy button functionality with comprehensive error handling and accessibility
					if (copyButton) {
						copyButton.addEventListener('click', function() {
							try {
								if (!generatedShortcode) {
									console.error('Generated shortcode element not found');
									return;
								}
								
								const text = generatedShortcode.textContent;
								if (!text) {
									console.error('No text to copy');
									return;
								}
								
								// Try to copy to clipboard with modern API first
								if (navigator.clipboard && navigator.clipboard.writeText) {
									navigator.clipboard.writeText(text).then(function() {
										// Success feedback
										const originalText = copyButton.textContent;
										copyButton.textContent = '<?php esc_html_e( 'Copied!', 'lux-copyright-manager' ); ?>';
										copyButton.setAttribute('aria-label', '<?php esc_attr_e( 'Shortcode copied to clipboard', 'lux-copyright-manager' ); ?>');
										copyButton.classList.add('copy-success');
										
										setTimeout(function() {
											copyButton.textContent = originalText;
											copyButton.removeAttribute('aria-label');
											copyButton.classList.remove('copy-success');
										}, 200);
									}).catch(function(err) {
										// Fallback for older browsers or insecure contexts
										try {
											const textArea = document.createElement('textarea');
											textArea.value = text;
											textArea.setAttribute('aria-hidden', 'true');
											textArea.style.position = 'fixed';
											textArea.style.left = '-999px';
											document.body.appendChild(textArea);
											textArea.select();
											document.execCommand('copy');
											document.body.removeChild(textArea);
											
											// Success feedback
											const originalText = copyButton.textContent;
											copyButton.textContent = '<?php esc_html_e( 'Copied!', 'lux-copyright-manager' ); ?>';
											copyButton.setAttribute('aria-label', '<?php esc_attr_e( 'Shortcode copied to clipboard', 'lux-copyright-manager' ); ?>');
											copyButton.classList.add('copy-success');
											
											setTimeout(function() {
												copyButton.textContent = originalText;
												copyButton.removeAttribute('aria-label');
												copyButton.classList.remove('copy-success');
											}, 2000);
										} catch (fallbackErr) {
											console.error('Failed to copy text using fallback method: ', fallbackErr);
											copyButton.setAttribute('aria-label', '<?php esc_attr_e( 'Failed to copy shortcode', 'lux-copyright-manager' ); ?>');
											copyButton.classList.add('copy-error');
											setTimeout(function() {
												copyButton.removeAttribute('aria-label');
												copyButton.classList.remove('copy-error');
											}, 2000);
										}
									});
								} else {
									// Fallback for older browsers
									try {
										const textArea = document.createElement('textarea');
										textArea.value = text;
										textArea.setAttribute('aria-hidden', 'true');
										textArea.style.position = 'fixed';
										textArea.style.left = '-9999px';
										document.body.appendChild(textArea);
										textArea.select();
										document.execCommand('copy');
										document.body.removeChild(textArea);
										
										// Success feedback
										const originalText = copyButton.textContent;
										copyButton.textContent = '<?php esc_html_e( 'Copied!', 'lux-copyright-manager' ); ?>';
										copyButton.setAttribute('aria-label', '<?php esc_attr_e( 'Shortcode copied to clipboard', 'lux-copyright-manager' ); ?>');
										copyButton.classList.add('copy-success');
										
										setTimeout(function() {
											copyButton.textContent = originalText;
											copyButton.removeAttribute('aria-label');
											copyButton.classList.remove('copy-success');
										}, 2000);
									} catch (err) {
										console.error('Failed to copy text using fallback method: ', err);
										copyButton.setAttribute('aria-label', '<?php esc_attr_e( 'Failed to copy shortcode', 'lux-copyright-manager' ); ?>');
										copyButton.classList.add('copy-error');
										setTimeout(function() {
											copyButton.removeAttribute('aria-label');
											copyButton.classList.remove('copy-error');
										}, 2000);
									}
								}
							} catch (err) {
								console.error('Error in copy button click handler: ', err);
								if (copyButton) {
									copyButton.setAttribute('aria-label', '<?php esc_attr_e( 'Failed to copy shortcode', 'lux-copyright-manager' ); ?>');
									copyButton.classList.add('copy-error');
									setTimeout(function() {
										copyButton.removeAttribute('aria-label');
										copyButton.classList.remove('copy-error');
									}, 2000);
								}
							}
						});
					}
					
					// Update preview function with comprehensive error handling
					function updatePreview() {
						try {
							// Generate preview HTML
							let previewHtml = '';
							
							// Before text
							if (customBeforeText && customBeforeText.value) {
								previewHtml += customBeforeText.value + ' ';
							}
							
							// Copyright symbol
							if (showSymbol && showSymbol.checked) {
								previewHtml += '© ';
							}
							
							// Date
							let displayDate = currentYear;
							if (showStartingYear && showStartingYear.checked && startingYear && startingYear.value) {
								const separator = customSeparator ? customSeparator.value : '–';
								displayDate = startingYear.value + separator + currentYear;
							}
							previewHtml += displayDate;
							
							// After text
							if (customAfterText && customAfterText.value) {
								previewHtml += ' ' + customAfterText.value;
							}
							
							// Site title - automatically link when showSiteTitle is enabled (matching shortcode behavior)
							if (showSiteTitle && showSiteTitle.checked && siteTitle) {
								const shouldLink = linkSiteTitle ? linkSiteTitle.checked : true; // Auto-link by default
								if (shouldLink) {
									const siteTitleUrl = (customSiteTitleUrl && customSiteTitleUrl.value) || '<?php echo esc_url( home_url('/') ); ?>';
									previewHtml += ' <a href="' + siteTitleUrl + '" target="_blank">' + siteTitle + '</a>';
								} else {
									previewHtml += ' ' + siteTitle;
								}
							}
							
							// Tagline
							if (showTagline && showTagline.checked && siteTagline) {
								previewHtml += ' - ' + siteTagline;
							}
							
							// Privacy link - automatically link when showPrivacyLink is enabled (matching shortcode behavior)
							if (showPrivacyLink && showPrivacyLink.checked) {
								const linkText = (customPrivacyText && customPrivacyText.value) || 'Privacy Policy';
								const linkUrl = (customPrivacyUrl && customPrivacyUrl.value) || privacyPolicyUrl || '#';
								previewHtml += ' | <a href="' + linkUrl + '" target="_blank">' + linkText + '</a>';
							}
							
							// Update preview content
							if (previewContent) {
								previewContent.innerHTML = previewHtml || '<?php esc_html_e( 'Preview will appear here...', 'lux-copyright-manager' ); ?>';
							}
							
							// Generate shortcode
							let shortcode = '[lux_copyright_manager';
							const attributes = [];
							
							// Add attributes only if they differ from defaults (matching PHP handler logic)
							if (showSymbol && !showSymbol.checked) attributes.push('show_symbol="false"');
							if (showStartingYear && showStartingYear.checked) attributes.push('show_starting_year="true"');
							if (showStartingYear && showStartingYear.checked && startingYear && startingYear.value) attributes.push('starting_year="' + startingYear.value + '"');
							if (customSeparator && customSeparator.value !== '–') attributes.push('custom_separator="' + customSeparator.value + '"');
							if (showSiteTitle && showSiteTitle.checked) attributes.push('show_site_title="true"');
							// Only add link_site_title="false" if user explicitly disabled linking
							if (showSiteTitle && showSiteTitle.checked && linkSiteTitle && !linkSiteTitle.checked) attributes.push('link_site_title="false"');
							if (customSiteTitleUrl && customSiteTitleUrl.value) attributes.push('custom_site_title_url="' + customSiteTitleUrl.value + '"');
							if (showTagline && showTagline.checked) attributes.push('show_tagline="true"');
							if (showPrivacyLink && showPrivacyLink.checked) attributes.push('show_privacy_link="true"');
							if (customPrivacyText && customPrivacyText.value) attributes.push('custom_privacy_text="' + customPrivacyText.value + '"');
							if (customPrivacyUrl && customPrivacyUrl.value) attributes.push('custom_privacy_url="' + customPrivacyUrl.value + '"');
							// Handle custom_before_text attribute
							// If the field is empty (user wants to remove "Copyright"), we still need to generate the attribute
							// to override the default value
							if (customBeforeText) {
								if (customBeforeText.value === '') {
									// User cleared the field to remove "Copyright"
									attributes.push('custom_before_text=""');
								} else if (customBeforeText.value !== 'Copyright') {
									// User entered custom text
									attributes.push('custom_before_text="' + customBeforeText.value + '"');
								}
							}
							if (customAfterText && customAfterText.value) attributes.push('custom_after_text="' + customAfterText.value + '"');
							if (enableSchema && enableSchema.checked) attributes.push('enable_schema="true"');
							
							if (attributes.length > 0) {
								shortcode += ' ' + attributes.join(' ');
							}
							shortcode += ']';
							
							// Update generated shortcode
							if (generatedShortcode) {
								generatedShortcode.textContent = shortcode;
							}
						} catch (previewErr) {
							console.error('Error updating preview: ', previewErr);
							if (previewContent) {
								previewContent.textContent = '<?php esc_html_e( 'Error generating preview', 'lux-copyright-manager' ); ?>';
							}
						}
					}
					
					// Initialize preview
					updatePreview();
				} catch (initErr) {
					console.error('Error initializing shortcode builder: ', initErr);
					const previewContent = document.getElementById('preview-content');
					if (previewContent) {
						previewContent.textContent = '<?php esc_html_e( 'Error loading shortcode builder', 'lux-copyright-manager' ); ?>';
					}
				}
			});
		</script>
	<?php
}
