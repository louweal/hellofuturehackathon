<?php
add_shortcode('realviews_latest_reviews', 'realviews_latest_reviews_wrapper_function', 5);
function realviews_latest_reviews_wrapper_function($atts)
{
    $shortcode = true;
    $output = realviews_latest_reviews_function($atts, $shortcode);
    return $output;
}
