<?php
/**
 * Template:			helpers.php
 * Description:			Custom functions to use around the theme
 */


 /**
 * Get post thumbnail alt attribute
 *
 * @return	string 
 */

function the_post_thumbnail_alt() {
	echo get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true);
}

 /**
 * Subheading shortcode
 *
 * @return span 
 */

 function subheading_shortcode($atts, $content = null) {
    return '<span class="subheading">' . do_shortcode($content) . '</span>';
}

add_shortcode("sub", "subheading_shortcode");

/**
 * Prints a link based on the ACF link field content
 * 
 * @param	array $link $class
 */
function the_link( $link = null, $class = 'link' ) {
	if ( is_array( $link  ) ) {

		// Get the link fields.
		$link_url = $link[ 'url' ];
		$link_title = $link[ 'title' ] ? $link[ 'title' ] : 'Link';
		$link_target = $link[ 'target' ] ? $link[ 'target' ] : '_self';
		$link_scroll = strpos( $link_url, '#' ) > -1 ? ' js-anchor' : '';

		// Echo the link.
		echo '<a class="' . $class . $link_scroll . '" href="' . $link_url . '" target="' . $link_target .'" title="' . $link_title . '">' . $link_title . '</a>';
			
	}
}

 /**
 * Button shortcode
 *
 * @return	button 
 */

function button_shortcode( $atts ) {
	$a = shortcode_atts( array(
		'url' => '#',
		'text' => 'text',
		'target' => '_self',
		'class' => 'btn'
	), $atts );
	return '<a class="' . $a[ "class" ] . '" href="' . $a[ "url" ] . '" target="' . $a[ "target" ] . '" title="' . $a[ "text" ] . '">' . $a[ "text" ] . '</a>';
}

add_shortcode( 'button', 'button_shortcode' );
 
function buttons_shortcode($atts, $content = null) {
    return '<div class="buttons">' . do_shortcode($content) . '</div>';
}

add_shortcode("buttons", "buttons_shortcode");

/**
 * Get the logo from theme mods and return it if it is present
 *
 * @return	string Returns an URL if the logo is present
 */
function get_the_logo() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
	return ! empty( $image ) ? $image[0] : false;
}

function get_the_retina_logo() {
	$retina_logo_id = get_theme_mod( 'retina_logo' );
	$retina_image = wp_get_attachment_image_src( $retina_logo_id , 'full' );
	return ! empty( $retina_image ) ? $retina_image[0] : false;
}

/**
 * Print the logo in the document
 * Echoes an URL if the logo is present
 *
 * @uses 	get_the_logo() to get the logo if it is present
 * @return	null
 */
function the_logo() {
	$logo = get_the_logo();
	if ($logo) echo $logo;
	return null;
}

function the_retina_logo() {
	$retina_logo = get_the_retina_logo();
	if ($retina_logo) echo $retina_logo;
	return null;
}

/**
 * Gets the hero template using get_template_part.
 * 
 * @param	string $name Specific template to get.
 */
function get_hero( $name = null ) {
	get_template_part( './template-parts/hero/hero', $name );
}

/**
 * Gets the layout template using get_template_part.
 * 
 * @param	string $name Specific template to get.
 */
function get_layout( $name = null ) {
	get_template_part( './template-parts/layout/layout', $name );
}

/**
 * Outputs an URL with the relative path to the images folder.
 * 
 * @param	string $file
 */
function the_image_asset( $file ) {
	if ( is_string( $file ) ) {
		echo get_template_directory_uri() . '/assets/images/' . $file;
	}
}

/**
 * Debug function to prettify the output of the standard var_dump function. 
 * @param	variable $variable
 */
function debug( $variable ) {
	echo '<pre>';
	var_dump( $variable );
	echo '</pre>';
}