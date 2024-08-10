<?php
/*
Plugin Name: HederaPay for WooCommerce
Description: Integrate Hedera transactions into your WooCommerce shop.
Version: 0.1
Author: HashPress Pioneers
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}


add_action('plugins_loaded', 'init_hederapay_for_woocommerce_gateway');
function init_hederapay_for_woocommerce_gateway()
{
    // Check if WooCommerce is active
    if (!class_exists('WC_Payment_Gateway')) return;

    // Include our Gateway Class
    include_once 'gateway.php';

    // Add the Gateway to WooCommerce
    add_filter('woocommerce_payment_gateways', 'add_hederapay_payment_gateway');
}

function add_hederapay_payment_gateway($gateways)
{
    $gateways[] = 'WC_Gateway_Hederapay';
    return $gateways;
}
