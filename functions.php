<?php

/**
 * Template:			functions.php
 * Description:			Overview of all theme functionality
 * 
 * @package 	WordPress
 * @subpackage	Hellofuturehackathon Template
 *
 */

/**
 * All the templates to include
 * 
 */
$templates = array(
    'lib/ajax.php',                // Ajax functions
    'lib/filters.php',            // Filter hooks
    'lib/helpers.php',            // Helper functions
    'lib/theme.php',            // Theme support configuration
    'lib/customizer.php',        // Customizer modifications
    'lib/enqueue.php',            // Enqueue CSS and JS
    'lib/admin.php',            // Custom admin settings
    'lib/head.php',                // wp_head functions
    'lib/body.php',                // wp_body_open functions
    'lib/widgets.php',            // Widget registration
    // 'lib/plugins.php',            // Plugins
);

/**
 * Loop over all the paths and locate the
 * templates. This will include all files into
 * this functions.php file.
 */
foreach ($templates as $template) {
    locate_template($template, true, true);
}



// do_action('warpdrive_cache_flush'); // This will flush the entire cache
