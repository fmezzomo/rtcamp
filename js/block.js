const { registerBlockType } = wp.blocks;

registerBlockType('rtcamp/slideshow', {
    title: 'rtCamp Block Slideshow',
    description: 'Adds a slideshow block to display recent posts using the WP REST API in the Gutenberg editor.',
    icon: 'format-image',
    category: 'widgets'
});
