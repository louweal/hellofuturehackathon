<?php
/*
Plugin Name: HederaPay
Description: Integrate Hedera transactions into your WordPress website. 
Version: 0.1
Author: HashPress Pioneers
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'init_hederapay_function');

function init_hederapay_function()
{
    require_once plugin_dir_path(__FILE__) . 'lib/admin.php';
    require_once plugin_dir_path(__FILE__) . 'lib/enqueue.php';
    require_once plugin_dir_path(__FILE__) . 'lib/helpers.php';
    require_once plugin_dir_path(__FILE__) . 'lib/shortcodes.php';
    require_once plugin_dir_path(__FILE__) . 'lib/footer.php';
}


// must be hooked from main ?!
add_action('acf/init', 'hederapay_block_init');
function hederapay_block_init()
{
    // Check function exists.
    if (function_exists('acf_register_block_type')) {
        // Register the hederapay transaction button block.
        acf_register_block_type(array(
            'name'              => 'hederapay-transaction-button',
            'title'             => __('HederaPay Transaction Button', 'hfh'),
            'description'       => __('Button for transactions on the Hedera Network', 'hfh'),
            'render_template'   => dirname(plugin_dir_path(__FILE__)) . '/hederapay/blocks/hederapay-transaction-button.php',
            'mode'              => 'edit',
            'category'          => 'common',
            'icon'              => 'dashicons-money-alt',
            'keywords'          => array('hederapay', 'hedera', 'transaction', 'button'),
        ));
    }
}


// must be hooked from main ?!
add_action('acf/init', 'add_hederapay_field_groups');
function add_hederapay_field_groups()
{
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_hederapay_transaction_button', // Unique key for the field group
            'title' => 'HederaPay Transaction Button',
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
                    'placeholder' => 'Testnet', // Set default value to USD
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
                    'placeholder' => 'Previewnet', // Set default value to USD
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
                    'placeholder' => 'Mainnet', // Set default value to USD
                ),
                array(
                    'key' => 'field_title',
                    'label' => 'Button text',
                    'name' => 'title',
                    'type' => 'text',
                    'required' => 0,
                    'default_value' => 'Pay', // Set default value to USD
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
                    'default_value' => 'usd', // Set default value to USD
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
                        'value' => 'acf/hederapay-transaction-button',
                    ),
                ),
            ),
        ));
    }
}


add_action('plugins_loaded', function () {
    error_log('HederaPay has been loaded.');
});
