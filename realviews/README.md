# Realviews

## Dependencies / Required Plugins

-   [HederaPay](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay#readme)

## Installation

Download the [realviews.zip](https://github.com/louweal/hellofuturehackathon/blob/master/realviews.zip) file from the root of this repository. Go to the `WP dashboard` **>** `Plugins`. Click `Add New Plugin` **>** `Upload Plugin` and upload the downloaded zipfile. This will upload and install the plugin, next click `Activate` to activate the plugin.

> We are currently in the process of getting the plugin listed in the build-in WordPress Plugin Store.

## Configuration for WooCommerce shops _(optional)_

On the Realviews admin page you can select the number of reviews you want to show on a product page and set the button text for showing more reviews (if available).

![Realviews Admin Settings](https://github.com/louweal/hellofuturehackathon/blob/master/realviews/assets/admin.png)

## Shortcodes

### [realviews_latest_reviews]

Retrieves all reviews for the current product/page from the Hedera Mirror [REST API]().

> The reviews have small badge in the top right corner when they are from the `testnet` or `previewnet` network.

#### Attributes

| Attribute   | Description                                       | Default value |
| :---------- | :------------------------------------------------ | :------------ |
| max_reviews | Maximum number of reviews to show on product page | 2             |
| button_text | Button text for showing all reviews               | All reviews   |

#### Gutenberg block

On websites with Gutenberg and [Advanced Custom Fields PRO](https://www.advancedcustomfields.com/pro/) you can also the _Latest Reviews (Realviews)_-Gutenberg block instead of the `[realviews_latest_reviews]`. The functionality and output are the same as the shortcode.

![Gutenberg block](https://github.com/louweal/hellofuturehackathon/blob/master/realviews/assets/gutenberg-block.png)

### [realviews_num_reviews]

Displays the total number of reviews for the current product/page.

## Other Plugins by HashPress Pioneers

-   [HederaPay](https://github.com/louweal/hellofuturehackathon/tree/master/hederapay)
