<?php

/**
 * Template:			realviews-transaction-button.php
 * Description:			Button for transactions on the Hedera Network
 */



$title = get_field("field_title");
$memo = get_field("field_memo");
$amount = get_field("field_amount");
$currency = get_field("field_currency");
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

$badge = "";
if ($network == "testnet") {
    $badge = '<span class="realviews-transaction-button__badge">testnet</span>';
} else if ($network == 'previewnet') {
    $badge = '<span class="realviews-transaction-button__badge">previewnet</span>';
}

$cashback = get_field("field_cashback");


?>

<div class="realviews-transaction-wrapper">
    <div style="display: flex">
        <?php if ($amount == null) { ?>
            <input type="number" class="realviews-transaction-input" placeholder="<?php echo strtoupper($currency); ?>">
        <?php }; //if 
        ?>

        <div class="btn realviews-transaction-button" data-currency="<?php echo $currency; ?>" data-memo="<?php echo $memo; ?>" data-network="<?php echo $network; ?>" data-account="<?php echo $account; ?>" data-amount="<?php echo $amount; ?>">
            <?php echo $cashback_badge; ?><?php echo $title; ?><?php echo $badge; ?>
        </div>
    </div>

    <div class="realviews-transaction-notices"></div>
</div>