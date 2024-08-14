<?php

/**
 * Template:       		enqueue.php
 * Description:    		Add CSS and Javascript to the page
 */

add_action('wp_enqueue_scripts', 'enqueue_realviews_script', 13);
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

add_action('wp_enqueue_scripts', 'enqueue_main_styles', 5);
function enqueue_main_styles()
{
    $path = plugin_dir_url(dirname(__FILE__, 1));

    wp_enqueue_style(
        'realviews-main-styles',
        $path . 'src/css/main.css',
        array(),
        null,
        'all'
    );
}

function dequeue_hederapay_scripts()
{
    wp_dequeue_script('hederapay-main-script');
    wp_dequeue_script('hederapay-vendor-script');
}
add_action('wp_enqueue_scripts', 'dequeue_hederapay_scripts', 100);


add_action('wp_enqueue_scripts', 'enqueue_realviews_styles', 5);
function enqueue_realviews_styles()
{
    $path = plugin_dir_url(dirname(__FILE__, 1));

    wp_enqueue_style(
        'realviews-styles',
        $path . 'src/css/realviews.css',
        array(),
        null,
        'all'
    );
}
