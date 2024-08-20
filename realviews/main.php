<?php
/*
Plugin Name: Realviews
Description: Integrate Hedera Smart Contracts into your WordPress website to get verifiable reviews.
Version: 0.1
Author: HashPress Pioneers
Author URI: https://hashpresspioneers.com/
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
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes/latest-reviews.php';
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes/num-reviews.php';


    // load these only if woocommerce is active
    if (!class_exists('WooCommerce')) return;

    require_once plugin_dir_path(__FILE__) . 'lib/product.php';
}

function enable_hederapay()
{
    $hederapay = 'hederapay/main.php';

    if (in_array($hederapay, apply_filters('active_plugins', get_option('active_plugins'))) == false) {
        activate_plugin($hederapay);
    }
}
add_action('init', 'enable_hederapay');



// must be hooked from main ?!
add_action('acf/init', 'realviews_latest_reviews_block');
function realviews_latest_reviews_block()
{
    // Check function exists.
    if (function_exists('acf_register_block_type')) {
        // Register the realviews review list block.
        acf_register_block_type(array(
            'name'              => 'realviews-latest-reviews',
            'title'             => __('Latest Review List (Realviews)', 'hfh'),
            'description'       => __('Show all reviews written for this product, post or page (Realviews)', 'hfh'),
            'render_template'   => dirname(plugin_dir_path(__FILE__)) . '/realviews/blocks/realviews-latest-reviews.php',
            'mode'              => 'edit',
            'category'          => 'common',
            'icon'              => 'admin-comments',
            'keywords'          => array('reviews', 'review', 'list', 'realviews', 'latest'),
        ));
    }
}

// must be hooked from main ?!
add_action('acf/init', 'add_latest_reviews_field_groups');
function add_latest_reviews_field_groups()
{
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_realviews_latest_reviews', // Unique key for the field group
            'title' => 'Latest Reviews (Realviews)',
            'fields' => array(
                array(
                    'key' => 'max_reviews',
                    'label' => 'Number of reviews',
                    'name' => 'max_reviews',
                    'type' => 'number',
                    'instructions' => 'Set to -1 to show all reviews',
                    'required' => 0,
                    'default_value' => 2,
                ),
                array(
                    'key' => 'button_text',
                    'label' => 'Button text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'required' => 0,
                    'default_value' => 'All reviews',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/realviews-latest-reviews',
                    ),
                ),
            ),
        ));
    }
}

add_action('acf/init', 'add_realviews_field_groups', 11);
function add_realviews_field_groups()
{
    if (function_exists('acf_add_local_field_group')) {
        if (!acf_get_local_field_group('group_realviews_transaction_button')) {
            acf_add_local_field_group(array(
                'key' => 'group_realviews_transaction_button', // Unique key for the field group
                'title' => 'Realviews Transaction Button',
                'fields' => array(
                    array(
                        'key' => 'field_store',
                        'label' => 'Store transactions',
                        'name' => 'field_store',
                        'type' => 'true_false',
                        'instructions' => 'Store transaction IDs in page metadata for reviewing.',
                        'ui' => 1,
                        'required' => 0,
                        'default_value' => 0,
                    ),
                ),
                'location' => array(
                    array(
                        array(
                            'param' => 'block',
                            'operator' => '==',
                            'value' => 'acf/hederapay-transaction-button',
                        ),
                    ),
                ),
            ));
        }
    }
}


// add_action('plugins_loaded', function () {
//     error_log('Realviews has been loaded.');
// });
