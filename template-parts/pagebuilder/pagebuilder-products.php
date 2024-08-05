<?php

/**
 * Template:			pagebuilder-products.php
 * Description:			Products Pagebuilder Layout
 */
$default = get_sub_field('products_default');
if ($default || array_key_exists('default', $args)) {
    $editor = get_field('products_editor', 'options');
    // $button = get_field('products_button', 'options');
    $items = get_field('products_relationship', 'options');
} else {
    $editor = get_sub_field('products_editor');
    // $button = get_sub_field('products_button');
    $items = get_sub_field('products_relationship');
}

?>

<section class="section section--products" xxxdata-aos="fade-up-10" id="products">
    <div class="container">
        <div class="flex justify-center pb-8">
            <?php if ($editor) { ?>
                <div class="section__title">
                    <?php echo $editor; ?>
                </div>
            <?php } ?>


        </div>
    </div>

    <div class="lg:container">

        <?php if ($items) { ?>
            <div class="slider slider--products swiper js-slider" data-slider="products">
                <div class="slider__wrapper swiper-wrapper">
                    <?php foreach ($items as $post) {
                        global $post; ?>
                        <div class="slider__slide swiper-slide">
                            <?php get_template_part('./template-parts/card/card', 'product'); ?>
                        </div>
                    <?php }
                    wp_reset_postdata(); ?>
                </div>
            </div>
        <?php } ?>


    </div>
</section>