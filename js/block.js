( function( blocks, element, blockEditor, components, i18n ) {
    var el = element.createElement;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var ToggleControl = components.ToggleControl;
    var ColorPalette = components.ColorPalette;
    var RangeControl = components.RangeControl;
    var __ = i18n.__;

    blocks.registerBlockType('rtcamp/slideshow', {
        title: 'rtCamp Block Slideshow',
        description: 'Adds a slideshow block to display recent posts using the WP REST API in the Gutenberg editor.',
        icon: 'format-image',
        category: 'widgets',

        attributes: {
            url: { type: 'string', default: rtcampDefaults.url },
            showTitle: { type: 'boolean', default: rtcampDefaults.showTitle },
            showExcerpt: { type: 'boolean', default: rtcampDefaults.showExcerpt },
            showDate: { type: 'boolean', default: rtcampDefaults.showDate },
            autoScroll: { type: 'boolean', default: rtcampDefaults.autoScroll },
            scrollInterval: { type: 'number', default: rtcampDefaults.scrollInterval },
            showArrows: { type: 'boolean', default: rtcampDefaults.showArrows },
            backgroundColor: { type: 'string', default: rtcampDefaults.backgroundColor },
            textColor: { type: 'string', default: rtcampDefaults.textColor },
            arrowColor: { type: 'string', default: rtcampDefaults.arrowColor },
            arrowBackgroundColor: { type: 'string', default: rtcampDefaults.arrowBackgroundColor },
        },

        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const { showTitle, showExcerpt, showDate, autoScroll, scrollInterval, showArrows, backgroundColor, textColor, arrowColor, arrowBackgroundColor } = attributes;

            return el(
                'div',
                { className: 'slideshow',
                style: { 
                    backgroundColor, 
                    color: textColor, 
                    padding: '20px', 
                    border: '1px solid #ccc', 
                    borderRadius: '5px'
                } 
                },
                showTitle && el( 
                    'p', 
                    { style: { fontWeight: 'bold', fontSize: '18px', marginBottom: '10px' } }, 
                    __( 'Example Post Title', 'text-domain' ) 
                ),
                el(
                    'div',
                    { style: { width: '100%', height: '300px', backgroundColor: '#eaeaea', marginBottom: '10px' } },
                    el( 'span', { style: { display: 'block', textAlign: 'center', paddingTop: '140px', color: '#999' } }, __( 'Image not available', 'text-domain' ) )
                ),
                showDate && el( 
                    'p', 
                    { style: { color: '#666', fontSize: '14px' } }, 
                    __( 'November 1, 2024', 'text-domain' ) 
                ),
                showExcerpt && el( 
                    'p', 
                    { style: { color: '#333', fontSize: '16px' } }, 
                    __( 'Example of post content or excerpt that will be displayed here.', 'text-domain' ) 
                ),
                showArrows && el(
                    'div',
                    { className: 'arrows' }, 
                    el(
                        'button',
                        { style: { left: '30px', backgroundColor: arrowBackgroundColor, color: arrowColor } },
                        '◀'
                    ),
                    el(
                        'button',
                        { style: { right: '30px', backgroundColor: arrowBackgroundColor, color: arrowColor } },
                        '▶'
                    )
                ),
                el(
                    InspectorControls,
                    {},
                    el(
                        PanelBody,
                        { title: 'Slideshow Settings', initialOpen: true },
                        el(
                            ToggleControl,
                            {
                                label: 'Show Title',
                                checked: showTitle,
                                onChange: value => setAttributes({ showTitle: value })
                            }
                        ),
                        el(
                            ToggleControl,
                            {
                                label: 'Show Excerpt',
                                checked: showExcerpt,
                                onChange: value => setAttributes({ showExcerpt: value })
                            }
                        ),
                        el(
                            ToggleControl,
                            {
                                label: 'Show Date',
                                checked: showDate,
                                onChange: value => setAttributes({ showDate: value })
                            }
                        ),
                        el(
                            ToggleControl,
                            {
                                label: 'Auto Scroll',
                                checked: autoScroll,
                                onChange: value => setAttributes({ autoScroll: value })
                            }
                        ),
                        autoScroll && el(
                            RangeControl,
                            {
                                label: 'Scroll Interval (seconds)',
                                value: scrollInterval,
                                onChange: value => setAttributes({ scrollInterval: value }),
                                min: 1,
                                max: 10
                            }
                        ),
                        el(
                            ToggleControl,
                            {
                                label: 'Show Arrows',
                                checked: showArrows,
                                onChange: value => setAttributes({ showArrows: value })
                            }
                        ),
                        el(
                            'p',
                            { style: { marginBottom: '5px' } },
                            'Background Color'
                        ),
                        el(
                            ColorPalette,
                            {
                                value: backgroundColor,
                                onChange: value => setAttributes({ backgroundColor: value })
                            }
                        ),
                        el(
                            'p',
                            { style: { marginBottom: '5px' } },
                            'Text Color'
                        ),
                        el(
                            ColorPalette,
                            {
                                value: textColor,
                                onChange: value => setAttributes({ textColor: value })
                            }
                        ),
                        el(
                            'p',
                            { style: { marginBottom: '5px' } },
                            'Arrow Background Color'
                        ),
                        el(
                            ColorPalette,
                            {
                                value: arrowBackgroundColor,
                                onChange: value => setAttributes({ arrowBackgroundColor: value })
                            }
                        ),
                        el(
                            'p',
                            { style: { marginBottom: '5px' } },
                            'Arrow Color'
                        ),
                        el(
                            ColorPalette,
                            {
                                value: arrowColor,
                                onChange: value => setAttributes({ arrowColor: value })
                            }
                        )
                    )
                )
            );
        },

        save: function() {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element,
    window.wp.blockEditor,
    window.wp.components,
    window.wp.i18n
) );
