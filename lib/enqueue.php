<?php

/**
 * Template:       		enqueue.php
 * Description:    		Add CSS and Javascript to the page
 */


/**
 * Add attributes to the style tag
 * 
 * Set the rel attribute on the css file to preload.
 * This is a the modern way of async loading files.
 * 
 * @since	1.0
 * @link	https://developer.wordpress.org/reference/hooks/style_loader_tag/
 * @return	string
 */
add_filter('style_loader_tag', 'custom_style_attributes', 10, 4);
function custom_style_attributes($html, $handle, $href, $media)
{
    // Handles to perform the task on
    $handles = array();
    if (in_array($handle, $handles)) {
        return '<link id="' . $handle . '-css" href="' . $href . '" rel="stylesheet" onload="this.media=\'' . $media . '\'">';
    }
    return $html;
}

/**
 * Add attributes to the script tag
 * 
 * Can be used to add a 'async' or 'defer' attribute 
 * to a script tag
 * 
 * @since	1.0
 * @link	https://developer.wordpress.org/reference/hooks/script_loader_tag/
 * @return	string
 */
add_filter('script_loader_tag', 'custom_script_attributes', 10, 3);
function custom_script_attributes($tag, $handle, $src)
{

    // Script that load async
    $async_attr = 'async';
    $async_handles = array();
    if (in_array($handle, $async_handles)) {
        return '<script id="' . $handle . '-js" src="' . $src . '" type="text/javascript" ' . $async_attr . '></script>';
    }

    // Scripts that load defer
    $defer_attr = 'defer';
    $defer_handles = array();
    if (in_array($handle, $defer_handles)) {
        return '<script id="' . $handle . '-js" src="' . $src . '" type="text/javascript" ' . $defer_attr . '></script>';
    }

    // Scripts that are ES6 Modules
    $module_attr = 'module';
    $module_handles = array();
    if (in_array($handle, $module_handles)) {
        return '<script id="' . $handle . '-js" src="' . $src . '" type="' . $module_attr . '"></script>';
    }

    return $tag;
}

/**
 * Theme styles
 * Add styles for the theme
 * 
 * @since	1.0
 */
add_action('wp_enqueue_scripts', 'theme_styles');
function theme_styles()
{

    /**
     * Unregister gutenberg blocks
     */
    // wp_deregister_style( 'wp-block-library' );
    // wp_deregister_style( 'wp-block-library-theme' );

    /**
     * Style
     * 
     * Main stylesheet of this theme
     */
    wp_register_style('main', get_template_directory_uri() . '/dist/main.bundle.css', false, false, 'all');
    wp_enqueue_style('main');
}

/**
 * Theme scripts
 * Add scripts to the head or body
 * 
 * @since	1.0
 */
add_action('wp_enqueue_scripts', 'theme_scripts');
function theme_scripts()
{

    wp_deregister_script('wp-embed');
    // wp_deregister_script( 'jquery' );

    /**
     * WebfontLoader
     * @link	https://github.com/typekit/webfontloader
     */
    wp_enqueue_script('webfontLoader', '//ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', false, false, true);
    wp_add_inline_script('webfontLoader', "WebFont.load({
		google: {
			families: ['League+Script:400?display=swap', 'Montserrat:300,400,500,600,700?display=swap', 'Inter:200,300,400,500,600,700?display=swap']
		}
	});");

    /**
     * jQuery 
     * @link	http://api.jquery.com/
     */
    wp_register_script('jquery', '//cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js', false, '3.5.1', false);
    // wp_enqueue_script( 'jquery' );

    /**
     * Swiper
     * @link	http://idangero.us/swiper/api/
     */
    // wp_register_script( 'swiper', '//cdnjs.cloudflare.com/ajax/libs/Swiper/4.2.6/js/swiper.min.js', false, '4.2.6', true );
    // wp_enqueue_script( 'swiper' );

    /**
     * Script
     * 
     * This file includes the general script of handling
     * interactions and DOM modifications
     */
    wp_register_script('main', get_template_directory_uri() . '/dist/main.bundle.js', false, false, true);
    wp_localize_script('main', 'wp', array(
        'ajax'             => admin_url('admin-ajax.php'),
        'theme'         => get_template_directory_uri(),
        'post'            => array(
            'id'            => get_the_id(),
            'title'            => get_the_title(),
            'type'            => get_post_type(),
            'template'        => basename(get_page_template())
        ),
        'rest'            => esc_url(get_rest_url()),
        'nonce'            => wp_create_nonce('wp_rest'),
    ));
    wp_enqueue_script('main');
}
