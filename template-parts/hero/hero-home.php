<?php

/**
 * Template:			hero-home.php
 * Description:			Home hero template
 */
$title = get_field('hero_title');
$link = get_field('hero_link');
$image_1 = get_field('hero_image_1');
$image_2 = get_field('hero_image_2');
$image_3 = get_field('hero_image_3');
$image_4 = get_field('hero_image_4');
$image_5 = get_field('hero_image_5');
$image_6 = get_field('hero_image_6');
$images = [$image_1, $image_2, $image_3, $image_4, $image_5, $image_6];

if ($title) {

?>

    <header class="hero-home">
        <div class="hero-home__bg"></div>
        <div class="container hero-home__content order-2 sm:order-1">
            <div class="row">
                <div class="box sm:box-8 lg:box-6">
                    <?php if ($title) { ?>
                        <div class="editor">
                            <?php echo $title; ?>
                        </div> <?php }; //if 
                                ?>
                    <?php if ($link) { ?>
                        <?php the_link($link, 'btn'); ?>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="hero-home__slider order-1 sm:order-2">
            <div class="slider slider--home swiper js-slider" data-slider="home">
                <div class="slider__wrapper swiper-wrapper">
                    <?php foreach ($images as $image) { ?>
                        <div class="slider__slide swiper-slide">
                            <picture>
                                <img src="<?php echo $image['sizes']['medium_large']; ?>" alt="<?php echo $image['alt']; ?>">
                            </picture>
                        </div>
                    <?php }; //foreach 
                    ?>
                </div>
            </div>
        </div>
    </header>

<? } ?>