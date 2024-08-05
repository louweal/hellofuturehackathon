<?php

/**
 * Template:			card.php
 * Description:			The card template
 *
 */

?>

<article class="card">
    <a class="card__inner" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <div class="card__header">
            <?php if (has_post_thumbnail()) { ?>
                <picture>
                    <source media="(min-width: 30em)" srcset="<?php the_post_thumbnail_url('medium_large'); ?>">
                    <img loading="lazy" class="card__featured" src="<?php the_post_thumbnail_url('medium_large'); ?>" alt="<?php the_title(); ?>">
                </picture>
            <?php } ?>
        </div>
        <div class="card__body">
            <div class="editor editor--card">
                <h3><?php the_title(); ?></h3>
                <?php the_excerpt(); ?>
            </div>
            <span class="card__link">Read more</span>
        </div>
    </a>
</article>