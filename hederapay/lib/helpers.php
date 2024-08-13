<?php

/**
 * Template:			helpers.php
 * Description:			Custom functions used by the plugin
 */

function add_transaction_id_to_post($post_id, $new_value)
{
    $meta_key = '_transaction_ids';
    $current_meta_value = get_post_meta($post_id, $meta_key, true);

    if (empty($current_meta_value) || !is_array($current_meta_value)) {
        $current_meta_value = array();
    }

    $current_meta_value[] = $new_value;
    update_post_meta($post_id, $meta_key, $current_meta_value);
}
