<?php

/**
 * Template:			admin.php
 * Description:			Custom admin settings
 */

add_action('admin_menu', function () {
    add_menu_page('Realviews', 'Realviews', 'manage_options', 'realviews', 'realviews_settings', 'dashicons-format-chat');
});

function realviews_settings()
{
?>
    <h1>Realviews</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('realviews_settings_group');
        do_settings_sections('realviews-settings');
        submit_button();
        ?>
    </form>
<?php
}

add_action('admin_init', 'realviews_settings_init');
function realviews_settings_init()
{
    register_setting('realviews_settings_group', 'realviews_settings');

    add_settings_section(
        'realviews_settings_section',
        '',
        'realviews_settings_section_callback',
        'realviews-settings'
    );


    add_settings_field(
        'max-reviews',
        'Maximum number of reviews (on product page)',
        'realviews_max_reviews_field_callback',
        'realviews-settings',
        'realviews_settings_section'
    );

    add_settings_field(
        'button-text',
        'Button text',
        'realviews_button_text_field_callback',
        'realviews-settings',
        'realviews_settings_section'
    );
}

function realviews_settings_section_callback()
{
    echo "Global settings for displaying reviews on WooCommerce product pages.";
}


// function realviews_network_field_callback()
// {
//     // Get the current value of the option
//     $settings = get_option('realviews_settings');

//     // Define the options for the select dropdown
//     $options = array(
//         'testnet' => 'Testnet',
//         'previewnet' => 'Previewnet',
//         'mainnet' => 'Mainnet',
//     );

//     echo '<select name="realviews_settings[network]">';
//     foreach ($options as $value => $label) {
//         $selected = ($settings === $value) ? 'selected="selected"' : '';
//         echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . esc_html($label) . '</option>';
//     }
//     echo '</select>';
// }

function realviews_max_reviews_field_callback()
{
    $settings = get_option('realviews_settings');
    $max_reviews = isset($settings['max_reviews']) ? esc_html($settings['max_reviews']) : 2;
?>
    <input type="number" name="realviews_settings[max_reviews]" value="<?php echo $max_reviews; ?>">
<?php
}

function realviews_button_text_field_callback()
{
    $settings = get_option('realviews_settings');
    $button_text = isset($settings['button_text']) ? esc_html($settings['button_text']) : 'All reviews';
?>
    <input type="text" name="realviews_settings[button_text]" value="<?php echo $button_text; ?>">
<?php
}
