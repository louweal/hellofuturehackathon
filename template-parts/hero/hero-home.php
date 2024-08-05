<?php

/**
 * Template:			hero-home.php
 * Description:			Home hero template
 */
$title = get_field('hero_title');
$link = get_field('hero_link');
$visuals = get_field('hero_visuals');
?>

<header class="hero-home">
    <div class="hero-home__bg"></div>
    <div class="container hero-home__content order-2 sm:order-1">
        <div class="row">
            <div class="box sm:box-10 lg:box-8 z-20">
                <h3 class="hero-home__label">Expedita tempora</h3>
                <h1 class="hero-home__title"><?php echo $title; ?></h1>
                <?php if ($link) { ?>
                    <?php the_link($link, 'btn'); ?>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="hero-home__slider order-1 sm:order-2">
        <div class="slider slider--home swiper js-slider" data-slider="home">
            <div class="slider__wrapper swiper-wrapper">
                <?php foreach ($visuals as $image) { ?>
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