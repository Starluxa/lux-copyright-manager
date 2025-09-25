/**
 * Frontend script for Lux Copyright Manager admin page
 * Handles the interactive shortcode builder and live preview
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Tab switching functionality
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            
            var tabName = $(this).data('tab');
            
            // Update nav tabs
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            
            // Update tab content
            $('.tab-content').removeClass('active');
            $('#' + tabName + '-tab').addClass('active');
        });

        // Initialize preview update
        updatePreviewAndShortcode();
        
        // Bind all form controls to update preview
        $('#show_symbol, #show_starting_year, #show_site_title, #link_site_title, #show_tagline, #show_privacy_link, #enable_schema').on('change', function() {
            updatePreviewAndShortcode();
            toggleSubControls();
        });
        
        $('#starting_year, #custom_separator, #custom_before_text, #custom_after_text, #custom_site_title_url, #custom_privacy_text, #custom_privacy_url').on('input change', function() {
            updatePreviewAndShortcode();
        });

        // Initialize sub-control visibility
        toggleSubControls();

        // Copy shortcode functionality
        $('#copy-shortcode').on('click', function() {
            var shortcodeText = $('#generated-shortcode').text();
            
            if (navigator.clipboard && window.isSecureContext) {
                // Use modern clipboard API
                navigator.clipboard.writeText(shortcodeText).then(function() {
                    showCopyFeedback($(this), 'success');
                }.bind(this), function() {
                    showCopyFeedback($(this), 'error');
                }.bind(this));
            } else {
                // Fallback for older browsers
                var textArea = document.createElement('textarea');
                textArea.value = shortcodeText;
                textArea.style.position = 'fixed';
                textArea.style.left = '-999999px';
                textArea.style.top = '-999999px';
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                
                try {
                    document.execCommand('copy');
                    showCopyFeedback($(this), 'success');
                } catch (err) {
                    showCopyFeedback($(this), 'error');
                }
                
                document.body.removeChild(textArea);
            }
        });

        function toggleSubControls() {
            // Show/hide starting year controls
            if ($('#show_starting_year').is(':checked')) {
                $('#starting_year_control').show();
            } else {
                $('#starting_year_control').hide();
            }
            
            // Show/hide site title link controls
            if ($('#show_site_title').is(':checked')) {
                $('#link_site_title_control').show();
            } else {
                $('#link_site_title_control').hide();
            }
            
            // Show/hide privacy link controls
            if ($('#show_privacy_link').is(':checked')) {
                $('#privacy_link_control').show();
            } else {
                $('#privacy_link_control').hide();
            }
        }

        function updatePreviewAndShortcode() {
            var attributes = {};
            var shortcodeAtts = [];

            // Collect form values
            var showSymbol = $('#show_symbol').is(':checked');
            var showStartingYear = $('#show_starting_year').is(':checked');
            var startingYear = $('#starting_year').val();
            var customSeparator = $('#custom_separator').val();
            var customBeforeText = $('#custom_before_text').val();
            var customAfterText = $('#custom_after_text').val();
            var showSiteTitle = $('#show_site_title').is(':checked');
            var linkSiteTitle = $('#link_site_title').is(':checked');
            var customSiteTitleUrl = $('#custom_site_title_url').val();
            var showTagline = $('#show_tagline').is(':checked');
            var showPrivacyLink = $('#show_privacy_link').is(':checked');
            var customPrivacyText = $('#custom_privacy_text').val();
            var customPrivacyUrl = $('#custom_privacy_url').val();
            var enableSchema = $('#enable_schema').is(':checked');

            // Build shortcode attributes (only non-default values)
            if (!showSymbol) {
                shortcodeAtts.push('show_symbol="false"');
            }
            
            if (showStartingYear) {
                shortcodeAtts.push('show_starting_year="true"');
                if (startingYear) {
                    shortcodeAtts.push('starting_year="' + startingYear + '"');
                }
            }
            
            if (customSeparator && customSeparator !== '–') {
                shortcodeAtts.push('custom_separator="' + customSeparator + '"');
            }
            
            if (customBeforeText && customBeforeText !== 'Copyright') {
                shortcodeAtts.push('custom_before_text="' + customBeforeText + '"');
            }
            
            if (customAfterText) {
                shortcodeAtts.push('custom_after_text="' + customAfterText + '"');
            }
            
            if (showSiteTitle) {
                shortcodeAtts.push('show_site_title="true"');
                if (!linkSiteTitle) {
                    shortcodeAtts.push('link_site_title="false"');
                }
                if (customSiteTitleUrl) {
                    shortcodeAtts.push('custom_site_title_url="' + customSiteTitleUrl + '"');
                }
            }
            
            if (showTagline) {
                shortcodeAtts.push('show_tagline="true"');
            }
            
            if (showPrivacyLink) {
                shortcodeAtts.push('show_privacy_link="true"');
                if (customPrivacyText) {
                    shortcodeAtts.push('custom_privacy_text="' + customPrivacyText + '"');
                }
                if (customPrivacyUrl) {
                    shortcodeAtts.push('custom_privacy_url="' + customPrivacyUrl + '"');
                }
            }
            
            if (enableSchema) {
                shortcodeAtts.push('enable_schema="true"');
            }

            // Generate shortcode
            var shortcode = '[lux_copyright_manager';
            if (shortcodeAtts.length > 0) {
                shortcode += ' ' + shortcodeAtts.join(' ');
            }
            shortcode += ']';
            
            $('#generated-shortcode').text(shortcode);

            // Generate preview HTML
            generatePreview({
                showSymbol: showSymbol,
                showStartingYear: showStartingYear,
                startingYear: startingYear,
                customSeparator: customSeparator,
                customBeforeText: customBeforeText,
                customAfterText: customAfterText,
                showSiteTitle: showSiteTitle,
                linkSiteTitle: linkSiteTitle,
                customSiteTitleUrl: customSiteTitleUrl,
                showTagline: showTagline,
                showPrivacyLink: showPrivacyLink,
                customPrivacyText: customPrivacyText,
                customPrivacyUrl: customPrivacyUrl,
                enableSchema: enableSchema
            });
        }

        function generatePreview(attrs) {
            var currentYear = luxCopyrightData.currentYear;
            var separator = attrs.customSeparator || '–';
            var displayDate = currentYear;

            if (attrs.showStartingYear && attrs.startingYear) {
                displayDate = attrs.startingYear + separator + currentYear;
            }

            var beforeText = attrs.customBeforeText || 'Copyright';
            var afterText = attrs.customAfterText || '';
            var symbol = attrs.showSymbol ? '© ' : '';

            var siteTitle = '';
            if (attrs.showSiteTitle) {
                var siteName = luxCopyrightData.siteTitle;
                if (attrs.linkSiteTitle) {
                    var linkUrl = attrs.customSiteTitleUrl || luxCopyrightData.homeUrl;
                    siteTitle = ' <a href="' + linkUrl + '">' + siteName + '</a>';
                } else {
                    siteTitle = ' ' + siteName;
                }
            }

            var tagline = '';
            if (attrs.showTagline && luxCopyrightData.siteTagline) {
                tagline = ' - ' + luxCopyrightData.siteTagline;
            }

            var privacyLink = '';
            if (attrs.showPrivacyLink) {
                var privacyText = attrs.customPrivacyText || 'Privacy Policy';
                var privacyUrl = attrs.customPrivacyUrl || luxCopyrightData.privacyPolicyUrl;
                
                if (privacyUrl) {
                    privacyLink = ' | <a href="' + privacyUrl + '">' + privacyText + '</a>';
                } else {
                    privacyLink = ' | ' + privacyText;
                }
            }

            // Build final preview
            var preview = beforeText;
            if (beforeText) preview += ' ';
            preview += symbol + displayDate;
            if (afterText) preview += ' ' + afterText;
            preview += siteTitle + tagline + privacyLink;

            $('#preview-content').html(preview);
        }

        function showCopyFeedback($button, status) {
            var originalText = $button.text();
            
            if (status === 'success') {
                $button.text('Copied!').addClass('copy-success');
                setTimeout(function() {
                    $button.text(originalText).removeClass('copy-success');
                }, 2000);
            } else {
                $button.text('Copy Failed').addClass('copy-error');
                setTimeout(function() {
                    $button.text(originalText).removeClass('copy-error');
                }, 2000);
            }
        }
    });

})(jQuery);