<?php

/**
 * Template:       		shortcodes.php
 * Description:    		Adds shortcodes to the page
 */


// for development /testing only
add_shortcode('realviews_create_topic', 'realviews_create_topic_function');
function realviews_create_topic_function()
{
    return '<div class="btn realviews-create-topic-button" data-network="testnet">Create topic</div>';
}



//Register list reviews shortcode
// add_shortcode('realviews_list_reviews', 'realviews_list_reviews_function');
// function realviews_list_reviews_function($atts)
// {
//     return '<div class="realviews-reviews-wrapper">hello</div>';
// }

add_shortcode('realviews_latest_reviews', 'realviews_latest_reviews_function');
function realviews_latest_reviews($atts)
{
    return '<div class="realviews-reviews-wrapper"></div>';
}

add_shortcode('realviews_num_reviews', 'realviews_num_reviews_function');
function realviews_num_reviews_function()
{
    return 5;
}
