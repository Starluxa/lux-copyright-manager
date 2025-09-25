import { __ } from '@wordpress/i18n';
import { InspectorControls, useBlockProps, MediaUpload } from '@wordpress/block-editor';
import { PanelBody, TextControl, ToggleControl, Button } from '@wordpress/components';
import { useSelect } from '@wordpress/data';

/**
 * This file defines the editor interface for the Lux Copyright Date Block.
 *
 * The Edit component provides a comprehensive and user-friendly interface
 * within the WordPress block editor (Gutenberg) that allows users to
 * customize the copyright block without needing to write code. It includes:
 *
 * 1. Visual Editing Experience:
 *    - Real-time preview of the copyright block as attributes change
 *    - Intuitive controls organized in logical sections
 *    - Responsive design that works in the block editor
 *
 * 2. Comprehensive Control Panel:
 *    - Content settings (copyright symbol, before/after text)
 *    - Date range configuration (starting year, separator)
 *    - Link management (site title, privacy policy)
 *    - Styling options (background images)
 *    - SEO features (Schema.org structured data, tagline)
 *
 * 3. Key Features:
 *    - Live preview updates as users modify settings
 *    - Default attribute values for sensible out-of-box experience
 *    - Site data integration (title, tagline) with error handling
 *    - Background image selection with preview and removal
 *    - Reset to defaults functionality
 *    - Internationalization support for all text elements
 *    - Accessible form controls with proper labeling
 *
 * 4. Technical Implementation:
 *    - Uses WordPress Data Module (@wordpress/data) for site data
 *    - Implements WordPress Components (@wordpress/components) for UI
 *    - Follows WordPress block editor patterns (@wordpress/block-editor)
 *    - Properly handles asynchronous data fetching with error boundaries
 *    - Uses React hooks for state management
 *    - Implements block wrapper props for consistent styling
 *
 * The component is designed to be both powerful and approachable, giving
 * users complete control over their copyright display while maintaining
 * ease of use through thoughtful organization and real-time feedback.
 */

const defaultAttributes = {
    showStartingYear: false,
    startingYear: '',
    showSymbol: true,
    showSiteTitle: false,
    showPrivacyLink: false,
    customPrivacyUrl: '',
    customPrivacyText: '',
    linkSiteTitle: false,
    linkPrivacyLink: false,
    customSiteTitleUrl: '',
    customBeforeText: 'Copyright',
    customAfterText: '',
    customSeparator: '–',
    bgImageUrl: undefined,
    bgImageId: undefined,
    enableSchema: false,
    showTagline: false,
};

