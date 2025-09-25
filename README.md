# Auto Footer Copyright & Year Updater - Block and Shortcode by Lux

This plugin provides a "Lux Copyright & Year" block that automatically updates your site's copyright date. It also includes a shortcode for use in theme templates.

## Features

- **Automatic Year Updating**: The copyright year updates automatically
- **Block Editor Support**: Easily add copyright info using the block editor
- **Shortcode Support**: Use [lux_copyright] in your theme templates
- **Customizable**: Set starting year and toggle year range display
- **Interactive Shortcode Builder**: Visually create custom copyright notices with live preview
- **Accessibility Ready**: WCAG compliant with keyboard navigation support

## Installation

1. Download the plugin zip file
2. Install via WordPress Admin > Plugins > Add New > Upload Plugin
3. Activate the plugin

## Usage

### Block Editor
1. Create or edit a post/page
2. Add the "Lux Copyright & Year" block
3. Configure settings in the block sidebar

### Shortcode Builder (New!)
1. Go to WordPress Admin > Settings > Lux Copyright Manager
2. Use the interactive controls to customize your copyright notice
3. See a live preview of how it will appear on your site
4. Copy the generated shortcode with the click of a button

### Shortcode
Use `[lux_copyright]` in your theme templates where you want the copyright to appear.

## Customization
The shortcode supports attributes:
- `show_symbol`: Show the copyright symbol (©) - default: true
- `show_starting_year`: Show a starting year to create a range - default: false
- `starting_year`: The starting year for the range - default: current year
- `show_site_title`: Show the site title - default: false
- `show_tagline`: Show the site tagline - default: false
- `show_privacy_link`: Show a link to the privacy policy - default: false
- `custom_separator`: Custom separator for date ranges - default: –
- `custom_before_text`: Text to show before the date - default: Copyright
- `custom_after_text`: Text to show after the date
- `enable_schema`: Enable Schema.org structured data - default: false

## Support
For support or feature requests, please use the WordPress.org support forums for this plugin.

## How to Use the Shortcode
This plugin includes both a block and a shortcode version. You can access detailed instructions on how to use the shortcode by going to:
WordPress Admin > Settings > Lux Copyright Manager

There you'll find:
- The basic shortcode: `[lux_copyright]`
- A copy button to easily copy the shortcode
- Customizable attributes for advanced usage
- Instructions for both the block and shortcode versions
- Interactive Shortcode Builder with live preview (New!)

---

> **Note**
> This plugin requires WordPress 6.2+ and PHP 7.0+
