<?php
class RTCamp {
    public function __construct() {
        add_action('init', array($this, 'register_slideshow_block'));
    }

    public function register_slideshow_block() {

        wp_register_script(
            'rtcamp-slideshow-block',
            plugins_url('../js/block.js', __FILE__),
            array('wp-blocks', 'wp-element', 'wp-editor'),
        );

        register_block_type('rtcamp/slideshow', array(
            'editor_script' => 'rtcamp-slideshow-block',
        ) );
    }
}