export default function Edit( { attributes, setAttributes } ) {
	const {
		showStartingYear,
		startingYear,
		showSymbol,
		showSiteTitle,
		showPrivacyLink,
		linkSiteTitle,
		linkPrivacyLink,
		customSiteTitleUrl,
		customBeforeText,
		customAfterText,
	customSeparator,
		customPrivacyUrl,
		customPrivacyText,
		bgImageUrl,
		bgImageId,
		enableSchema,
		showTagline,
	} = attributes;

	const currentYear = new Date().getFullYear().toString();
	let displayDate;
	if ( showStartingYear && startingYear ) {
		displayDate = startingYear + customSeparator + currentYear;
	} else {
		displayDate = currentYear;
	}

	const { siteTitle, siteTagline } = useSelect( ( select ) => {
		try {
			const siteData = select( 'core' ).getEntityRecord( 'root', 'site' );
			return {
				siteTitle: siteData?.title,
				siteTagline: siteData?.description,
			};
		} catch ( error ) {
			// Handle connection errors gracefully with fallback values
			console.warn( 'Could not fetch site data:', error );
			return {
				siteTitle: null,
				siteTagline: null,
			};
		}
	}, [] );

	const onReset = () => {
		setAttributes( defaultAttributes );
	};

	const blockProps = useBlockProps( {
		style: {
			backgroundImage: bgImageUrl ? `url(${ bgImageUrl })` : 'none',
			backgroundSize: 'cover',
			backgroundPosition: 'center',
		},
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Content', 'lux-copyright-manager' ) }>
					<ToggleControl
						checked={ showSymbol }
						label={ __( 'Show © symbol', 'lux-copyright-manager' ) }
						onChange={ () => setAttributes( { showSymbol: ! showSymbol } ) }
						__nextHasNoMarginBottom={ true }
					/>
					<TextControl
						label={ __( 'Text before year', 'lux-copyright-manager' ) }
						value={ customBeforeText }
						onChange={ ( value ) => setAttributes( { customBeforeText: value } ) }
						placeholder={ __( 'e.g., Copyright', 'lux-copyright-manager' ) }
						__nextHasNoMarginBottom={ true }
						__next40pxDefaultSize={ true }
					/>
					<TextControl
						label={ __( 'Text after year', 'lux-copyright-manager' ) }
						value={ customAfterText }
						onChange={ ( value ) => setAttributes( { customAfterText: value } ) }
						placeholder={ __( 'e.g., All rights reserved', 'lux-copyright-manager' ) }
						__nextHasNoMarginBottom={ true }
						__next40pxDefaultSize={ true }
					/>
				</PanelBody>
				<PanelBody title={ __( 'Date Range', 'lux-copyright-manager' ) } initialOpen={ false }>
					<ToggleControl
						checked={ showStartingYear }
						label={ __( 'Enable date range', 'lux-copyright-manager' ) }
						onChange={ () => setAttributes( { showStartingYear: ! showStartingYear } ) }
						__nextHasNoMarginBottom={ true }
					/>
					{ showStartingYear && (
						<>
							<TextControl
								label={ __( 'Starting year', 'lux-copyright-manager' ) }
								value={ startingYear }
								onChange={ ( value ) => setAttributes( { startingYear: value } ) }
								placeholder={ ( new Date().getFullYear() - 10 ).toString() }
								__nextHasNoMarginBottom={ true }
								__next40pxDefaultSize={ true }
							/>
							<TextControl
								label={ __( 'Date range separator', 'lux-copyright-manager' ) }
								value={ customSeparator }
								onChange={ ( value ) => setAttributes( { customSeparator: value } ) }
								placeholder={ __( 'e.g., – or to', 'lux-copyright-manager' ) }
								__nextHasNoMarginBottom={ true }
								__next40pxDefaultSize={ true }
							/>
						</>
					) }
				</PanelBody>
				<PanelBody title={ __( 'Links', 'lux-copyright-manager' ) } initialOpen={ false }>
					<ToggleControl
						checked={ showSiteTitle }
						label={ __( 'Show site title', 'lux-copyright-manager' ) }
						onChange={ () => setAttributes( { showSiteTitle: ! showSiteTitle } ) }
						help={ __( 'Edit in Settings > General', 'lux-copyright-manager' ) }
						__nextHasNoMarginBottom={ true }
					/>
					{ showSiteTitle && (
						<>
							<ToggleControl
								checked={ linkSiteTitle }
								label={ __( 'Link site title to homepage', 'lux-copyright-manager' ) }
								onChange={ () => setAttributes( { linkSiteTitle: ! linkSiteTitle } ) }
								__nextHasNoMarginBottom={ true }
							/>
							{ linkSiteTitle && (
								<TextControl
									label={ __( 'Custom site title URL', 'lux-copyright-manager' ) }
									value={ customSiteTitleUrl }
									onChange={ ( value ) => setAttributes( { customSiteTitleUrl: value } ) }
									placeholder={ __( 'e.g., https://example.com', 'lux-copyright-manager' ) }
									__next40pxDefaultSize={ true }
								/>
							) }
						</>
					) }
					<hr />
					<ToggleControl
						checked={ showPrivacyLink }
						label={ __( 'Show privacy policy link', 'lux-copyright-manager' ) }
						onChange={ () => setAttributes( { showPrivacyLink: ! showPrivacyLink } ) }
						help={ __( 'Uses the page set in Settings > Privacy.', 'lux-copyright-manager' ) }
						__nextHasNoMarginBottom={ true }
					/>
					{ showPrivacyLink && (
						<>
							<ToggleControl
								checked={ linkPrivacyLink }
								label={ __( 'Link privacy policy to URL', 'lux-copyright-manager' ) }
								onChange={ () => setAttributes( { linkPrivacyLink: ! linkPrivacyLink } ) }
								__nextHasNoMarginBottom={ true }
							/>
							<TextControl
								label={ __( 'Custom link text', 'lux-copyright-manager' ) }
								value={ customPrivacyText }
								onChange={ ( value ) => setAttributes( { customPrivacyText: value } ) }
								placeholder={ __( 'e.g., Privacy Policy', 'lux-copyright-manager' ) }
								__nextHasNoMarginBottom={ true }
								__next40pxDefaultSize={ true }
							/>
							<TextControl
								label={ __( 'Custom link URL', 'lux-copyright-manager' ) }
								value={ customPrivacyUrl }
								onChange={ ( value ) => setAttributes( { customPrivacyUrl: value } ) }
								placeholder={ __( 'e.g., /privacy-policy', 'lux-copyright-manager' ) }
								__nextHasNoMarginBottom={ true }
								__next40pxDefaultSize={ true }
							/>
						</>
					) }
				</PanelBody>
				<PanelBody title={ __( 'Style', 'lux-copyright-manager' ) } initialOpen={ false }>
					<div className="editor-post-featured-image">
						{(() => {
							try {
								return (
									<MediaUpload
										onSelect={ ( media ) => setAttributes( { bgImageUrl: media.url, bgImageId: media.id } ) }
										allowedTypes={ [ 'image' ] }
										value={ attributes.bgImageId }
										render={ ( { open } ) => (
											<Button
												onClick={ open }
												variant="secondary"
											>
												{ __( 'Select Background Image', 'lux-copyright-manager' ) }
											</Button>
										) }
									/>
								);
							} catch ( error ) {
								console.warn( 'MediaUpload component error:', error );
								return (
									<div style={ { padding: '10px', backgroundColor: '#f0f0f0', border: '1px solid #ccc', borderRadius: '4px' } }>
										<p style={ { margin: '0 0 10px 0', fontSize: '12px', color: '#666' } }>
											{ __( 'Media library is currently unavailable.', 'lux-copyright-manager' ) }
										</p>
										<Button
											variant="secondary"
											disabled
											style={ { opacity: 0.6 } }
										>
											{ __( 'Select Background Image', 'lux-copyright-manager' ) }
										</Button>
									</div>
								);
							}
						})()}
						{ bgImageUrl && (
							<>
								<img src={ bgImageUrl } alt={ __( 'Background Preview', 'lux-copyright-manager' ) } style={ { width: '100%', height: 'auto', marginTop: '10px' } } />
								<Button
									onClick={ () => setAttributes( { bgImageUrl: undefined, bgImageId: undefined } ) }
									isLink
									isDestructive
								>
									{ __( 'Remove Background Image', 'lux-copyright-manager' ) }
								</Button>
							</>
						) }
					</div>
				</PanelBody>
				<PanelBody title={ __( 'SEO & Schema', 'lux-copyright-manager' ) } initialOpen={ false }>
					<ToggleControl
						checked={ showTagline }
						label={ __( 'Show site tagline', 'lux-copyright-manager' ) }
						onChange={ () => setAttributes( { showTagline: ! showTagline } ) }
						help={ __( 'Edit in Settings > General', 'lux-copyright-manager' ) }
						__nextHasNoMarginBottom={ true }
					/>
					<ToggleControl
						checked={ enableSchema }
						label={ __( 'Enable SEO Schema', 'lux-copyright-manager' ) }
						onChange={ () => setAttributes( { enableSchema: ! enableSchema } ) }
						help={ __( 'Adds structured data for search engines.', 'lux-copyright-manager' ) }
						__nextHasNoMarginBottom={ true }
					/>
				</PanelBody>
				<div style={ { padding: '16px' } }>
					<Button
						variant="secondary"
						isDestructive
						onClick={ onReset }
						style={ { width: '100%' } }
					>
						{ __( 'Reset to Defaults', 'lux-copyright-manager' ) }
					</Button>
				</div>
			</InspectorControls>
			<p { ...blockProps }>
				{ customBeforeText && `${ customBeforeText } ` }
				{ showSymbol && '© ' }
				{ displayDate }
				{ customAfterText && ` ${ customAfterText }` }
				{ ( showSiteTitle || showTagline ) && (
					<>
						{ showSiteTitle && ( linkSiteTitle ? <a href={ customSiteTitleUrl || "# " } onClick={ ( e ) => { if ( ! customSiteTitleUrl ) e.preventDefault(); } }>{ ` ${ siteTitle || __( 'Your Site Title', 'lux-copyright-manager' ) }` }</a> : ` ${ siteTitle || __( 'Your Site Title', 'lux-copyright-manager' ) }` ) }
						{ showTagline && ` - ${ siteTagline || __( 'Your Site Tagline', 'lux-copyright-manager' ) }` }
					</>
				) }
				{ showPrivacyLink && ( linkPrivacyLink ? <a href="# " onClick={ ( e ) => e.preventDefault() }>{ ` | ${ customPrivacyText || __( 'Privacy Policy', 'lux-copyright-manager' ) }` }</a> : ` | ${ customPrivacyText || __( 'Privacy Policy', 'lux-copyright-manager' ) }` ) }
			</p>
		</>
	);
}

