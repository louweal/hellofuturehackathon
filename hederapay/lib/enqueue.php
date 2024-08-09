<?php

/**
 * Template:       		enqueue.php
 * Description:    		Add CSS and Javascript to the page
 */

add_action('wp_enqueue_scripts', 'enqueue_hederapay_script');
function enqueue_hederapay_script()
{
    // Enqueue the script
    // $path = plugin_dir_url(__FILE__); // todo move 
    $path =  get_template_directory_uri() . '/hederapay/';

    // todo: remove time()
    wp_enqueue_script('hederapay-main-script', $path .  'dist/main.bundle.js', array(), null, array(
        'strategy'  => 'defer', 'in_footer' => false
    ));
    wp_enqueue_script('hederapay-vendor-script', $path .  'dist/vendors.bundle.js', array(), null, array(
        'strategy'  => 'defer', 'in_footer' => false
    ));
}

add_action('wp_enqueue_scripts', 'hederapay_enqueue_styles', 20);
function hederapay_enqueue_styles()
{
    $path =  get_template_directory_uri() . '/hederapay/';

    wp_enqueue_style(
        'hederapay-styles', // Handle
        $path . '/src/css/hederapay.css',
        array(), // Dependencies
        time(), // Version number
        'all' // Media type
    );
}
