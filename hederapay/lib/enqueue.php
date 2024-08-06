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
    wp_enqueue_script('hederapay-script', $path .  'dist/hederapay.bundle.js', array(), time(), array(
        'strategy'  => 'defer', 'in_footer' => false
    ));
}
