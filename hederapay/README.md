# HederaPay

HederaPay allows users to integrate HBAR transaction buttons into their WordPress website. This plugin is suited for all regular WordPress sites. For webshops that use WooCommerce we have another plugin: [HederaPay for WooCommerce](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay-for-woocommerce).

## Pitch video

Available soon

## Installation

Download the [hederapay.zip](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay.zip) file in the root of this repository. Go to your WordPress dashboard > Plugins. Click 'Add New Plugin'. Click 'Upload Plugin' and upload the zipfile. This will upload and install the plugin, next click 'Activate' to activate the plugin.

> We are currently in the process of getting the plugin listed in the build-in WordPress Plugin Store.

## Configuration (optional)

The HederaPay settings page allows you to change the site metadata that is shown in the transaction modal.

![Admin Settings](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay/assets/admin-settings.png)

## Shortcodes

The plugin adds the following shortcodes:

### [hederapay-transaction-button]

#### Attributes

network

connect_text (optional)

disconnect_text (optional)

#### Attributes

title

amount

currency usd, eur, jpy, gbp, aud, cad, cny, inr, brl, zar, chf, rub, nzd, mxn, sgd

account

memo?

Example...

### [hederapay-paired-wallet] _(optional)_

### [hederapay-connect-button] _(optional)_

## Gutenberg block

If the [ACF plugin](https://wordpress.org/plugins/advanced-custom-fields/) is active on your website, HederaPay adds the _HederaPay Transaction Button_ block to your Gutenberg block overview. The functionality is the same as the `[hederapay-transaction-button]` shortcode, it is just easier to use.

img-here!

## Questions?

Contact me at **email**
