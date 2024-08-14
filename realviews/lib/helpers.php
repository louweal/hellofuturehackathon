<?php

/**
 * Template:			helpers.php
 * Description:			Custom functions used by the plugin
 */


function getTitle()
{
    global $post;

    // if (is_page()) {
    //     return get_the_title();
    // }
    if (is_product()) {
        $product = wc_get_product($post->ID);
        return $product->get_title();
    } else {
        return get_the_title();
    }
}
