<?php

/**
 * Template:       		shortcodes.php
 * Description:    		Adds shortcodes to the page
 */

// Register the hederapay connect wallet shortcode
add_shortcode('hederapay_connect_button', 'hederapay_connect_button_function');
function hederapay_connect_button_function($atts)
{
    // Define the default attributes
    $atts = shortcode_atts(
        array(
            'connect_text' => 'Connect wallet',
            'disconnect_text' => 'Disconnect wallet',
        ),
        $atts,
        'hederapay_connect_button'
    );

    // Extract the attributes
    $connect_text = esc_html($atts['connect_text']);
    $disconnect_text = esc_html($atts['disconnect_text']);

    $network = get_option('hederapay-network') ?: 'testnet';
    return '<span id="hederapay-output"></span><div data-connect-text="' . $connect_text . '" data-disconnect-text="' . $disconnect_text . '" data-network="' . $network . '" class="btn hederapay-connect-button">' . $connect_text . '</div>';
}

// Register the hederapay transaction button shortcode
add_shortcode('hederapay_transaction_button', 'hederapay_transaction_button_function');
function hederapay_transaction_button_function($atts)
{
    if (!isset($atts['amount'])) {
        return "Please specify the amount you wish to receive.";
    }

    // Define the default attributes
    $atts = shortcode_atts(
        array(
            'title' => 'Pay now',
            'amount' => '0',
            'currency' => 'hbar',
            'wallet' => null
        ),
        $atts,
        'hederapay_transaction_button'
    );

    // Extract the attributes
    $title = esc_html($atts['title']);
    $amount = esc_html($atts['amount']);
    $currency = esc_html($atts['currency']);
    $wallet = esc_html($atts['wallet']);

    if (!$wallet) {
        // use global wallet setting instead
        $options = get_option('hederapay_settings');
        $wallet = isset($options['wallet']) ? $options['wallet'] : null;
    }
    if (!$wallet) {
        return "Please specify receiver wallet.";
    }

    if (!str_starts_with($wallet, '0.0.')) {
        return "Wallet address should start with 0.0.";
    }

    // get global settings
    $network = get_option('hederapay-network') ?: 'testnet';

    // convert amount to tinybar
    $tinybar_amount = $currency == 'hbar' ? $amount * 1e8 : convert_currency_to_tinybar($amount, $currency);

    return '<div class="btn hederapay-transaction-button" data-network="' . $network . '" data-account="' . $wallet . '" data-tinybar-amount="' . $tinybar_amount . '">' . $title . '</div>';
}
