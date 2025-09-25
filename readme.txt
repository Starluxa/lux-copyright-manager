=== Lux Copyright Manager ===

Contributors: StarLux
Tags: copyright, footer, year, block, gutenberg
Requires at least: 6.2
Tested up to: 6.8
Stable tag: 1.0.0
Requires PHP: 7.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Development of this plugin takes place on GitHub. You can find the full, un-minified source code for this release here: https://github.com/Starluxa/lux-copyright-manager/releases/tag/v1.0.0

Display and automatically update your site's copyright date with a block and shortcode.

== Description ==

**Lux Copyright Manager**

Display and automatically update your site's copyright date with a block and shortcode. Provides professional design options and flexible functionality for WordPress sites.

= ✨ Key Features =

**🎨 Professional Design Options**
* Custom background images with perfect positioning
* Flexible text styling with full typography control
* Clean, modern interface that matches any theme

**📅 Smart Date Management**
* Automatically updates copyright year
* Optional date ranges (e.g., "2015-2024")
* Customizable separators and formatting

**🔗 Intelligent Linking**
* One-click site title integration
* Automatic privacy policy linking
* Custom link text and URLs

**🚀 SEO & Performance**
* Built-in Schema.org structured data
* Zero performance impact
* Fully translatable for global sites

**🎯 User Experience**
* Intuitive block editor interface
* Helpful placeholders and guidance
* Reset to defaults functionality

= 🎯 Perfect For =

* **Business Websites** – Professional copyright notices with company branding
* **Agency Sites** – Customizable solutions for client projects
* **E-commerce Stores** – Legal compliance with style
* **Personal Blogs** – Simple, elegant footer solutions
* **Multi-language Sites** – Full internationalization support


== Installation ==

1. **Upload the Plugin**
   * Download the plugin zip file
   * Go to WordPress Admin > Plugins > Add New
   * Click "Upload Plugin" and select the zip file
   * Click "Install Now"

2. **Activate the Plugin**
   * After installation, click "Activate Plugin"

3. **Add the Copyright Block**
   * Go to any page or post editor
   * Click the "+" button to add a new block
   * Search for "Lux Copyright Manager" or look under "Widgets"
   * Click to add the block to your page

4. **Configure Your Settings**
   * Use the block settings in the right sidebar
   * Customize text, dates, links, and styling
   * Add background images if desired

5. **Enjoy Automatic Updates**
   * The copyright year updates automatically each year
   * No more manual updates needed!

= Alternative Installation =

You can also install via FTP:
1. Upload the `lux-copyright-manager` folder to `/wp-content/plugins/`
2. Activate through the WordPress admin

== Frequently Asked Questions ==

= Does this block slow down my site? =

No, this plugin is built with performance in mind:
* Zero database queries on the frontend
* Minimal, optimized JavaScript and CSS
* Only loads assets when the block is actually used
* Built using WordPress core functions for maximum compatibility

= How does the SEO Schema feature work? =

The SEO Schema feature adds structured data markup to help search engines better understand your content:

* **What it does**: Adds Schema.org microdata attributes (`itemscope`, `itemtype`, `itemprop`)
* **Benefits**: Helps search engines display rich snippets and understand your site's structure
* **Automatic**: Works behind the scenes – no configuration needed
* **Standards compliant**: Uses official Schema.org vocabulary

= Can I customize the appearance? =

Yes, the block offers extensive customization:

* **Typography**: Full font size and color control through WordPress theme
* **Backgrounds**: Add custom background images with perfect positioning
* **Spacing**: Control padding and margins through the block editor
* **Colors**: Inherits your theme's color palette
* **Layout**: Responsive design that works on all devices

= Does it work with any theme? =

Yes, the block is designed to work with any WordPress theme that supports the block editor (Gutenberg). It:
* Uses standard WordPress block styling
* Inherits your theme's CSS variables
* Follows WordPress design patterns
* Is fully responsive and accessible

= Can I use it on multiple sites? =

