<?php

function add_hederapay_settings_to_footer()
{
    $settings = get_option('hederapay_settings');

    $name = isset($settings['name']) ? esc_html($settings['name']) : get_bloginfo('name');
    $description = isset($settings['description']) ? esc_html($settings['description']) : get_bloginfo('description');
    $icon = isset($settings['icon']) ? esc_html($settings['icon']) : get_site_icon_url();
    $url = isset($settings['url']) ? esc_html($settings['url']) : home_url();

?>
    <div id="hederapay-app-metadata" style="display: none;" data-name="<?php echo $name; ?>" data-description="<?php echo $description; ?>" data-icon="<?php echo $icon; ?>" data-url="<?php echo $url; ?>"></div>

<?php
}
add_action('wp_footer', 'add_hederapay_settings_to_footer');
