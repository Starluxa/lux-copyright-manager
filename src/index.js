/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
import { registerBlockType } from '@wordpress/blocks';

/**
 * This file serves as the entry point for the Lux Copyright Date Block.
 *
 * It registers the block with the WordPress block editor system, connecting
 * all the necessary components that make up the block's functionality:
 *
 * 1. Block Registration:
 *    - Uses registerBlockType() from @wordpress/blocks
 *    - Passes metadata from block.json for block configuration
 *    - Connects the edit and save components
 *
 * 2. Component Integration:
 *    - Edit component (./edit.js) - Provides the editor interface
 *    - Save component (./save.js) - Defines the saved content structure
 *    - Metadata (./block.json) - Contains block configuration and attributes
 *
 * 3. Key Responsibilities:
 *    - Registers the block with WordPress using a unique name
 *    - Defines the block's behavior in both editor and frontend contexts
 *    - Establishes the connection between block metadata and implementation
 *    - Ensures proper separation of editor and save concerns
 *
 * This file follows WordPress block editor best practices by keeping the
 * registration simple and delegating complex logic to specialized components.
 * The block.json metadata file is used for configuration, which enables
 * WordPress to automatically handle many block features.
 */

/**
 * Internal dependencies
 */
import Edit from './edit';
import save from './save';
import metadata from './block.json';

/**
 * Styles
 */
import './editor.scss';
import './style.scss';


/**
 * Every block starts by registering a new block type definition.
 *
 * @see https://developer.wordpress.org/block-editor/developers/block-api/#registering-a-block
 */
registerBlockType( metadata.name, {
	/**
	 * @see ./edit.js
	 */
	edit: Edit,
	/**
	 * @see ./save.js
	 */
	save,
} );
