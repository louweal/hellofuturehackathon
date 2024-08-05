<?php
/**	
 * Template:			index.php
 * Description:			
 */

get_header();

global $wp_query;

$background = get_field( 'page_background', get_option( 'page_for_posts' ) ); 
?>

<main id="site-main" class="main">

    <?php get_hero(); ?>

    <section class="section<?php if ( $background ) { echo ' section--bg'; } ?>">
        <?php if ( have_posts() ) { ?>
            <div class="container flex flex-col gap-8">
                <div class="row justify-center">
                    <?php while ( have_posts() ) { the_post(); ?>
                        <div class="box xs:box-6 md:box-4 lg:box-3">
                            <?php get_template_part( './template-parts/card/card', '' ); ?>                        
                        </div>                                                                                                                         
                    <?php } ?>                  
                </div>
                <?php if ( $wp_query->max_num_pages > 1 ) { ?>
                    <div class="section__pagination flex justify-center">
                        <?php the_posts_pagination(); ?>
                    </div>                
                <?php } ?>
            </div>
        <?php } ?>
    </section>

</main>

<?php get_footer(); ?>