<?php
/*
Plugin Name: HederaPay
Description: Send (hbar) transactions using the Hedera Network
Version: 0.1
Author: Realviews
*/

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * All the libs to include
 * 
 */

// $path = '';  // todo move to plugins
$path =  '/hederapay/';

$libs = array(
    $path . 'lib/admin.php',            // Custom admin settings
    $path . 'lib/enqueue.php',            // Enqueue CSS and JS
    $path . 'lib/helpers.php',            // Helper functions
    $path . 'lib/shortcodes.php',            // Theme support configuration
);

/**
 * Loop over all the paths and locate the
 * libs. This will include all files into
 * this main.php file.
 */
foreach ($libs as $lib) {
    locate_template($lib, true, true);
}
