<?php

/**
 * Template:			acf.php
 * Description:			ACF field group creation
 */

function add_hederapay_field_groups()
{

    if (function_exists('acf_add_local_field_group')) :
        acf_add_local_field_group(array(
            'key' => 'group_hederapay_transaction_button', // Unique key for the field group
            'title' => 'HederaPay Transaction Button',
            'fields' => array(
                array(
                    'key' => 'field_network',
                    'label' => 'Network',
                    'name' => 'network',
                    'type' => 'select',
                    // 'instructions' => 'Choose an option.',
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
                    'label' => 'Testnet Account ID',
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
                ),
                array(
                    'key' => 'previewnet_account',
                    'label' => 'Previewnet Account ID',
                    'name' => 'previewnet_account',
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
                                'value' => 'previewnet',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'mainnet_account',
                    'label' => 'Mainnet Account ID',
                    'name' => 'mainnet_account',
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
                                'value' => 'mainnet',
                            ),
                        ),
                    ),
                ),
                array(
                    'key' => 'field_title',
                    'label' => 'Button text',
                    'name' => 'title',
                    'type' => 'text',
                    // 'instructions' => 'Enter the title here.',
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
                        'value' => 'acf/hederapay-transaction-button', // Adjust as needed for different post types or contexts
                    ),
                ),
            ),
        ));

    endif;
}
add_action('acf/init', 'add_hederapay_field_groups');