Yes, the plugin license allows unlimited use on:
* Your own websites
* Client websites (if you're a developer/agency)
* Personal and commercial projects

= What if I need the copyright notice in a different location? =

The block can be used anywhere the block editor is available:
* **Footer areas** (most common)
* **Header areas** (less common but possible)
* **Sidebar widgets** (if your theme supports widget blocks)
* **Page content** (for specific legal pages)
* **Template parts** (for global footer templates)

= Is the date range feature required? =

**No!** The date range is completely optional. You can choose from:
* **Current year only**: Shows just the current year (e.g., "© 2024")
* **Date range**: Shows a range (e.g., "© 2015-2024")
* **Custom text**: Add your own custom date format

= How do I update the site title and tagline? =

The block automatically pulls your site's title and tagline from WordPress settings:
1. Go to **WordPress Admin > Settings > General**
2. Update the "Site Title" and "Tagline" fields
3. The block will automatically reflect these changes

= Can I link the site title to a different page? =

**Yes!** You have full control over linking:
* **Homepage**: Link to your site's homepage (default)
* **Custom URL**: Enter any custom URL you prefer
* **No link**: Display as plain text without linking

= Does it support multiple languages? =

**Yes!** Full internationalization support:
* All text strings are translatable
* Includes .pot translation template
* Compatible with translation plugins like WPML and Polylang
* RTL (right-to-left) language support

= What about the privacy policy link? =

The privacy policy feature is smart and flexible:
* **Automatic**: Uses the privacy policy page set in WordPress settings
* **Custom text**: Change the link text from the default "Privacy Policy"
* **Custom URL**: Link to any page or external URL
* **Conditional**: Only shows if a privacy policy page is set

= Will my copyright notice update automatically? =

**Yes!** That's the whole point:
* Updates the year automatically on January 1st
* No manual intervention required
* Works reliably year after year
* Handles leap years and time zones correctly

= Can I reset the block settings? =

**Yes!** If you ever want to start fresh:
1. Select the copyright block
2. Look for the "Reset to Defaults" button in the settings panel
3. Click it to restore all original settings

= Is there a shortcode version? =

**Yes!** For themes that don't support blocks or for use in other areas:
* The plugin includes both block and shortcode functionality
* Use `[lux_copyright_manager]` in posts, pages, or widget areas
* Same features and automatic updates as the block version
* Access detailed instructions and a copy button in the admin: WordPress Admin > Settings > Lux Copyright Manager

= How do I use the Shortcode Builder? =

**The new Shortcode Builder makes it easy to create custom copyright notices:**
* Interactive controls for all shortcode attributes
* Live preview of your copyright notice as you customize it
* Visual examples showing different configurations
* Copy button for generated shortcodes with user feedback
* Comprehensive documentation for each attribute
* Troubleshooting guides for common issues
* Accessible design with keyboard navigation support

To access the Shortcode Builder:
1. Go to WordPress Admin > Settings > Lux Copyright Manager
2. Use the "Shortcode Builder" tab to customize your copyright notice
3. See a live preview of how it will appear on your site
4. Copy the generated shortcode with the click of a button

= Do you offer support? =

**Absolutely!** We provide comprehensive support:
* Detailed documentation and video tutorials
* Responsive support through WordPress.org forums
* Regular updates and improvements
* Compatibility with latest WordPress versions

== Screenshots ==

1. **Block in Action** – screenshot-1.png
   * Clean, professional copyright footer with custom background
   * Shows the block rendering with site title, tagline, and privacy link
   * Demonstrates responsive design and typography integration

2. **Inspector Sidebar – Content Settings** – screenshot-2.png
   * Organized content panel with all text customization options
   * Shows placeholders and help text for user guidance
   * Demonstrates the intuitive interface design

3. **Inspector Sidebar – Date Range Settings** – screenshot-3.png
   * Date range configuration with starting year and separator options
   * Shows the conditional fields that appear when date range is enabled
   * Illustrates the smart, progressive disclosure interface

4. **Inspector Sidebar – Links Settings** – screenshot-4.png
   * Links configuration panel with site title and privacy policy options
   * Shows help text explaining where settings are managed
   * Demonstrates the educational approach to user guidance

5. **Inspector Sidebar – Style Settings** – screenshot-5.png
   * Background image upload and preview functionality
   * Shows the media library integration
   * Illustrates the professional styling options

6. **Inspector Sidebar – SEO & Schema** – screenshot-6.png
   * SEO and Schema.org settings with helpful explanations
   * Shows the tagline toggle and schema enable option
   * Demonstrates the advanced features for power users

== Changelog ==

= 1.0.0 =

* ✨ **New Features**
  * Professional copyright footer block for WordPress
  * Automatic copyright year updates
  * Customizable date ranges with flexible separators
  * Site title and tagline integration
  * Automatic privacy policy linking
  * Custom background image support
  * Schema.org structured data for SEO
  * Full internationalization support
  * Responsive design for all devices
  * Interactive Shortcode Builder with live preview
  * Tab-based admin interface with Builder, Documentation, and Block Instructions
  * Visual examples of different shortcode configurations
  * Copy button for generated shortcodes with user feedback
  * Comprehensive documentation for all shortcode attributes
  * Troubleshooting guides for common issues

* 🎨 **Design & User Experience**
  * Intuitive block editor interface
  * Helpful placeholders and guidance text
  * Progressive disclosure for advanced options
  * Clean, organized inspector sidebar
  * Professional styling with theme integration
  * Reset to defaults functionality
  * Modern, responsive admin interface
  * Accessible design with WCAG compliance
  * Keyboard navigation support
  * High contrast mode support
  * Reduced motion support for users with vestibular disorders
  * Improved error handling and user feedback

* ⚡ **Performance & Technical**
  * Zero frontend performance impact
 * Optimized JavaScript and CSS
  * WordPress coding standards compliance
  * Accessibility ready
  * RTL language support
  * Comprehensive documentation

* 🌍 **Internationalization**
  * Complete translation template (.pot file)
  * All user-facing strings translatable
  * Compatible with WPML and Polylang
  * Proper text domain implementation

* 📦 **Developer Features**
  * Shortcode functionality for non-block themes
  * Extensive customization options
  * WordPress hooks and filters ready
  * Clean, documented code
  * Modern JavaScript (ES6+) and PHP 7.0+

* 🛠 **Compatibility**
  * WordPress 6.2+ required, tested up to 6.6
  * PHP 7.0+ support
  * All major hosting platforms
  * Theme compatibility (any block-enabled theme)

== Upgrade Notice ==

= 1.0.0 =
Initial release – no upgrade notices at this time.


