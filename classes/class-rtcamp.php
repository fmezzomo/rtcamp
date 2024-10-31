<?php
class RTCamp {
    public function __construct() {
        add_action( 'init', array( $this, 'register_slideshow_block' ) );
        add_action( 'enqueue_block_assets', array( $this, 'slideshow_block_assets' ) );
    }

    public function register_slideshow_block() {
        wp_register_script(
            'rtcamp-slideshow-block',
            plugins_url( '../js/block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor' ),
        );

        register_block_type('rtcamp/slideshow', array(
            'editor_script'   => 'rtcamp-slideshow-block',
            'render_callback' => array( $this, 'render_slideshow' ),
        ) );
    }

    // Enqueue block editor script and style
    function slideshow_block_assets() {
        wp_enqueue_style(
            'my-slideshow-style',
            plugins_url( '../css/style.css', __FILE__ ),
        );

        wp_enqueue_script(
            'my-slideshow-script',
            plugins_url( '../js/slideshow.js', __FILE__ ),
            array(),
            '1.0',
            true
        );
    }

    public function render_slideshow() {
        $url      = 'https://wptavern.com/wp-json/wp/v2/posts';
        $response = wp_remote_get( $url );

        if ( is_wp_error( $response ) ) {
            return '<div>Error fetching posts.</div>';
        }

        $posts = json_decode( wp_remote_retrieve_body( $response ), true );
        
        if ( empty( $posts ) ) {
            return '<div>No posts found.</div>';
        }

        $output  = '<div class="slideshow">';
        foreach ( $posts as $post ) {
            $output .= '<div class="slide" style="display: none;">';
            $output .= '    <a href="' . esc_url( $post[ 'link' ] ) . '">';
            $output .= '        <h2>'. esc_html( $post[ 'title' ][ 'rendered' ]) . '</h2>';
            $output .= '        <img src="' . esc_url( $post[ 'jetpack_featured_media_url' ] ) . '" alt="'. esc_attr( $post[ 'title' ][ 'rendered' ] ) . '" />';
            $output .= '        <p>' . esc_html( date( 'F j, Y', strtotime( $post[ 'date' ] ) ) ) . '</p>';
            $output .= '    </a>';
            $output .= '</div>';
        }
        $output .= '    <button class="prev">Previous</button>';
        $output .= '    <button class="next">Next</button>';
        $output .= '</div>';

        return $output;


    }
}
