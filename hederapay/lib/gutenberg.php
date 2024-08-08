<?php

/**
 * 
 * Add custom Block Editor Block
 * 
 */

add_action('acf/init', 'custom_acf_blocks_init');
function custom_acf_blocks_init()
{

    // Check function exists.
    if (function_exists('acf_register_block_type')) {

        // Register a testimonial block.
        acf_register_block_type(array(
            'name'              => 'hederapay-transaction-button',
            'title'             => __('HederaPay Transaction Button', 'hfh'),
            'description'       => __('Button for transactions on the Hedera Network', 'hfh'),
            'render_template'   => 'hederapay/blocks/hederapay-transaction-button.php',
            'mode'              => 'edit',
            'category'          => 'common',
        ));
    }
}
