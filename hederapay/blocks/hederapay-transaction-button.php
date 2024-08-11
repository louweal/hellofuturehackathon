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

// debug($amount);
?>
<div class="hederapay-transaction-wrapper">
    <div style="display: flex">
        <?php if ($amount == null) { ?>
            <input type="number" class="hederapay-transaction-input" placeholder="<?php echo strtoupper($currency); ?>">
        <?php }; //if 
        ?>

        <div class="btn hederapay-transaction-button" data-currency="<?php echo $currency; ?>" data-memo="<?php echo $memo; ?>" data-network="<?php echo $network; ?>" data-account="<?php echo $account; ?>" data-amount="<?php echo $amount; ?>">
            <?php echo $title; ?><?php echo $badge; ?>
        </div>
    </div>

    <div class="hederapay-transaction-notices"></div>
</div>