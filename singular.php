<?php

/**
 * Template:			singular.php
 * Description:			The template for displaying single posts and pages.
 *
 */

get_header();

?>

<main id="site-main" class="main">

    <?php if (have_posts()) {
        while (have_posts()) {
            the_post();

    ?>
            <?php get_hero('home'); ?>


            <?php if (!empty(get_the_content())) { ?>
                <section class="section">
                    <div class="container">
                        <?php if (is_singular('post')) { ?>
                            <h1><?php the_title(); ?></h1>
                        <?php } ?>
                        <div class="editor">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </section>
            <?php } ?>

    <?php }
    } ?>

</main>

<?php get_footer(); ?>