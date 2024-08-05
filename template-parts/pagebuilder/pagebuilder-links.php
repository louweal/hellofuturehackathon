<?php

/**
 * Template:			pagebuilder-links.php
 * Description:			Links Pagebuilder Layout
 */
return;
$items = get_sub_field('links_repeater');
?>

<section class="section" data-aos="fade-up-10">
    <div class="container">

        <div class="row">
            <?php if (have_rows('links_repeater')) { ?>
                <?php while (have_rows('links_repeater')) {
                    the_row();
                    $image = get_sub_field('image');
                    $button = get_sub_field('button');
                ?>
                    <div class="box md:box-6">
                        <a class="card card--link" href="<?php echo $button['url']; ?>">
                            <div class="card__header">
                                <?php if ($image) { ?>
                                    <picture>
                                        <source media="(min-width: 30em)" srcset="<?php echo $image['sizes']['full-hd']; ?>">
                                        <img class="" src="<?php echo $image['sizes']['medium_large']; ?>" alt="<?php echo $image['alt']; ?>">
                                    </picture>
                                <?php } ?>
                            </div>
                            <div class="card__body">
                                <?php if ($button) { ?>
                                    <?php the_fake_link($button, 'btn'); ?>
                                <?php } ?>
                            </div>
                        </a>
                    </div>
                <?php }; //while 
                ?>
            <?php }; //if 
            ?>

        </div>
    </div>


</section>