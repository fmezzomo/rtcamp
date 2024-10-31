<?php
/*
Plugin Name: RTCamp Slideshow Block
Description: Adds a slideshow block to display recent posts using the WP REST API in the Gutenberg editor.
Version: 1.0
Author: Fábio Mezzomo
*/

if (!defined('ABSPATH')) {
    exit;
}

// Include the main class file
require_once plugin_dir_path(__FILE__) . 'classes/class-rtcamp.php';

// Initialize the RTCamp class
function rtcamp_initialize_plugin() {
    $rtcamp = new RTCamp();
}
add_action('plugins_loaded', 'rtcamp_initialize_plugin');