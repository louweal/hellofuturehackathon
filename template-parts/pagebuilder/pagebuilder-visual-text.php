<?php

/**
 * Template:			pagebuilder-visual-text.php
 * Description:			Visual Text Pagebuilder Layout
 */

$text = get_sub_field('visual-text_editor');
$link = get_sub_field('visual-text_link');
$visual = get_sub_field('visual-text_visual');

?>

<section class="section" data-aos="fade-up-10">
    <div class="container">

        <div class="row h-full">
            <div class="box lg:box-6 order-2 lg:order-1">
                <div class="border border-light rounded-md p-8 lg:p-12 h-full flex flex-col justify-center">
                    <?php if ($text) { ?>
                        <div class="editor pb-4">
                            <?php echo $text; ?>
                        </div>
                    <?php }; //if 
                    ?>

                    <?php if ($link) { ?>
                        <?php the_link($link, 'btn'); ?>
                    <?php } ?>
                </div>
            </div>
            <div class="box lg:box-6 order-1 lg:order-2">

                <?php if ($visual) { ?>
                    <picture>
                        <source media="(min-width: 30em)" srcset="<?php echo $visual['sizes']['full-hd']; ?>">
                        <img class="h-full w-full object-cover rounded" src="<?php echo $visual['sizes']['medium_large']; ?>" alt="<?php echo $visual['alt']; ?>">
                    </picture>
                <?php }; //if 
                ?>
            </div>
        </div>
    </div>
</section>