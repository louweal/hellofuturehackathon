<?php

/**			
 * Template:			widgets.php
 * Description:			Create custom widgets and sidebars
 */

/**
 * theme_sidebars
 * 
 * Register custom sidebar locations.
 * Repeat the code in the function to register
 * multiple sidebars.
 * 
 * @since	1.0
 */
add_action('widgets_init', 'theme_sidebars');
function theme_sidebars()
{
    $args = array(
        'id'            => 'header-menu',
        'class'         => 'header-menu',
        'name'          => __('Header menu', 'hfh'),
        'description'   => __('Menu area in the header', 'hfh'),
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    );
    register_sidebar($args);
    $args = array(
        'id'            => 'header-widget',
        'class'         => 'header',
        'name'          => __('Header widget', 'hfh'),
        'description'   => __('Widget area after the header', 'hfh'),
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    );
    register_sidebar($args);

    $args = array(
        'id'            => 'footer-widget-1',
        'class'         => 'footer-1',
        'name'          => __('Footer widget 1', 'hfh'),
        'description'   => __('Widget column in footer', 'hfh'),
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    );
    register_sidebar($args);

    $args = array(
        'id'            => 'footer-widget-2',
        'class'         => 'footer-2',
        'name'          => __('Footer widget 2', 'hfh'),
        'description'   => __('Widget column in footer', 'hfh'),
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    );
    register_sidebar($args);

    $args = array(
        'id'            => 'footer-widget-3',
        'class'         => 'footer-3',
        'name'          => __('Footer widget 3', 'hfh'),
        'description'   => __('Widget column in footer', 'hfh'),
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    );
    register_sidebar($args);
}
