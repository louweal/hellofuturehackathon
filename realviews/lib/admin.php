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
    <p>Num reviews on product page</p>
    <form method="post" action="options.php">
        <?php
        // settings_fields('hederapay_settings_group');
        // do_settings_sections('hederapay-settings');
        // submit_button();
        ?>
    </form>
<?php
}
