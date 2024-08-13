<?php

/**
 * Template:			helpers.php
 * Description:			Custom functions to use around the theme
 */


/**
 * Prints a link based on the ACF link field content
 * 
 * @param	array $link $class
 */
function the_link($link = null, $class = 'link')
{
    if (is_array($link)) {

        // Get the link fields.
        $link_url = $link['url'];
        $link_title = $link['title'] ? $link['title'] : 'Link';
        $link_target = $link['target'] ? $link['target'] : '_self';
        $link_scroll = strpos($link_url, '#') > -1 ? ' js-anchor' : '';

        // Echo the link.
        echo '<a class="' . $class . $link_scroll . '" href="' . $link_url . '" target="' . $link_target . '" title="' . $link_title . '">' . $link_title . '</a>';
    }
}


/**
 * Get the logo from theme mods and return it if it is present
 *
 * @return	string Returns an URL if the logo is present
 */
function get_the_logo()
{
    $custom_logo_id = get_theme_mod('custom_logo');
    $image = wp_get_attachment_image_src($custom_logo_id, 'full');
    return !empty($image) ? $image[0] : false;
}

/**
 * Print the logo in the document
 * Echoes an URL if the logo is present
 *
 * @uses 	get_the_logo() to get the logo if it is present
 * @return	null
 */
function the_logo()
{
    $logo = get_the_logo();
    if ($logo) echo $logo;
    return null;
}

/**
 * Gets the hero template using get_template_part.
 * 
 * @param	string $name Specific template to get.
 */
function get_hero($name = null)
{
    get_template_part('./template-parts/hero/hero', $name);
}

/**
 * Gets the layout template using get_template_part.
 * 
 * @param	string $name Specific template to get.
 */
function get_layout($name = null)
{
    get_template_part('./template-parts/layout/layout', $name);
}

/**
 * Outputs an URL with the relative path to the images folder.
 * 
 * @param	string $file
 */
function the_image_asset($file)
{
    if (is_string($file)) {
        echo get_template_directory_uri() . '/assets/images/' . $file;
    }
}

/**
 * Debug function to prettify the output of the standard var_dump function. 
 * @param	variable $variable
 */
function debug($variable)
{
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
}
