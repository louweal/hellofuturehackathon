<?php

/**
 * Template:       		shortcodes.php
 * Description:    		Adds shortcodes to the page
 */



//Register list reviews shortcode
add_shortcode('realviews_list_reviews', 'realviews_list_reviews_function');
function realviews_list_reviews_function()
{
    $output = '<div class="realviews-reviews-wrapper">hello</div>';
    return $output;
}
