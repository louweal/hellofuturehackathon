<?php
/**
 * Template:			ajax.php
 * Description:			Ajax related functions
 */

 
/**
 * get_posts_ajax
 * 
 * Generic HTTP GET response that processes
 * all the parameters put through the query
 * to get the posts requested.
 * 
 * @since	1.0
 * @link	https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_nopriv_(action)
 * @link	https://codex.wordpress.org/Plugin_API/Action_Reference/wp_ajax_(action)
 * @return	string
 */
add_action( 'wp_ajax_nopriv_get_posts_ajax', 'get_posts_ajax') ;
add_action( 'wp_ajax_get_posts_ajax', 'get_posts_ajax' );
function get_posts_ajax() {
	header( 'Content-Type: text/html' );

	// Get the variables from the GET Request
	$query_post_type		= isset( $_REQUEST[ 'post_type' ] ) ? $_REQUEST[ 'post_type' ] : array( 'post' );
	$query_post_status		= isset( $_REQUEST[ 'post_status' ] ) ? $_REQUEST[ 'post_status' ] : array( 'publish' );	
	$query_posts_per_page	= isset( $_REQUEST[ 'posts_per_page' ] ) ? $_REQUEST[ 'posts_per_page' ] : -1;
	$query_paged			= isset( $_REQUEST[ 'paged' ] ) ? $_REQUEST[ 'paged' ] : 1;
	$query_offset			= isset( $_REQUEST[ 'offset' ] ) ? $_REQUEST[ 'offset' ] : '';
	$query_order 			= isset( $_REQUEST[ 'order' ] ) ? $_REQUEST[ 'order' ] : 'DESC';
	$query_orderby			= isset( $_REQUEST[ 'orderby' ] ) ? $_REQUEST[ 'orderby' ] : 'date';
	$query_p				= isset( $_REQUEST[ 'p' ] ) ? $_REQUEST[ 'p' ] : '';
	$query_s				= isset( $_REQUEST[ 's' ] ) ? $_REQUEST[ 's' ] : '';
	$query_post__in			= isset( $_REQUEST[ 'post__in'] ) ? $_REQUEST[ 'post__in' ] : array();
	$query_post__not_in		= isset( $_REQUEST[ 'post__not_in' ] ) ? $_REQUEST[ 'post__not-in' ] : array();
	$query_meta_key			= isset( $_REQUEST[ 'meta_key' ] ) ? $_REQUEST[ 'meta_key' ] : '';
	$query_meta_value		= isset( $_REQUEST[ 'meta_value' ] ) ? $_REQUEST[ 'meta_value' ] : '';

	// Set arguments for query
	$args = array(
		'post_type'			=> $query_post_type,
		'post_status'		=> $query_post_status,
		'posts_per_page'	=> $query_posts_per_page,
		'paged'				=> $query_paged,
		'offset'			=> $query_offset,
		'order'				=> $query_order,
		'orderby'			=> $query_orderby,
		'p'					=> $query_p,
		's'					=> $query_s,
		'post__in'			=> $query_post__in,
		'post__not_in'		=> $query_post__not_in,
		'meta_key'			=> $query_meta_key,
		'meta_value'		=> $query_meta_value,
		'tax_query'			=> array(),
		'meta_query'		=> array()
	);

	// Fields to ignore for taxonomies
	$excludes = array(
		'action',
		'post_type',
		'posts_per_page',
		'post_status',
		'paged',
		'offset',
		'order',
		'orderby',
		'p',
		's',
		'cat',
		'tag',
		'post__in',
		'post__not_in',
		'meta_key',
		'meta_value',
		'_wp_nonce',
		'_wp_referrer'
	);

	// Get all registered taxonomies
	$taxonomies = get_taxonomies();

	// Loop over remaining query items and pass them as taxonomy filters
	// Or when the keys are not in the taxonomies and also not in the ignores
	// add them to the meta_query array.
	if ( ! empty( $_REQUEST ) ) {
		foreach( $_REQUEST as $item => $value ) {
			if ( in_array( $item, $taxonomies ) ) {
				$args[ 'tax_query' ][] = array(
					'taxonomy'			=> $item,
					'field'				=> 'slug',
					'terms'				=> $value
				);		
			} else if ( ! in_array( $item, $excludes ) ) {
				$args[ 'meta_query' ][] = array(
					'key'				=> $item,
					'value'				=> $value
				);
			}
		}
	}

	// Create a new query.
	$query = new WP_Query( $args );

	// Loop over the query.
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$post_type = get_post_type();

			// Change the output to what you prefer.
			// The response is send as text.
			the_title();

		} wp_reset_postdata();
	} else {

		// Display error message when no posts are found.
        echo '<div class="message">' . __( 'Post has not been found') . '</div>';

	}

	// End connection
	die();
}