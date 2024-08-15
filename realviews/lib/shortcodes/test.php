<?php

add_shortcode('realviews_test', 'realviews_test_function');
function realviews_test_function()
{
    global $post;
    ob_start();

    $transaction_id = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;
    if (!$transaction_id) {
?>
        <div class="btn create-contract-button">Create and pay</div>
    <?php
    } else { ?>

        <p>Successs!</p>

<?php
        $post_id = $post->ID;

        add_transaction_id_to_post($post_id, $transaction_id);
    }
    return ob_get_clean();
}
