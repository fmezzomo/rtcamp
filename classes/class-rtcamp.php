<?php
class RTCamp {
    private $URLAPI = 'https://wptavern.com';

    public function __construct() {
        add_action( 'init', array( $this, 'register_slideshow_block' ) );
        add_action( 'enqueue_block_assets', array( $this, 'slideshow_block_assets' ) );
    }

    public function register_slideshow_block() {
        $default_attributes = array(
            'url'             => $this->URLAPI,
            'showTitle'       => true,
            'showExcerpt'     => false,
            'showDate'        => true,
            'autoScroll'      => false,
            'scrollInterval'  => 5,
            'showArrows'      => true,
            'backgroundColor' => '#ffffff',
            'textColor'       => '#000000',
        );

        wp_register_script(
            'rtcamp-slideshow-block',
            plugins_url( '../js/block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
        );

        wp_localize_script( 'rtcamp-slideshow-block', 'rtcampDefaults', $default_attributes );


        register_block_type( 'rtcamp/slideshow', array(
            'editor_script'   => 'rtcamp-slideshow-block',
            'render_callback' => array( $this, 'render_slideshow' ),
            'attributes'      => array(
                'url'             => array( 'type' => 'string', 'default' => $default_attributes[ 'url' ] ),
                'showTitle'       => array( 'type' => 'boolean', 'default' => $default_attributes[ 'showTitle' ] ),
                'showExcerpt'     => array( 'type' => 'boolean', 'default' => $default_attributes[ 'showExcerpt' ] ),
                'showDate'        => array( 'type' => 'boolean', 'default' => $default_attributes[ 'showDate' ] ),
                'autoScroll'      => array( 'type' => 'boolean', 'default' => $default_attributes[ 'autoScroll' ] ),
                'scrollInterval'  => array( 'type' => 'number', 'default' => $default_attributes[ 'scrollInterval' ] ),
                'showArrows'      => array( 'type' => 'boolean', 'default' => $default_attributes[ 'showArrows' ] ),
                'backgroundColor' => array( 'type' => 'string', 'default' => $default_attributes[ 'backgroundColor' ] ),
                'textColor'       => array( 'type' => 'string', 'default' => $default_attributes[ 'textColor' ] ),
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

        $showTitle = $attributes['showTitle'];
        $showExcerpt = $attributes['showExcerpt'];
        $showDate = $attributes['showDate'];
        $autoScroll = $attributes['autoScroll'];
        $scrollInterval = $attributes['scrollInterval'];
        $showArrows = $attributes['showArrows'];
        $backgroundColor = $attributes['backgroundColor'];
        $textColor = $attributes['textColor'];

        $output  = '<div class="slideshow-container">';
        $output .= '    <input type="text" id="url-input" placeholder="Enter URL..." />';
        $output .= '    <button id="change-slideshow">Apply URL</button>';
        $output .= '    <div class="slideshow">';

        foreach ($posts as $post) {
            $output .= '<div class="slide">';
            $output .= '<a href="' . esc_url($post['link']) . '" target="_blank">';
    
            if ($showTitle) {
                $output .= '<h2 style="color:' . esc_attr($textColor) . ';">' . esc_html($post['title']['rendered']) . '</h2>';
            }
    
            if (isset($post['jetpack_featured_media_url'])) {
                $output .= '<img src="' . esc_url($post['jetpack_featured_media_url']) . '" alt="' . esc_attr($post['title']['rendered']) . '" />';
            }
    
            if ($showDate) {
                $output .= '<p>' . esc_html(date('F j, Y', strtotime($post['date']))) . '</p>';
            }
    
            if ($showExcerpt) {
                $output .= '<p>' . esc_html($post['excerpt']['rendered']) . '</p>';
            }
    
            $output .= '</a>';
            $output .= '</div>';
        }
    
        if ($showArrows) {
            $output .= '<button class="prev">Previous</button>';
            $output .= '<button class="next">Next</button>';
        }
    
        $output .= '</div>';
        $output .= '</div>';
    
        // Add JavaScript for auto-scroll if enabled
        if ($autoScroll) {
            $output .= '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    let interval = setInterval(() => {
                        document.querySelector(".next").click();
                    }, ' . ($scrollInterval * 1000) . ');
                });
            </script>';
        }
    
        return $output;
    }
}
