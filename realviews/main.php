<?php
/*
Plugin Name: Realviews
Description: Integrate Hedera Consensus Service into your WordPress website for trustworthy reviews. 
Version: 0.1
Author: HashPress Pioneers
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'init_realviews_function');

function init_realviews_function()
{
    require_once plugin_dir_path(__FILE__) . 'lib/admin.php';
    require_once plugin_dir_path(__FILE__) . 'lib/enqueue.php';
    require_once plugin_dir_path(__FILE__) . 'lib/helpers.php';
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes.php';
}



// must be hooked from main ?!
add_action('acf/init', 'realviews_block_init');
function realviews_block_init()
{
    // Check function exists.
    if (function_exists('acf_register_block_type')) {
        // Register the realviews review list block.
        acf_register_block_type(array(
            'name'              => 'realviews-list-reviews',
            'title'             => __('Review List (Realviews)', 'hfh'),
            'description'       => __('Show all reviews written for this product, post or page (Realviews)', 'hfh'),
            'render_template'   => dirname(plugin_dir_path(__FILE__)) . '/realviews/blocks/realviews-list-reviews.php',
            'mode'              => 'edit',
            'category'          => 'common',
            'icon'              => 'admin-comments',
            'keywords'          => array('reviews', 'review', 'list', 'realviews'),
        ));
    }
}

// must be hooked from main ?!
// todo add realviews checkbox?



add_action('plugins_loaded', function () {
    error_log('Realviews has been loaded.');
});
