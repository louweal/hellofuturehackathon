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
        do_settings_sections('hederapay-settings');
        submit_button();
        ?>
    </form>
<?php
}

add_action('admin_init', 'hederapay_settings_init');
function hederapay_settings_init()
{
    register_setting('hederapay_settings_group', 'hederapay_settings');

    add_settings_section(
        'hederapay_settings_section',
        'Default Settings',
        'hederapay_settings_section_callback',
        'hederapay-settings'
    );

    add_settings_field(
        'network',
        'Network',
        'hederapay_network_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );

    add_settings_field(
        'testnet_wallet',
        'Testnet Account ID',
        'hederapay_testnet_wallet_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );
    add_settings_field(
        'previewnet_wallet',
        'Previewnet Account ID',
        'hederapay_previewnet_wallet_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );
    add_settings_field(
        'mainnet_wallet',
        'Mainnet Account ID',
        'hederapay_mainnet_wallet_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );
    add_settings_field(
        'button_text',
        'Button Text',
        'hederapay_button_text_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );
    add_settings_field(
        'amount',
        'Amount',
        'hederapay_amount_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );
    add_settings_field(
        'currency',
        'Currency',
        'hederapay_currency_field_callback',
        'hederapay-settings',
        'hederapay_settings_section'
    );
}

function hederapay_settings_section_callback()
{
    // Callback for the settings section. Can be left empty.
    echo "The default settings are used when values are not specified within the button instances (shortcodes and Gutenberg blocks).";
}

function hederapay_network_field_callback()
{
    $options = get_option('hederapay_settings');
    $network = $options['network'] ?? '';
?>
    <select name="hederapay_settings[network]" id="hederapay-network">
        <option value="testnet" <?php selected($network, 'testnet'); ?>>Testnet</option>
        <option value="previewnet" <?php selected($network, 'previewnet'); ?>>Previewnet</option>
        <option value="mainnet" <?php selected($network, 'mainnet'); ?>>Mainnet</option>
    </select>
<?php
}

function hederapay_testnet_wallet_field_callback()
{
    $options = get_option('hederapay_settings');
    $wallet_value = isset($options['testnet_wallet']) ? esc_textarea($options['testnet_wallet']) : '0.0.0000000';
?>
    <input type="text" name="hederapay_settings[testnet_wallet]" value="<?php echo $wallet_value; ?>">
<?php
}

function hederapay_previewnet_wallet_field_callback()
{
    $options = get_option('hederapay_settings');
    $wallet_value = isset($options['previewnet_wallet']) ? esc_textarea($options['previewnet_wallet']) : '0.0.0000000';
?>
    <input type="text" name="hederapay_settings[previewnet_wallet]" value="<?php echo $wallet_value; ?>">
<?php
}

function hederapay_mainnet_wallet_field_callback()
{
    $options = get_option('hederapay_settings');
    $wallet_value = isset($options['mainnet_wallet']) ? esc_textarea($options['mainnet_wallet']) : '0.0.0000000';
?>
    <input type="text" name="hederapay_settings[mainnet_wallet]" value="<?php echo $wallet_value; ?>">
<?php
}

function hederapay_button_text_field_callback()
{
    $options = get_option('hederapay_settings');
    $button_text = isset($options['button_text']) ? esc_textarea($options['button_text']) : 'Pay';
?>
    <input type="text" name="hederapay_settings[button_text]" value="<?php echo $button_text; ?>">
<?php
}

function hederapay_amount_field_callback()
{
    $options = get_option('hederapay_settings');
    $amount = isset($options['amount']) ? esc_textarea($options['amount']) : '1';
?>
    <input type="number" name="hederapay_settings[number]" value="<?php echo $amount; ?>">
<?php
}

function hederapay_currency_field_callback()
{
    $options = get_option('hederapay_settings');
    $currency = $options['currency'] ?? 'usd';
?>
    <select name="hederapay_settings[currency]" id="hederapay-currency">
        <option value="usd" <?php selected($currency, 'usd'); ?>>USD</option>
        <option value="eur" <?php selected($currency, 'eur'); ?>>EUR</option>
        <option value="jpy" <?php selected($currency, 'jpy'); ?>>JPY</option>
        <option value="gbp" <?php selected($currency, 'gbp'); ?>>GBP</option>
        <option value="aud" <?php selected($currency, 'aud'); ?>>AUD</option>
        <option value="cad" <?php selected($currency, 'cad'); ?>>CAD</option>
        <option value="cny" <?php selected($currency, 'cny'); ?>>CNY</option>
        <option value="inr" <?php selected($currency, 'inr'); ?>>INR</option>
        <option value="brl" <?php selected($currency, 'brl'); ?>>BRL</option>
        <option value="zar" <?php selected($currency, 'zar'); ?>>ZAR</option>
        <option value="chf" <?php selected($currency, 'chf'); ?>>CHF</option>
        <option value="rub" <?php selected($currency, 'rub'); ?>>RUB</option>
        <option value="nzd" <?php selected($currency, 'nzd'); ?>>NZD</option>
        <option value="mxn" <?php selected($currency, 'mxn'); ?>>MXN</option>
        <option value="sgd" <?php selected($currency, 'sgd'); ?>>SGD</option>
    </select>
<?php
}
