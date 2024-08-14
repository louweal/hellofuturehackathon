<?php

/**	
 * Template:			footer.php
 * Description:			The template for displaying the footer
 */

?>

<footer id="site-footer" class="footer">
    <div class="container">
        <div class="flex flex-col lg:grid lg:grid-cols-12 gap-5 pb-8">
            <div class="col-span-7">
                <?php if (is_active_sidebar('footer-widget-1')) { ?>
                    <div class="editor editor--footer">
                        <?php dynamic_sidebar('footer-widget-1');
                        ?>
                    </div>
                <?php } ?>

            </div>
            <div class="lg:col-span-2">
                <?php if (is_active_sidebar('footer-widget-2')) { ?>
                    <div class="editor editor--footer">
                        <?php dynamic_sidebar('footer-widget-2');
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="lg:col-span-3">
                <?php if (is_active_sidebar('footer-widget-3')) { ?>
                    <div class="editor editor--footer">
                        <?php dynamic_sidebar('footer-widget-3');
                        ?>
                    </div>
                <?php } ?>
            </div>

        </div>
    </div>
    <div class="footer__bar">
        <div class="container">
            <div class="flex flex-col sm:flex-row gap-2 justify-between">
                <p>HashPress Pioneers © <?php echo date("Y"); ?> All rights reverved</p>
                <a href="https://hellofuturehackathon.dev/">Hello Future Hackathon</a>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>