<?php

/**
 * Template:       		enqueue.php
 * Description:    		Add CSS and Javascript to the page
 */

add_action('wp_enqueue_scripts', 'enqueue_realviews_script');
function enqueue_realviews_script()
{
    // Enqueue the script
    $path = plugin_dir_url(dirname(__FILE__, 1));

    wp_enqueue_script('realviews-main-script', $path .  'dist/main.bundle.js', array(), null, array(
        'strategy'  => 'defer', 'in_footer' => false
    ));
    wp_enqueue_script('realviews-vendor-script', $path .  'dist/vendors.bundle.js', array(), null, array(
        'strategy'  => 'defer', 'in_footer' => false
    ));
}

add_action('wp_enqueue_scripts', 'enqueue_realviews_styles', 20);
function enqueue_realviews_styles()
{
    $path = plugin_dir_url(dirname(__FILE__, 1));

    wp_enqueue_style(
        'realviews-styles', // Handle
        $path . 'src/css/realviews.css',
        array(), // Dependencies
        null, // Version number
        'all' // Media type
    );
}
