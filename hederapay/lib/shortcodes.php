<?php

/**
 * Template:       		shortcodes.php
 * Description:    		Adds shortcodes to the page
 */

//Register Paired Account shortcode
add_shortcode('hederapay_paired_account', 'hederapay_paired_account_function');
function hederapay_paired_account_function()
{
    return '<div class="hederapay-paired-account"></div>';
}

// Register the hederapay connect wallet shortcode
add_shortcode('hederapay_connect_button', 'hederapay_connect_button_function');
function hederapay_connect_button_function($atts)
{
    // Define the default attributes
    $atts = shortcode_atts(
        array(
            'network' => 'testnet',
            'connect_text' => 'Connect wallet',
            'disconnect_text' => 'Disconnect wallet',
        ),
        $atts,
        'hederapay_connect_button'
    );

    // Extract the attributes
    $network = esc_html($atts['network']);
    $connect_text = esc_html($atts['connect_text']);
    $disconnect_text = esc_html($atts['disconnect_text']);

    $badge = "";
    if ($network == "testnet") {
        $badge = '<span class="hederapay-transaction-button__badge">testnet</span>';
    } else if ($network == 'previewnet') {
        $badge = '<span class="hederapay-transaction-button__badge">previewnet</span>';
    }

    return '<div data-network="' . $network . '" data-connect-text="' . $connect_text . '" data-disconnect-text="' . $disconnect_text . '" class="btn hederapay-connect-button"><span class="hederapay-connect-button-text">' . $connect_text . '</span>' . $badge . '</div>';
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
            'title' => 'Pay',
            'memo' => null,
            'amount' => null,
            'currency' => 'hbar',
            'testnet_account' => null,
            'previewnet_account' => null,
            'mainnet_account' => null,
            'woocommerce' => false, // executed from HederaPay for WooCommerce
        ),
        $atts,
        'hederapay_transaction_button'
    );

    $num_accounts = isset($atts['testnet_account']) + isset($atts['previewnet_account']) + isset($atts['mainnet_account']);

    if ($num_accounts != 1) {
        return "Please specify one receiver wallet.";
    }

    // Extract the attributes
    $title = esc_html($atts['title']);
    $memo = esc_html($atts['memo']);
    $amount = floatval(esc_html($atts['amount'])); // convert string to float
    $currency = strtolower(esc_html($atts['currency']));
    $testnet_account = esc_html($atts['testnet_account']);
    $previewnet_account =  esc_html($atts['previewnet_account']);
    $mainnet_account =  esc_html($atts['testnet_account']);
    $woocommerce =  esc_html($atts['woocommerce']);

    $result = getAccountAndNetwork($testnet_account, $previewnet_account, $mainnet_account);
    $network = $result["network"];
    $account = $result["account"];

    if (!str_starts_with($account, '0.0.')) {
        return "A Hedera Account ID should look like this: 0.0.xxxxxxx";
    }

    // convert amount to tinybar
    $tinybar_amount = $currency == 'hbar' ? $amount * 1e8 : convert_currency_to_tinybar($amount, $currency);

    $input_field = "";
    if ($amount == null) {
        $input_field = '<input type="number" class="hederapay-transaction-input">';
    }

    $badge = "";
    if ($network == "testnet") {
        $badge = '<span class="hederapay-transaction-button__badge">testnet</span>';
    } else if ($network == 'previewnet') {
        $badge = '<span class="hederapay-transaction-button__badge">previewnet</span>';
    }

    return '<div class="hederapay-transaction-wrapper"><div style="display: flex">' . $input_field . '<div class="btn hederapay-transaction-button" data-currency="' . $currency . '" data-network="' . $network . '" data-account="' . $account . '" data-tinybar-amount="' . $tinybar_amount . '" data-memo="' . $memo . '" data-woocommerce="' . $woocommerce . '">' . $title . $badge . '</div></div><div class="hederapay-transaction-notices"></div></div>';
}

function getAccountAndNetwork($testnet_account, $previewnet_account, $mainnet_account)
{
    if (isset($testnet_account)) {
        return [
            "network" => "testnet",
            "account" => $testnet_account
        ];
    }
    if (isset($previewnet_account)) {
        return [
            "network" => "previewnet",
            "account" => $previewnet_account
        ];
    }
    if (isset($mainnet_account)) {
        return [
            "network" => "mainnet",
            "account" => $mainnet_account
        ];
    }
}
