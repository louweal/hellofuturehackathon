<?php

/**
 * Template:			admin.php
 * Description:			Custom admin settings
 */

add_action('admin_menu', function () {
    add_menu_page('HederaPay', 'HederaPay', 'manage_options', 'hederapay', 'hederapay_settings', 'dashicons-money-alt');
});

function hederapay_settings()
{
?>
    <h1>HederaPay</h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('hederapay_settings_group');
        settings_fields('hederapay_options_group');

        ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Network</th>
                <td>
                    <select name="hederapay-network" id="hederapay-network">
                        <option value="testnet" <?php selected(get_option('hederapay-network'), 'testnet'); ?>>Testnet</option>
                        <option value="mainnet" <?php selected(get_option('hederapay-network'), 'mainnet'); ?>>Mainnet</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php
        do_settings_sections('hederapay-settings');
        submit_button();
        ?>
    </form>
<?php
}

add_action('admin_init', 'hederapay_settings_init');
function hederapay_settings_init()
{
    register_setting('hederapay_options_group', 'hederapay-network');
    register_setting('hederapay_settings_group', 'hederapay_options');

    add_settings_section(
        'hederapay_settings_section',
        'Settings',
        'hederapay_settings_section_callback',
        'hederapay-settings'
    );

    $fields = [
        'wallet' => 'Receiver Account',
    ];

    foreach ($fields as $field_id => $field_title) {
        add_settings_field(
            $field_id,
            $field_title,
            'hederapay_field_callback',
            'hederapay-settings',
            'hederapay_settings_section',
            ['id' => $field_id]
        );
    }
}

function hederapay_settings_section_callback()
{
}

function hederapay_field_callback($args)
{
    $options = get_option('hederapay_options');
    $value = isset($options[$args['id']]) ? $options[$args['id']] : '0.0.0000000';
    echo '<input type="text" name="hederapay_options[' . esc_attr($args['id']) . ']" value="' . esc_textarea($value) . '"><span></span>';
}
