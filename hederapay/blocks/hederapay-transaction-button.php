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

$title = get_field("field_title");
$memo = get_field("field_memo");
$amount = get_field("field_amount");
$currency = get_field("field_currency");

$badge = "";
if ($network == "testnet") {
    $badge = '<span class="hederapay-transaction-button__badge">testnet</span>';
} else if ($network == 'previewnet') {
    $badge = '<span class="hederapay-transaction-button__badge">previewnet</span>';
}

$button_state = $amount == null ? "disabled" : "";

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

        <button type="button" class="btn hederapay-transaction-button" data-attributes="<?php echo $encodedData; ?>" <?php echo $button_state; ?>>
            <?php echo $title; ?><?php echo $badge; ?>
        </button>
    </div>

    <div class="hederapay-transaction-notices"></div>
</div>