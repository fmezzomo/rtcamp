<?php
class RTCamp {
    private $URLAPI        = 'https://wptavern.com';
    private $transientKey  = 'rtcamp_slideshow_posts';
    private $cacheTime     = 12 * HOUR_IN_SECONDS; // 12 hours

    public function __construct() {
        add_action( 'init', array( $this, 'register_slideshow_block' ) );
        add_action( 'enqueue_block_assets', array( $this, 'slideshow_block_assets' ) );
    }

    public function register_slideshow_block() {
        $defaultAttributes = array(
            'textDomain'           => 'rtcamp',
            'url'                  => $this->URLAPI,
            'showTitle'            => true,
            'imageUrl'             => plugins_url( '../img/image.jpg', __FILE__ ),
            'showExcerpt'          => false,
            'showDate'             => true,
            'transitionEffect'     => true,
            'autoScroll'           => false,
            'scrollInterval'       => 5,
            'showArrows'           => true,
            'backgroundColor'      => '#ffffff',
            'textColor'            => '#000000',
            'arrowIconLeft'        => 'fa-chevron-left',
            'arrowIconRight'       => 'fa-chevron-right',
            'arrowColor'           => '#ffffff',
            'arrowBackgroundColor' => '#000000',
        );

        wp_register_script(
            'rtcamp-slideshow-block',
            plugins_url( '../js/block.js', __FILE__ ),
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
        );

        wp_localize_script( 'rtcamp-slideshow-block', 'rtcampDefaults', $defaultAttributes );


        register_block_type( 'rtcamp/slideshow', array(
            'editor_script'   => 'rtcamp-slideshow-block',
            'render_callback' => array( $this, 'render_slideshow' ),
            'attributes'      => array(
                'textDomain'           => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'textDomain' ] ),
                'url'                  => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'url' ] ),
                'showTitle'            => array( 'type' => 'boolean', 'default' => $defaultAttributes[ 'showTitle' ] ),
                'imageUrl'             => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'imageUrl' ] ),
                'showExcerpt'          => array( 'type' => 'boolean', 'default' => $defaultAttributes[ 'showExcerpt' ] ),
                'showDate'             => array( 'type' => 'boolean', 'default' => $defaultAttributes[ 'showDate' ] ),
                'transitionEffect'     => array( 'type' => 'boolean', 'default' => $defaultAttributes[ 'transitionEffect' ] ),
                'autoScroll'           => array( 'type' => 'boolean', 'default' => $defaultAttributes[ 'autoScroll' ] ),
                'scrollInterval'       => array( 'type' => 'number', 'default'  => $defaultAttributes[ 'scrollInterval' ] ),
                'showArrows'           => array( 'type' => 'boolean', 'default' => $defaultAttributes[ 'showArrows' ] ),
                'backgroundColor'      => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'backgroundColor' ] ),
                'textColor'            => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'textColor' ] ),
                'arrowIconLeft'        => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'arrowIconLeft' ] ),
                'arrowIconRight'       => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'arrowIconRight' ] ),
                'arrowColor'           => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'arrowColor' ] ),
                'arrowBackgroundColor' => array( 'type' => 'string', 'default'  => $defaultAttributes[ 'arrowBackgroundColor' ] ),
            ),
        ));
    }

    // Enqueue block editor script and style
    function slideshow_block_assets() {
        wp_enqueue_style(
            'rtcamp-slideshow-style',
            plugins_url( '../css/style.css', __FILE__ ),
        );

        wp_enqueue_script(
            'rtcamp-slideshow-script',
            plugins_url( '../js/slideshow.js', __FILE__ ),
            array(),
            '1.0',
            true
        );

        wp_enqueue_style(
            'font-awesome',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css'
        );
    }

    public function render_slideshow( $attributes ) {

        $tryCache   = true;
        $textDomain = $attributes[ 'textDomain' ];
        $inputURL   = $attributes[ 'url' ];

        $urlContainer  = '    <div class="url-container">';
        $urlContainer .= '      <input type="text" id="url-input" placeholder="' . __( 'Enter URL to Fetch Data...', $textDomain ) . '" />';
        $urlContainer .= '      <button id="change-slideshow">' . __( 'Apply URL', $textDomain ) . '</button>';

        if ( isset( $_GET[ 'url' ] ) ) {
            $inputURL = trim( $_GET[ 'url' ] );

            // Get cache data only for the known API
            $tryCache = false;

            $urlContainer .= '  <span class="text-url-fetch">' . __( 'Fetching data from', $textDomain ) . ': ' . $_GET[ 'url' ] . '</span>';
        }
    
        $urlContainer .= '    </div>';

        if ( ! preg_match( "~^(?:f|ht)tps?://~i", $inputURL ) ) {
            $inputURL = 'https://' . $inputURL;
        }


        $url = filter_var( $inputURL, FILTER_VALIDATE_URL ) ? esc_url_raw( $inputURL ) : $this->URLAPI;

        // Check if posts are already cached
        $posts = $tryCache ? get_transient( $this->transientKey ) : false ;

        // If not cached, fetch from API
        if ( $posts === false ) {

            $parsedUrl = parse_url( $url );
            $domain    = isset( $parsedUrl[ 'scheme'] ) ? $parsedUrl[ 'scheme' ] . '://' . $parsedUrl[ 'host' ] : $parsedUrl[ 'host' ];

            $apiUrl = rtrim( $domain, '/' ) . '/wp-json/wp/v2/posts';

            $response = wp_remote_get( $apiUrl );

            if ( is_wp_error( $response ) ) {
                return $urlContainer . '<div>Error fetching posts.</div>';
            }

            $posts = json_decode( wp_remote_retrieve_body( $response ), true );

            // Cache the posts for the period defined into the cacheTime var
            if ( $tryCache ) {
                set_transient( $this->transientKey, $posts, $this->cacheTime );
            }
        }

        if ( empty( $posts ) ) {
            return $urlContainer . '<div>No posts found.</div>';
        }

        $showTitle            = $attributes[ 'showTitle' ];
        $showExcerpt          = $attributes[ 'showExcerpt' ];
        $showDate             = $attributes[ 'showDate' ];
        $transitionEffect     = $attributes[ 'transitionEffect' ];
        $autoScroll           = $attributes[ 'autoScroll' ];
        $scrollInterval       = $attributes[ 'scrollInterval' ];
        $showArrows           = $attributes[ 'showArrows' ];
        $backgroundColor      = $attributes[ 'backgroundColor' ];
        $textColor            = $attributes[ 'textColor' ];
        $arrowColor           = $attributes[ 'arrowColor' ];
        $arrowIconLeft        = $attributes[ 'arrowIconLeft' ];
        $arrowIconRight       = $attributes[ 'arrowIconRight' ];
        $arrowBackgroundColor = $attributes[ 'arrowBackgroundColor' ];

        $output  = '<div >';
        $output .=     $urlContainer;
        $output .= '    <div class="slideshow" class="slideshow-container" style="background-color:' . esc_attr( $backgroundColor ) . ';">';

        if ( $transitionEffect ) {
            $output .= '        <input type="hidden" name="transition-effect" id="transition-effect" value="1">';
        }
    
        if ( $showArrows ) {
            $output .= '<div class="arrows">';
            $output .= '    <button class="prev" style="background-color:' . esc_attr( $arrowBackgroundColor ) . '; color:' . esc_attr( $arrowColor ) . ';">';
            $output .= '        <i class="fas ' . esc_attr( $arrowIconLeft ) . '"></i>';
            $output .= '    </button>';

            $output .= '    <button class="next" style="background-color:' . esc_attr( $arrowBackgroundColor ) . '; color:' . esc_attr( $arrowColor ) . ';">';
            $output .= '        <i class="fas ' . esc_attr( $arrowIconRight ) . '"></i>';
            $output .= '    </button>';
            $output .= '</div>';
        }

        foreach ( $posts as $post ) {
            $output .= '<div class="slide" style="color:' . esc_attr( $textColor ) . ';">';
            $output .= '<a href="' . esc_url( $post[ 'link' ] ) . '" target="_blank">';
    
            if ( $showTitle ) {
                $output .= '<h3 style="color:' . esc_attr( $textColor ) . ';">' . esc_html( $post[ 'title' ][ 'rendered' ] ) . '</h3>';
            }
    
            if ( isset( $post[ 'jetpack_featured_media_url' ] ) ) {
                $output .= '<img src="' . esc_url( $post[ 'jetpack_featured_media_url' ] ) . '" alt="' . esc_attr( $post[ 'title' ][ 'rendered' ] ) . '" />';
            }
    
            $output .= '</a>';

            if ( $showDate ) {
                $output .= '<p>' . esc_html( date( 'F j, Y', strtotime( $post[ 'date' ] ) ) ) . '</p>';
            }
    
            if ( $showExcerpt ) {
                $output .= '<p>' . wp_kses_post( $post[ 'excerpt' ][ 'rendered' ] ) . '</p>';
            }
    
            $output .= '</div>';
        }
    
        $output .= '</div>';
        $output .= '</div>';
    
        // Add JavaScript for auto-scroll if enabled
        if ( $autoScroll ) {
            $output .= '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    let interval = setInterval(() => {
                        document.querySelector(".next").click();
                    }, ' . ( $scrollInterval * 1000 ) . ');
                });
            </script>';
        }
    
        return $output;
    }
}
