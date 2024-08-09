<?php

/**
 * Template:			helpers.php
 * Description:			Custom functions used by the plugin
 */


function get_hbar_price($currency)
{
    static $result = [];

    if (!isset($result[$currency])) {
        try {
            $url = "https://api.coingecko.com/api/v3/simple/price?ids=hedera-hashgraph&vs_currencies=" . $currency;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);
            $result[$currency] = $data['hedera-hashgraph'][$currency];
            return $data['hedera-hashgraph'][$currency];
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
            return;
        }
    } else {
        return $result[$currency];
    }
}

function convert_currency_to_tinybar($amount, $currency)
{
    try {
        $amount = intval($amount);
        $hbar_price = get_hbar_price($currency);
        if ($hbar_price == 0) {
            throw new Exception("Error fetching HBAR price or HBAR price is zero");
        }
        $hbar_amount = $amount / $hbar_price;
        $tinybar_amount = $hbar_amount * 1e8; // 1 HBAR = 100,000,000 tinybars
        return round($tinybar_amount); // Round to the nearest whole number
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        return;
    }
}
