# HederaPay

HederaPay is a WordPress Plugin for sending HBAR transactions on the Hedera Network. It is compatible with all WordPress websites and themes, including sites without the Advanced Custom Fields (ACF) plugin. On sites **with** ACF however, it adds a Gutenberg block named _HederaPay Transaction Button_ to make the plugin even easier to use.

## Pitch video

Available soon

## Installation

Download the hederapay.zip file in the root of this repository. Go to your WordPress dashboard > Plugins. Click 'Add New Plugin'. Click 'Upload Plugin' and upload the zipfile. This will upload and install the plugin, next click 'Activate' to activate the plugin.

> We are currently in the process of getting the plugin listed in the build-in WordPress Plugin Store.

## Configuration (optional)

After activation, _HederaPay_ appears in the WordPress admin sidebar. Head over to this setting page is you wish to change the app metadata that is shown in the transaction modal. You can set all metadata including the website name, the website description, the website URL and the icon.

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
