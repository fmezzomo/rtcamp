( function( blocks, element ) {
    var el = element.createElement;

    var blockStyle = {
        backGroundColor: '#900',
        color: '#000'
    }

    blocks.registerBlockType('rtcamp/slideshow', {
        title: 'rtCamp Block Slideshow',
        description: 'Adds a slideshow block to display recent posts using the WP REST API in the Gutenberg editor.',
        icon: 'format-image',
        category: 'widgets',
        edit: function() {
            return el(
                'p',
                { style: blockStyle },
                'Slideshow Block',
            );
        },
        save: function() {
            return null;
        }
    });
}(
    window.wp.blocks,
    window.wp.element
) );