<?php

/**	
 * Template:			woocommerce.php
 * Description:			
 */

get_header();

?>

<main id="site-main" class="main">

    <?php if (is_singular('product')) { ?>

        <section class="section woocommerce woocommerce--product">
            <div class="container">
                <div class="woocommerce__content">
                    <?php woocommerce_content(); ?>
                </div>
            </div>
        </section>

    <?php } else { ?>
        <section class="section section--woocommerce woocommerce">
            <div class="container">
                <div class="woocommerce__content">
                    <?php woocommerce_content(); ?>
                </div>
            </div>
        </section>
    <?php } ?>

</main>

<?php get_footer(); ?>