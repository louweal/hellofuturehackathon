<?php

/**
 * Template:			theme.php
 * Description:			Set the core functions of the theme
 */

/**
 * theme_menus
 * 
 * Register navigation menus. Repeat the 
 * register_nav_menu function to register
 * multiple menu's.
 * 
 * @since	1.0
 */
add_action('after_setup_theme', 'theme_menus');
function theme_menus()
{

    // Create default menu
    // register_nav_menu('menu-main', 'Main Menu');
}

/**
 * add_theme_features
 * 
 * Register theme features
 * Setup support for theme features.
 * Uncomment the features that should not be supported.
 * 
 * @since	1.0
 * @link	https://codex.wordpress.org/Theme_Features
 * @link	https://developer.wordpress.org/reference/functions/add_theme_support/
 */
add_action('after_setup_theme', 'add_theme_features');
function add_theme_features()
{

    // Add theme support for title tag
    add_theme_support('title-tag');

    // Add theme support for post formats
    add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'));

    // Add theme support for Featured Images
    add_theme_support('post-thumbnails');

    // Add theme support for HTML5 Semantic Markup
    add_theme_support('html5', array('comment-list', 'comment-form', 'search-form', 'gallery', 'caption'));

    // Add theme support for Custom Logo
    add_theme_support('custom-logo', array(
        'height'      => '',
        'width'       => '',
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    ));

    // Add theme support for selective refresh of widgets in customizer
    add_theme_support('customize-selective-refresh-widgets');

    // Add theme support for Image Size
    add_image_size('retina', 2560, 1600, true);
    add_image_size('full-hd', 1920, 1080, true);

    // Add theme support for Woocommerce
    add_theme_support('woocommerce');
    // add_theme_support('wc-product-gallery-zoom');
    // add_theme_support('wc-product-gallery-lightbox');
    // add_theme_support('wc-product-gallery-slider');
    // remove_theme_support('wc-product-gallery-zoom');
}
