<?php

/**
 * Template:			product.php
 * Description:			Hooks to change single product page
 */



// Add Above the Related Products section
add_action('woocommerce_after_single_product_summary', 'woocommerce_after_single_product_summary_hook', 15);
function woocommerce_after_single_product_summary_hook()
{
    echo do_shortcode('[realviews_latest_reviews]');

    global $product;
    $product_id = $product->get_id();
    $transaction_ids = get_post_meta($product_id, '_transaction_ids', true);


    // add transctionsids as attribute??


    foreach ($transaction_ids as $transaction_id) {
        $id = str_replace("-", "@", $transaction_id);

        $exploded_transaction_id = explode("-", $transaction_id);
        $account_id = $exploded_transaction_id[0];
        $timestamp = $exploded_transaction_id[1];
        $formatted_timestamp = str_replace(".", "-", $timestamp);

        $jsonData = json_encode($account_id);     // Encode to JSON
        $encodedAccount = base64_encode($jsonData);     // Encode the JSON string using Base64
?>

        <div class="realviews-write-review-wrapper" data-encoded=" <?php echo $encodedAccount; ?>">
            <p>You bought this on <?php echo date('m/d/Y', round($timestamp)); ?>. </p>
            <div class="btn">Write review</div>
        </div>
<?php
        // debug($exploded_transaction_id);

        // $base_url = "https://testnet.mirrornode.hedera.com"; // todo
        // $endpoint = "/api/v1/transactions/" . $account_id . "-" . $formatted_timestamp;
        // // https://testnet.mirrornode.hedera.com/api/v1/transactions/
        // $ch = curl_init(); // Initialize a cURL session
        // $url = $base_url . $endpoint;

        // curl_setopt($ch, CURLOPT_URL, $url); // Set the URL
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Return the transfer as a string of the return value

        // $response = curl_exec($ch); // Execute the cURL session

        // if (curl_errno($ch)) {
        //     error_log('Error:' . curl_error($ch));
        // } else {
        //     $data = json_decode($response, true); // Decode the JSON response
        //     debug($data);
        // }

        // curl_close($ch); // Close the cURL session
    }
}
