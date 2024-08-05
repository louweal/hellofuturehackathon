<?php
/**
 * Template:			head.php
 * Description:			Head template
 */

/**
 * Remove and disable redundant meta tags from the head.
 */
add_action( 'init', 'cleanup_wp_head', 10 );
function cleanup_wp_head() {
	remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
	remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
	remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
}

/**
 * Disable those damned emoticons.
 * 
 * @since	1.0
 */
add_action( 'init', 'disable_wp_emojicons' );
function disable_wp_emojicons() {
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );

	add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}

/**
 * Remove emoticons from TinyMCE
 * 
 * @since	1.0
 */
function disable_emojicons_tinymce( $plugins ) {
	if ( is_array( $plugins ) ) {
		return array_diff( $plugins, array( 'wpemoji' ) );
	} else {
		return array();
	}
}

/**
 * head_main_meta
 * 
 * Main meta tags used in every project with 
 * tags for charset, Edge, viewport and cleartype
 * 
 * @since   1.0
 * @link    https://codex.wordpress.org/Function_Reference/wp_head
 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
 */
add_action( 'wp_head', 'head_main_meta', 5 );
function head_main_meta() { 
    ?>
		<meta charset="<?php bloginfo( 'charset' ); ?>"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <?php
}

/**
 * head_javascript_active
 * 
 * Places javascript in the head which checks if 
 * js is active. If it is, it removes the 'no-js' class
 * from the <html> element.
 * 
 * @since   1.0
 * @link    https://codex.wordpress.org/Function_Reference/wp_head
 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
 */
add_action( 'wp_head', 'head_javascript_active', 10 );
function head_javascript_active() {
	?>
		<script>document.documentElement.className = document.documentElement.className.replace('no-js', '');</script>
	<?php
}

/**
 * head_pwa_meta
 * 
 * Meta tags for saving the website
 * to the homescreen of a device.
 * With manifest and color settings
 * for Android devices.
 * 
 * @since   1.0
 * @link    https://codex.wordpress.org/Function_Reference/wp_head
 * @link    https://codex.wordpress.org/Plugin_API/Action_Reference/wp_head
 */
add_action( 'wp_head', 'head_pwa_meta', 10 );
function head_pwa_meta() { 
    ?>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
        <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
        <meta name="mobile-web-app-capable" content="yes"/>
        <meta name="theme-color" content="#ffffff"/>
        <link rel="manifest" href="<?php echo get_template_directory_uri() . '/assets/manifest.json'; ?>">
    <?php
}

/**
 * head_scripts
 * 
 * Add scripts that have to be included
 * at the start of the page.
 * 
 * @since   1.0
 * @link  
 */
add_action( 'wp_head', 'head_scripts' );
function head_scripts() {
	?>
	<?php 
}