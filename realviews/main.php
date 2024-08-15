<?php
/*
Plugin Name: Realviews
Description: Integrate Hedera Smart Contracts into your WordPress website to get verifiable reviews.
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
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes/latest-reviews.php';
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes/num-reviews.php';
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes/test.php';
    require_once plugin_dir_path(__FILE__) . 'lib/product.php';
}

function enable_hederapay()
{
    $plugin = 'hederapay/main.php';

    if (!is_plugin_active($plugin)) {
        activate_plugin($plugin);
    }
}
add_action('init', 'enable_hederapay');

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
                        'key' => 'field_network',
                        'label' => 'Network',
                        'name' => 'network',
                        'type' => 'select',
                        'required' => 0,
                        'choices' => array(
                            'testnet' => 'Testnet',
                            'previewnet' => 'Previewnet',
                            'mainnet' => 'Mainnet',
                        ),
                        'wrapper' => array(
                            'width' => '50%',
                        ),
                        'allow_null' => 0, // Do not allow null value
                    ),
                    array(
                        'key' => 'testnet_account',
                        'label' => 'Account ID',
                        'name' => 'testnet_account',
                        'type' => 'text',
                        // 'instructions' => 'Enter the Account Id here.',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '50%',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_network',
                                    'operator' => '==',
                                    'value' => 'testnet',
                                ),
                            ),
                        ),
                        'placeholder' => 'Testnet',
                    ),
                    array(
                        'key' => 'previewnet_account',
                        'label' => 'Account ID',
                        'name' => 'previewnet_account',
                        'type' => 'text',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '50%',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_network',
                                    'operator' => '==',
                                    'value' => 'previewnet',
                                ),
                            ),
                        ),
                        'placeholder' => 'Previewnet',
                    ),
                    array(
                        'key' => 'mainnet_account',
                        'label' => 'Account ID',
                        'name' => 'mainnet_account',
                        'type' => 'text',
                        'required' => 0,
                        'wrapper' => array(
                            'width' => '50%',
                        ),
                        'conditional_logic' => array(
                            array(
                                array(
                                    'field' => 'field_network',
                                    'operator' => '==',
                                    'value' => 'mainnet',
                                ),
                            ),
                        ),
                        'placeholder' => 'Mainnet',
                    ),
                    array(
                        'key' => 'field_title',
                        'label' => 'Button text',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 0,
                        'default_value' => 'Pay',
                    ),
                    array(
                        'key' => 'field_memo',
                        'label' => 'Memo',
                        'name' => 'memo',
                        'type' => 'text',
                        'required' => 0,
                    ),
                    array(
                        'key' => 'field_amount',
                        'label' => 'Amount',
                        'name' => 'amount',
                        'type' => 'number',
                        'instructions' => 'Leave empty to show an input field.',
                        'required' => 0,
                        'min' => 0,
                        'wrapper' => array(
                            'width' => '50%',
                        ),
                    ),
                    array(
                        'key' => 'field_currency',
                        'label' => 'Currency',
                        'name' => 'currency',
                        'type' => 'select',
                        'instructions' => 'Select the currency the amount is in. It will be converted to HBAR using the CoinGecko API.',
                        'required' => 0,
                        'choices' => array(
                            'usd' => 'USD',
                            'eur' => 'EUR',
                            'jpy' => 'JPY',
                            'gbp' => 'GBP',
                            'aud' => 'AUD',
                            'cad' => 'CAD',
                            'cny' => 'CNY',
                            'inr' => 'INR',
                            'brl' => 'BRL',
                            'zar' => 'ZAR',
                            'chf' => 'CHF',
                            'rub' => 'RUB',
                            'nzd' => 'NZD',
                            'mxn' => 'MXN',
                            'sgd' => 'SGD',
                        ),
                        'default_value' => 'usd',
                        'wrapper' => array(
                            'width' => '50%',
                        ),
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
}


add_action('plugins_loaded', function () {
    error_log('Realviews has been loaded.');
});
