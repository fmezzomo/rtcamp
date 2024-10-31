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
            'render_callback' => array($this, 'render_slideshow'),
        ) );
    }

    public function render_slideshow() {
        $url = 'https://wptavern.com/wp-json/wp/v2/posts';
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return '<div>Error fetching posts.</div>';
        }

        $posts = json_decode(wp_remote_retrieve_body($response), true);
        
        if (empty($posts)) {
            return '<div>No posts found.</div>';
        }

        // Build slideshow HTML
        ob_start(); 
        ?>
        <div class="slideshow">
            <?php foreach ($posts as $post): ?>
                <div class="slide" >
                    <a href="<?php echo esc_url($post['link']); ?>">
                        <h2><?php echo esc_html($post['title']['rendered']); ?></h2>
                        <img src="<?php echo esc_url($post['jetpack_featured_media_url']); ?>" alt="<?php echo esc_attr($post['title']['rendered']); ?>" />
                        <p><?php echo esc_html(date('F j, Y', strtotime($post['date']))); ?></p>
                    </a>
                </div>
            <?php endforeach; ?>
            <button class="prev">Previous</button>
            <button class="next">Next</button>
        </div>
        
        <?php
        return ob_get_clean();
    }
}
