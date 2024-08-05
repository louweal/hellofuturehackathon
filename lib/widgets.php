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
        'id'            => 'cart-widget',
        'class'         => 'menu',
        'name'          => 'Cart widget',
        'description'   => __('Widget showing the number of items in the Cart', 'control'),
        'before_title'  => '',
        'after_title'   => '',
        'before_widget' => '',
        'after_widget'  => '',
    );
    register_sidebar($args);
}
