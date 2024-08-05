<?php
/**			
 * Template:			filters.php
 * Description:			Filters to modify theme
 */

/**
 * Custom excerpt length.
 * 
 * @since	1.0
 * @link	https://developer.wordpress.org/reference/hooks/excerpt_length/
 * @param	integer $length Length of the excerpt
 * @return 	integer
 */
add_filter( 'excerpt_length', 'custom_excerpt_length', 10, 1 );
function custom_excerpt_length( $length ) {
	return 18;
}

/**
 * Custom excerpt more string.
 * 
 * @since	1.0
 * @link	https://developer.wordpress.org/reference/hooks/excerpt_more/
 * @return 	string
 */
add_filter( 'excerpt_more', 'custom_excerpt_more', 10, 1 );
function custom_excerpt_more( $excerpt ) {
	return '...';
}

/**
 * Add svg support
 * 
 */

add_filter('upload_mimes', 'add_svg_support_to_uploads', 10, 1 );
function add_svg_support_to_uploads($file_types){
	$new_filetypes = array();
	$new_filetypes['svg'] = 'image/svg+xml';
	$file_types = array_merge($file_types, $new_filetypes );
	return $file_types;
}