# HederaPay

HederaPay allows users to integrate HBAR transaction buttons into their WordPress website. This plugin is suited for all regular WordPress sites. For webshops that use WooCommerce we have another plugin: [HederaPay for WooCommerce](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay-for-woocommerce).

## Pitch video

Available soon

## Installation

Download the [hederapay.zip](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay.zip) file in the root of this repository. Go to your WordPress dashboard > Plugins. Click 'Add New Plugin'. Click 'Upload Plugin' and upload the zipfile. This will upload and install the plugin, next click 'Activate' to activate the plugin.

> We are currently in the process of getting the plugin listed in the build-in WordPress Plugin Store.

## Configuration _(optional)_

The HederaPay settings page allows you to change the site metadata that is shown in the transaction modal.

![Admin Settings](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay/assets/admin-settings.png)

## Shortcodes

### [hederapay-transaction-button]

Adds a transaction button for sending transactions on Hedera. The button has small badge in the top right corner when the active network is `testnet` or `previewnet` to warn the user/developer that the code isn't running on the mainnet. Use custom CSS if you want to hide it.

#### Attributes

| Attribute          | Description                                   | Default value |
| :----------------- | :-------------------------------------------- | :------------ |
| title              | Button text                                   | Pay           |
| amount             | Amount to be send in _currency_ (see details) | null          |
| currency           | Currency the _amount_ is in (see details)     | USD           |
| memo               | Message to send along with the transaction    | null          |
| testnet_account    | Receiver Account ID on the testnet            | null          |
| previewnet_account | Receiver Account ID on the previewnet         | null          |
| mainnet_account    | Receiver Account ID on the mainnet            | null          |

#### Details

##### Currency

The following currencies are supported: `USD` `EUR` `JPY` `GBP` `AUD` `CAD` `CNY` `INR` `BRL` `ZAR` `CHF` `RUB` `NZD` `MXN` `SGD`
The currencies aren't case-sensitive.

##### Amount

Amounts are converted to `HBAR` using the [CoinGecko API](https://docs.coingecko.com/v3.0.1/reference/simple-price). Subsequently, the `HBAR` amount is converted to `tinybar` and rounded to an integer value.

> 1 HBAR = 100.000.000 tinybar.

When no amount is provided an `<input>` field appears in which the user can enter an amount.

##### Accounts

Always provide a `mainnet_account` **or** a `previewnet_account` **or** a `testnet_account`. The shortcode won't work if you provide multiple IDs. This is a safety measure to avoid mistakes. Perhaps unsurprisingly, the shortcode also won't work when you don't provide any receiver account ID at all.

> Account IDs on Hedera have to start with **_0.0._**

**Examples:**
`[hederapay_transaction_button amount="5" currency="eur" title="☕︎ Buy us a coffee" testnet_account="0.0.4505361" memo="Coffee donation"]`

![Transaction button](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay/assets/transaction-button.png)

more images? transact modal ? dragonglass transaction?

`[hederapay_transaction_button currency="usd" title="Donate" testnet_account="0.0.4505361"]`

![Transaction button with input field](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay/assets/transaction-button-with-input.png)

#### Gutenberg block

For websites with Gutenberg and [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) or ACF Pro we also have a _HederaPay Transaction Button_-Gutenberg block. The functionality and output are the same as the shortcode.

**Example:**
![Gutenberg block](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay/assets/gutenberg-block.png)

### [hederapay-connect-button] _(optional)_

Adds a connect/disconnect button.

Useful when you want to allow the user to connect their wallet without sending a transaction. After connecting the button acts as a disconnect button. The button has small badge in the top right corner when the active network is `testnet` or `previewnet` to warn the user/developer that the code isn't running on the mainnet. Use custom CSS if you want to hide it.

#### Attributes _(all attributes are optional)_

| Attribute       | Description                                     | Default value     |
| :-------------- | :---------------------------------------------- | :---------------- |
| network         | Type of network: testnet, previewnet or mainnet | testnet           |
| connect_text    | Button text before wallet is paired             | Connect wallet    |
| disconnect_text | Button text after connection is established     | Disconnect wallet |

**Example:**
`[hederapay-connect-button network="testnet" connect_text="Connect" disconnect_text="Disconnect"]`

![Connect / disconnect button](https://github.com/louweal/hellofuturehackathon/blob/master/hederapay/assets/connect-button.png)

### [hederapay-paired-wallet] _(optional)_

Displays the Account ID of the paired wallet. It is hidden when no wallet is connected. Clicking the element opens the account in network inspector [Dragonglass](https://app.dragonglass.me/) in a new browser tab.

## Other Plugins by HashPress Pioneers

-   [HederaPay for WooCommerce](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay-for-woocommerce)
