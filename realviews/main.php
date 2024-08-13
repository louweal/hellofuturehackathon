<?php
/*
Plugin Name: Realviews
Description: Integrate Hedera Smart Contracts into your WordPress website for trustworthy reviews. 
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
    require_once plugin_dir_path(__FILE__) . 'lib/product.php';
}

// must be hooked from main ?!
add_action('acf/init', 'realviews_block_init', 10);
function realviews_block_init()
{
    // Check function exists.
    if (function_exists('acf_register_block_type')) {
        // Register the realviews transaction button block.
        acf_register_block_type(array(
            'name'              => 'realviews-transaction-button',
            'title'             => __('Realviews Transaction Button', 'hfh'),
            'description'       => __('Button for payments on Hedera with optional refund after review', 'hfh'),
            'render_template'   => dirname(plugin_dir_path(__FILE__)) . '/realviews/blocks/realviews-transaction-button.php',
            'mode'              => 'edit',
            'category'          => 'common',
            'icon'              => 'money-alt',
            'keywords'          => array('hederapay', 'hedera', 'transaction', 'button', 'realviews', 'review'),
        ));
    }
}

add_action('acf/init', 'add_cashback_field_group', 12);
function add_cashback_field_group()
{
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_realviews_cashback_field',
            'title' => 'Cashback field',
            'fields' => array(
                array(
                    'key' => 'field_cashback',
                    'label' => 'Cashback after review',
                    'name' => 'field_cashback',
                    'type' => 'text',
                    'required' => 0,
                    'instructions' => 'Can be an amount in [currency] or a percentage (use %). Leave empty to disable.',
                    'default_value' => '3%'
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'block',
                        'operator' => '==',
                        'value' => 'acf/realviews-transaction-button',
                    ),
                ),
            ),
        ));
    }
}


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
                    'instructions' => 'Leave empty to show all reviews',
                    'required' => 0,
                    'placeholder' => '6',
                ),
                array(
                    'key' => 'button_text',
                    'label' => 'Button text',
                    'name' => 'button_text',
                    'type' => 'text',
                    'required' => 0,
                    'placeholder' => 'All reviews',
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


add_action('plugins_loaded', function () {
    error_log('Realviews has been loaded.');
});
