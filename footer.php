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
                <h3>About</h3>

                <p class="lg:w-4/5">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Quia quod laborum possimus nisi nam omnis perferendis hic ad sapiente delectus cum aut consectetur harum quam ex modi soluta, expedita tempora.</p>

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
                <p>Anneloes Louwe © <?php echo date("Y"); ?> All rights reverved</p>
                <div class="hidden lg:block">hedera</div>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>