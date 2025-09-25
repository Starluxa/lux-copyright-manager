/**
 * The save function defines the way in which the different attributes should
 * be combined into the final markup, which is then serialized by the block
 * editor into `post_content`.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-edit-save/#save
 *
 * @return {null} Element to render.
 */
/**
 * This file defines the save behavior for the Lux Copyright Date Block.
 *
 * The save function determines what is saved to the WordPress post content
 * when a user saves a post containing this block. In this implementation,
 * the function returns null, which indicates that:
 *
 * 1. Dynamic Rendering Approach:
 *    - The block uses server-side rendering (dynamic block)
 *    - Content is generated when the page is loaded, not when saved
 *    - The block is registered with a 'render' property in block.json
 *
 * 2. Key Benefits:
 *    - Ensures copyright dates are always current without resaving posts
 *    - Allows for global settings that can be updated without editing posts
 *    - Reduces post_content size by not saving generated HTML
 *    - Enables consistent behavior between block and shortcode implementations
 *
 * 3. Implementation Details:
 *    - Delegates rendering to the PHP-based lux_get_copyright_html() function
 *    - Maintains consistency with the [lux_copyright] shortcode
 *    - Supports all block attributes through server-side processing
 *    - Provides better performance for frequently updating content
 *
 * This approach is particularly well-suited for the copyright block because
 * copyright years need to update automatically each year without requiring
 * users to edit and resave all posts containing the block.
 */

export default function save() {
	return null;
}
