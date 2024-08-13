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

    $data = array(
        "network" => $network,
        "connect_text" => $connect_text,
        "disconnect_text" => $disconnect_text
    );

    $jsonData = json_encode($data);     // Encode to JSON
    $encodedData = base64_encode($jsonData);     // Encode the JSON string using Base64


    $badge = "";
    if ($network == "testnet") {
        $badge = '<span class="hederapay-transaction-button__badge">testnet</span>';
    } else if ($network == 'previewnet') {
        $badge = '<span class="hederapay-transaction-button__badge">previewnet</span>';
    }

    return '<div data-attributes="' . $encodedData . '" class="btn hederapay-connect-button"><span class="hederapay-connect-button-text">' . $connect_text . '</span>' . $badge . '</div>';
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
            'woocommerce' => false, // executed from WooCommerce
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

    $button_state = $amount == null ? "disabled" : "";

    $badge = "";
    if ($network == "testnet") {
        $badge = '<span class="hederapay-transaction-button__badge">testnet</span>';
    } else if ($network == 'previewnet') {
        $badge = '<span class="hederapay-transaction-button__badge">previewnet</span>';
    }

    $data = array(
        "currency" => $currency,
        "memo" => $memo,
        "network" => $network,
        "account" => $account,
        "amount" => $amount,
    );

    $jsonData = json_encode($data);     // Encode to JSON
    $encodedData = base64_encode($jsonData);     // Encode the JSON string using Base64


?>
    <div class="hederapay-transaction-wrapper">
        <div style="display: flex">
            <?php if ($amount == null) { ?>
                <input type="number" class="hederapay-transaction-input" placeholder="<?php echo strtoupper($currency); ?>">
            <?php }; //if 
            ?>

            <button type="button" class="btn hederapay-transaction-button" data-woocommerce="<?php echo $woocommerce; ?>" data-attributes="<?php echo $encodedData; ?>" <?php echo $button_state; ?>>
                <?php echo $title; ?><?php echo $badge; ?>
            </button>
        </div>

        <div class="hederapay-transaction-notices"></div>
    </div>
<?php
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
