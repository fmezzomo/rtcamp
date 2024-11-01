( function( blocks, element, blockEditor, components ) {
    var el = element.createElement;
    var InspectorControls = blockEditor.InspectorControls;
    var PanelBody = components.PanelBody;
    var ToggleControl = components.ToggleControl;
    var ColorPalette = components.ColorPalette;
    var RangeControl = components.RangeControl;

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
        },

        edit: function( props ) {
            const { attributes, setAttributes } = props;
            const { showTitle, showExcerpt, showDate, autoScroll, scrollInterval, showArrows, backgroundColor, textColor } = attributes;

            return el(
                'div',
                { style: { backgroundColor, color: textColor, padding: '20px' } },
                el( 'p', {}, 'Slideshow Block' ),
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
    window.wp.components
) );
