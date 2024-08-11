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
                <h3>Shop</h3>
                <div class="editor editor--footer">
                    <ul>
                        <li><a href="/product/polo">Polo</a> </li>
                        <li><a href="/product/hoodie">Hoodie</a> </li>
                        <li><a href="/product/beanie">Beanie</a> </li>
                    </ul>
                </div>
            </div>
            <div class="lg:col-span-3">
                <h3>Socials</h3>
                <div class="editor editor--footer">
                    <ul>
                        <li><a href="#">Instagram</a> </li>
                        <li><a href="#">Twitter</a> </li>
                        <li><a href="#">Facebook</a> </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
    <div class="footer__bar">
        <div class="container">
            <div class="flex justify-between">
                <p>HashPress Pioneers © <?php echo date("Y"); ?> All rights reverved</p>
                <div class="hidden lg:block"><a href="https://hellofuturehackathon.dev/">Hello Future Hackathon</a></div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>