# Realviews

Integrate Hedera Smart Contracts into your WordPress website to get verifiable reviews.

## Dependencies / Required Plugins

-   [HederaPay](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay#readme) - HederaPay adds all functionality needed to connect to the Hedera network and make payments on Hedera.
-   (OPTIONAL) [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/)
    -   If ACF PRO is installed, the plugin adds easy to use Gutenberg blocks (these blocks have the same functionality as the shortcodes).
-   (OPTIONAL) [WooCommerce](https://woocommerce.com/)
    -   If WooCommerce is installed the HederaPay option is added to the Payment options in the WooCommerce check-out and a Reviews section is added to the product pages.

## Installation

Install [HederaPay](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay#readme) if not yet installed. Then download the [realviews.zip](https://github.com/louweal/hellofuturehackathon/blob/master/realviews.zip) file from the root of this repository. Go to the `WP dashboard` **>** `Plugins`. Click `Add New Plugin` **>** `Upload Plugin` and upload the downloaded zipfile. This will upload and install the plugin, next click `Activate` to activate the plugin.

> Plugin will soon be listed in the build-in WordPress Plugin Store for easier installation.

## Configuration for WooCommerce shops _(optional)_

On the Realviews admin page you can select the number of reviews you want to show on a product page and set the button text for showing more reviews (if available). This button triggers a modal showing all reviews. Set the number of reviews to `-1` to show all reviews in-page.

> Set the number of reviews to `0` to hide the whole review section.

![Realviews Admin Settings](https://github.com/louweal/hellofuturehackathon/blob/master/realviews/assets/admin.png)

## Shortcodes

### [hederapay_transaction_button]

Realviews adds attribute `store` to the Hederapay transaction button that allows you to save the transaction IDs to the page metadata such that they can be used to enable reviewing on that page.

| Attribute          | Description                                            | Default value |
| :----------------- | :----------------------------------------------------- | :------------ |
| title              | Button text                                            | Pay           |
| amount             | Amount to be send in _currency_ (see details)          | null          |
| currency           | Currency the _amount_ is in (see details)              | USD           |
| memo               | Message to send along with the transaction             | null          |
| testnet_account    | Receiver Account ID on the testnet                     | null          |
| previewnet_account | Receiver Account ID on the previewnet                  | null          |
| mainnet_account    | Receiver Account ID on the mainnet                     | null          |
| **store**          | **Store transaction id in page metadata (true/false)** | **false**     |

**Example**  
`[hederapay_transaction_button amount="5" currency="eur" title="Buy ebook" testnet_account="0.0.4505361" memo="Ebook purchase" store="true"]`

#### Gutenberg

Realviews also adds a `Store transactions` toggle to the Gutenberg block.

![Store transactions](https://github.com/louweal/hellofuturehackathon/blob/master/realviews/assets/store-transactions.png)

### [realviews_latest_reviews]

Retrieves all reviews for the current product/page from the Hedera Mirror [REST API](https://docs.hedera.com/hedera/sdks-and-apis/rest-api).

> The reviews have small badge in the top right corner when they are from the `testnet` or `previewnet` network.

#### Attributes

| Attribute   | Description                                       | Default value |
| :---------- | :------------------------------------------------ | :------------ |
| max_reviews | Maximum number of reviews to show on product page | 2             |
| button_text | Button text for showing all reviews               | All reviews   |

#### Gutenberg block

On websites with Gutenberg (WordPress version >= 5.0) and [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/) you can use the _Latest Reviews (Realviews)_-Gutenberg block instead of the shortcode. The functionality and output are the same as the shortcode.

![Gutenberg block](https://github.com/louweal/hellofuturehackathon/blob/master/realviews/assets/gutenberg-block.png)

### [realviews_num_reviews]

Displays the total number of reviews for the current product/page.

## Other Plugins by HashPress Pioneers

-   [HederaPay](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay)
