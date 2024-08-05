<?php

/**  
 * Template:			body.php
 * Description:			Scripts and tags for in the body
 */

/**
 * body_scripts
 * 
 * Add templates that have to be included
 * at the start of the page.
 * 
 * @since   1.0
 * @link    https://developer.wordpress.org/reference/functions/wp_body_open/
 */
add_action('wp_body_open', 'body_scripts');
function body_scripts()
{
}

/**
 * body_open_theme_templates
 * 
 * Add templates that have to be included
 * at the start of the page.
 * 
 * @since   1.0
 * @link    https://developer.wordpress.org/reference/functions/wp_body_open/
 */
add_action('wp_body_open', 'body_open_theme_templates');
function body_open_theme_templates()
{
}
