<?php

/**
 * Template:			hero-woocommerce.php
 * Description:			Woocommerce hero template
 */

return;
$shop_id = wc_get_page_id('shop');
?>

<header class="hero py-10">
    <div class="container text-center">
        <div class="editor">
            <?php if (is_product_category()) { ?>
                <h1><?php echo get_the_title($shop_id); ?></h1>
            <?php } else if (is_singular('product')) { ?>
                <a class="" href="<?php echo get_home_url(); ?>">Home</a> / CAT / title
            <?php } else { ?>
                <h1><?php echo woocommerce_page_title(); ?></h1>
            <?php } ?>
        </div>
    </div>
</header>