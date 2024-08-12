<?php

/**	
 * Template:			header.php
 * Description:			The template for displaying the header
 */

if (function_exists('opcache_reset')) {
    opcache_reset();
}
?>

<!DOCTYPE html>
<html lang="<?php bloginfo('language'); ?>" class="no-js">

<head>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php
    wp_body_open(); ?>

    <header id="site-header" class="header">
        <div class="flex flex-row justify-between items-center w-full">
            <div class="flex gap-5 items-center">

                <div class="header__logo">
                    <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>" rel="home">
                        <picture>
                            <img alt="<?php bloginfo('name'); ?>" src="<?php the_logo(); ?>">
                        </picture>
                    </a>


                </div>
                <a href="/shop">Shop</a>
                <a href="/webinar">Webinar</a>
            </div>

            <?php if (is_active_sidebar('header-widget')) { ?>
                <div class="flex gap-5 items-center">
                    <?php dynamic_sidebar('header-widget'); ?>
                </div>
            <?php } ?>
        </div>
    </header>