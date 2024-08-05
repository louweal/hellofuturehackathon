<?php

/**	
 * Template:			woocommerce.php
 * Description:			
 */

get_header();

?>

<main id="site-main" class="main">
    <?php get_hero('woocommerce'); ?>

    <?php if (is_singular('product')) { ?>

        <section class="section woocommerce woocommerce--product">
            <div class="container">
                <div class="woocommerce__content">
                    <?php woocommerce_content(); ?>
                </div>
            </div>
        </section>

        <?php
        get_template_part('./template-parts/pagebuilder/pagebuilder', 'products', array("default" => true));
        ?>

    <?php } else if (is_product_category() || is_shop()) { ?>

        <section class="section section--woocommerce woocommerce woocommerce--shop">
            <div class="container">
                <div class="row">
                    <div class="box lg:box-3 flex flex-col gap-[1.875rem] lg:pr-8">
                        <?php if (is_active_sidebar('sidebar-products')) { ?>
                            <div class="woocommerce__sidebar js-woo-filter">
                                <?php dynamic_sidebar('sidebar-products'); ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box lg:box-9">
                        <div class="woocommerce__content">
                            <?php if (is_product_category()) { ?>
                                <div class="editor mb-[3.125rem] lg:max-w-[90%]">

                                <?php } ?>


                                <?php woocommerce_content(); ?>

                                </div>
                        </div>
                    </div>

                </div>
        </section>

    <?php } else { ?>
        <section class="section section--woocommerce woocommerce" data-aos="fade-up-10">
            <div class="container">
                <div class="woocommerce__content">
                    <?php woocommerce_content(); ?>
                </div>
            </div>
        </section>
    <?php } ?>

</main>

<?php get_footer(); ?>