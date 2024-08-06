<?php

/**
 * Template:       		shortcodes.php
 * Description:    		Adds shortcodes to the page
 */

// Register the hederapay connect wallet shortcode
add_shortcode('hederapay_connect_button', 'hederapay_connect_button_function');
function hederapay_connect_button_function()
{
    $network = get_option('hederapay-network') ? get_option('hederapay-network') : 'testnet';

    return '<span id="hederapay-output"></span><div data-network="' . $network . '" class="btn hederapay-connect-button">Connect wallet</div>';
}


// Register the hederapay transaction button shortcode
add_shortcode('hederapay_transaction_button', 'hederapay_transaction_button_function');
function hederapay_transaction_button_function($atts)
{
    if (!isset($atts['amount'])) {
        return "Please specify the amount you wish to receive";
    }

    // Define the default attributes
    $atts = shortcode_atts(
        array(
            'title' => 'Pay now',
            'amount' => '0',
            'currency' => 'hbar'
        ),
        $atts,
        'hederapay_transaction_button'
    );

    // Extract the attributes
    $title = esc_html($atts['title']);
    $amount = esc_html($atts['amount']);
    $currency = esc_html($atts['currency']);

    if ($currency == 'hbar') {
        $tinybar_amount = $amount * 1e8;
    } else {
        // convert to tinybar using coingecko API
        $tinybar_amount = convert_currency_to_tinybar($amount, $currency);
    }


    $options = get_option('hederapay_options');
    $wallet = isset($options['wallet']) ? $options['wallet'] : '';
    $network = get_option('hederapay-network') ? get_option('hederapay-network') : 'testnet';

    return '<div class="btn hederapay-transaction-button" data-network="' . $network . '" data-account="' . $wallet . '" data-tinybar-amount="' . $tinybar_amount . '">' . $title . '</div>';
}
