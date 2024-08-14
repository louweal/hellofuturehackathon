<?php

/**
 * Template:			hederapay-transaction-button.php
 * Description:			Button for transactions on the Hedera Network
 */

$network = get_field("field_network");

switch ($network) {
    case "testnet";
        $account = get_field("testnet_account");
        break;
    case "previewnet":
        $account = get_field("previewnet_account");
        break;
    case "mainnet":
        $account = get_field("mainnet_account");
        break;
    default:
        $account = get_field("testnet_account");
}

if (!$account) {
    echo "<p>Receiver Account ID missing.</p>";
    return;
}

$title = get_field("field_title");
$memo = get_field("field_memo");
$amount = get_field("field_amount");
$currency = get_field("field_currency");
$store = get_field("field_store");


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
    "store" => $store
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

        <button type="button" class="btn hederapay-transaction-button" data-attributes="<?php echo $encodedData; ?>">
            <?php echo $title; ?><?php echo $badge; ?>
        </button>
    </div>

    <div class="hederapay-transaction-notices"></div>

    <?php
    $transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;

    if ($transaction_id) { ?>
        <div class="hederapay-transaction-success">
            <p>Payment received. Thank you!</p>
        </div>
    <?php
    }
    ?>
</div>