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
            textDomain: { type: 'string', default: rtcampDefaults.textDomain },
            url: { type: 'string', default: rtcampDefaults.url },
            showTitle: { type: 'boolean', default: rtcampDefaults.showTitle },
            imageUrl: { type: 'string', default: rtcampDefaults.imageUrl },
            showExcerpt: { type: 'boolean', default: rtcampDefaults.showExcerpt },
            showDate: { type: 'boolean', default: rtcampDefaults.showDate },
            transitionEffect: { type: 'boolean', default: rtcampDefaults.transitionEffect },
            autoScroll: { type: 'boolean', default: rtcampDefaults.autoScroll },
            scrollInterval: { type: 'number', default: rtcampDefaults.scrollInterval },
            showArrows: { type: 'boolean', default: rtcampDefaults.showArrows },
            backgroundColor: { type: 'string', default: rtcampDefaults.backgroundColor },
            textColor: { type: 'string', default: rtcampDefaults.textColor },
            arrowIconLeft: { type: 'string', default: rtcampDefaults.arrowIconLeft },
            arrowIconRight: { type: 'string', default: rtcampDefaults.arrowIconRight },
            arrowColor: { type: 'string', default: rtcampDefaults.arrowColor },
            arrowBackgroundColor: { type: 'string', default: rtcampDefaults.arrowBackgroundColor },
        },

        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const { textDomain,
                    showTitle,
                    imageUrl,
                    showExcerpt,
                    showDate,
                    transitionEffect,
                    autoScroll,
                    scrollInterval,
                    showArrows,
                    backgroundColor,
                    textColor,
                    arrowIconLeft,
                    arrowIconRight,
                    arrowColor,
                    arrowBackgroundColor } = attributes;

            return el(
                'div',
                    { className: 'slideshow',
                    style: { 
                        backgroundColor: backgroundColor,
                    } 
                },
                el(
                    'div',
                        { className: 'slide' },
                    showTitle && el( 
                        'h3', 
                        { style: { color: textColor } }, 
                        __( 'Example Post Title', textDomain ) 
                    ),
                    el( 'img', { 
                        src: imageUrl,
                        style: { 
                        } 
                    } ),
                    showDate && el( 
                        'p', 
                        { style: { color: textColor } }, 
                        __( 'November 1, 2024', textDomain ) 
                    ),
                    showExcerpt && el( 
                        'p', 
                        { style: { color: textColor } }, 
                        __( 'Example of post content or excerpt that will be displayed here.', textDomain ) 
                    ),
                ),
                showArrows && el(
                    'div',
                    { className: 'arrows' },
                    el(
                        'button',
                        {
                            className: 'prev',
                            style: {
                                backgroundColor: arrowBackgroundColor,
                                color: arrowColor,
                                border: 'none',
                                padding: '10px',
                            }
                        },
                        el('i', { className: 'fas ' + arrowIconLeft })
                    ),
                    el(
                        'button',
                        {
                            className: 'next',
                            style: {
                                backgroundColor: arrowBackgroundColor,
                                color: arrowColor,
                                border: 'none',
                                padding: '10px',
                            }
                        },
                        el('i', { className: 'fas ' + arrowIconRight })
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
                                label: 'Apply Transition Effect',
                                checked: transitionEffect,
                                onChange: value => setAttributes({ transitionEffect: value })
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
                        showArrows && el( wp.components.TextControl, {
                            label: __('Arrow Left Icon Class', textDomain),
                            value: arrowIconLeft,
                            onChange: (value) => setAttributes({ arrowIconLeft: value })
                        }),
                        showArrows && el( wp.components.TextControl, {
                            label: __('Arrow Right Icon Class', textDomain),
                            value: arrowIconRight,
                            onChange: (value) => setAttributes({ arrowIconRight: value })
                        }),
                        showArrows && el(
                            'p',
                            { style: { marginBottom: '5px' } },
                            'Arrow Background Color'
                        ),
                        showArrows && el(
                            ColorPalette,
                            {
                                value: arrowBackgroundColor,
                                onChange: value => setAttributes({ arrowBackgroundColor: value })
                            }
                        ),
                        showArrows && el(
                            'p',
                            { style: { marginBottom: '5px' } },
                            'Arrow Color'
                        ),
                        showArrows && el(
                            ColorPalette,
                            {
                                value: arrowColor,
                                onChange: value => setAttributes({ arrowColor: value })
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
