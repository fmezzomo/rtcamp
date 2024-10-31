<?php
class RTCamp {
    private $URLAPI = 'https://wptavern.com';

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

        register_block_type( 'rtcamp/slideshow', array(
            'editor_script'   => 'rtcamp-slideshow-block',
            'render_callback' => array( $this, 'render_slideshow' ),
            'attributes' => array(
                'url' => array(
                    'type' => 'string',
                    'default' => $this->URLAPI,
                ),
            ),
        ));
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

    public function render_slideshow( $attributes ) {

        $input_url = isset( $_GET[ 'url' ] ) ? trim( $_GET[ 'url' ] ) : $attributes[ 'url' ];
    
        if ( ! preg_match( "~^(?:f|ht)tps?://~i", $input_url ) ) {
            $input_url = 'https://' . $input_url;
        }

        $url = filter_var( $input_url, FILTER_VALIDATE_URL ) ? esc_url_raw( $input_url ) : $this->URLAPI;

        $parsedUrl = parse_url( $url );
        $domain    = isset( $parsedUrl[ 'scheme'] ) ? $parsedUrl[ 'scheme' ] . '://' . $parsedUrl[ 'host' ] : $parsedUrl[ 'host' ];

        $apiUrl = rtrim( $domain, '/' ) . '/wp-json/wp/v2/posts';

        $response = wp_remote_get( $apiUrl );

        if ( is_wp_error( $response ) ) {
            return '<div>Error fetching posts.</div>';
        }

        $posts = json_decode( wp_remote_retrieve_body( $response ), true );
        
        if ( empty( $posts ) ) {
            return '<div>No posts found.</div>';
        }

        $output  = '<div class="slideshow-container">';
        $output .= '    <input type="text" id="url-input" placeholder="Enter URL..." />';
        $output .= '    <button id="change-slideshow">Apply URL</button>';

        $output .= '    <div class="slideshow">';

        foreach ( $posts as $post ) {
            $output .= '    <div class="slide">';
            $output .= '        <a href="' . esc_url( $post[ 'link' ] ) . '" target="_blank">';
            $output .= '            <h2>'. esc_html( $post[ 'title' ][ 'rendered' ]) . '</h2>';
            $output .= '            <img src="' . esc_url( $post[ 'jetpack_featured_media_url' ] ) . '" alt="'. esc_attr( $post[ 'title' ][ 'rendered' ] ) . '" />';
            $output .= '            <p>' . esc_html( date( 'F j, Y', strtotime( $post[ 'date' ] ) ) ) . '</p>';
            $output .= '        </a>';
            $output .= '    </div>';
        }
        $output .= '        <button class="prev">Previous</button>';
        $output .= '        <button class="next">Next</button>';
        $output .= '    </div>';
        $output .= '</div>';

        return $output;
    }
}
