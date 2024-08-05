<?php

/**		
 * Template:			customizer.php
 * Description:			Customizer modifications
 */


/**
 * Customizer customizations
 * 
 * Use this hook to create new sections, settings and
 * fields for the customizer section.
 * 
 * @since   1.0
 * 
 * For help check out these links below
 * @link    https://codex.wordpress.org/Theme_Customization_API
 * @link    https://css-tricks.com/getting-started-wordpress-customizer/
 */
add_action('customize_register', 'theme_customizer_register');
function theme_customizer_register(WP_Customize_Manager $wp_customize)
{

    // Retina logo setting.
    $wp_customize->add_setting(
        'retina_logo',
        array(
            'transport'            => 'refresh',
            'capability'        => 'edit_theme_options',
            'type'                => 'theme_mod',
        )
    );

    // Retina logo input control
    $wp_customize->add_control(new WP_Customize_Media_Control(
        $wp_customize,
        'retina_logo',
        array(
            'label'                => 'Retina Logo',
            'section'            => 'title_tagline',
            'settings'            => 'retina_logo',
            'mime_type'         => 'image',
            'priority'            => 5
        )
    ));
}
