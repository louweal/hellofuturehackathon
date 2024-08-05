<?php
/**
 * Template:			pagebuilder-example.php
 * Description:			Example Pagebuilder Layout
 */

$background = get_sub_field( 'example_background' );
$padding = get_sub_field( 'example_padding' );
$text = get_sub_field( 'example_editor' );
$buttons = get_sub_field( 'example_buttons' );
?>
 
 <section class="section<?php if ( $background ) { echo ' section--bg'; } if ( !$padding ) { echo ' section--no-pb'; } ?>">
    <div class="container relative gap-8 flex flex-col flex-wrap lg:flex-row justify-center">               
        <div class="section__body mx-auto w-full lg:w-8/12">          
            <?php if ( $text ) { ?>
                <div class="editor">
                    <?php echo $text; ?>
                </div>  
            <?php } ?>                                          
            <?php if ( $buttons ) { ?>
                <div class="flex flex-wrap gap-4">
                    <?php foreach( $buttons as $button ) { ?>
                        <?php the_link( $button[ 'link' ], 'btn btn--' . $button[ 'type' ] ); ?>
                    <?php } ?>
                </div>  
            <?php } ?>
        </div>
    </div>  
 </section>
 