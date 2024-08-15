<?php
add_shortcode('realviews_latest_reviews', 'realviews_latest_reviews_function', 5);
function realviews_latest_reviews_function($atts)
{
    global $product;

    // Define the default attributes
    $atts = shortcode_atts(
        array(
            'max_reviews' => 6,
            'button_text' => 'All reviews'
        ),
        $atts,
        'realviews_latest_reviews'
    );

    $max_reviews = esc_html($atts['max_reviews']);
    $button_text = esc_html($atts['button_text']);

    $button_text = get_field("button_text") ?: 'All reviews';
    $title = getTitle();

    if (is_product()) {
        $post_id = $product->get_id();
    } else {
        global $post;
        $post_id = $post->ID;
    }

    $transaction_ids = get_post_meta($post_id, '_transaction_ids', true);

    $account_ids = [];
    if ($transaction_ids) {
        for ($i = count($transaction_ids) - 1; $i >= 0; $i--) {
            $transaction_id = $transaction_ids[$i];
            echo $transaction_id;

            $exploded_transaction_id = explode("-", $transaction_id);
            $account_id = $exploded_transaction_id[0];
            $timestamp = $exploded_transaction_id[1];
            $formatted_timestamp = str_replace(".", "-", $timestamp);

            if (!in_array($account_id, $account_ids)) {
                $account_ids[] = $account_id; // add account id to list
            }
        }
    }

    $jsonData = json_encode($account_ids);     // Encode to JSON
    $encodedAccountIds = base64_encode($jsonData);     // Encode the JSON string using Base64

    ob_start();

?>
    <section class="realviews">
        <?php $num_reviews = 5;
        ?>

        <h2>Reviews (<?php echo $num_reviews; ?>)</h2>
        <div class="realviews-wrapper">

            <?php if ($num_reviews > 0) { ?>
                <?php for ($i = 1; $i <= $max_reviews; $i++) {
                ?>
                    <div class="realviews-review">
                        <div class="realviews-review__header">
                            <div class="realviews-review__icon">
                                <span>H</span>
                            </div>
                            <div class="realviews-review__user">
                                <div class="realviews-review__username">
                                    <span rel="author">Henk</span>
                                </div>
                                <!-- <div class="realviews-review__count">
                                <span rel="author">623 realviews</span>
                            </div> -->
                            </div>
                        </div>
                        <div class="realviews-review__subheader">
                            <div class="realviews-review__stars">
                                <?php
                                $rating = 4;

                                for ($j = 1; $j <= 5; $j++) {
                                    if ($rating >= $j) {
                                ?>
                                        <svg width="18" height="15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#a)">
                                                <path d="M9.903.527a1.006 1.006 0 0 0-.9-.527 1.01 1.01 0 0 0-.9.527l-2.01 3.876-4.487.621a.983.983 0 0 0-.803.636.902.902 0 0 0 .247.958l3.256 3.02-.769 4.27a.914.914 0 0 0 .404.916c.309.208.718.235 1.056.068l4.01-2.007 4.008 2.007c.338.167.747.143 1.057-.068a.917.917 0 0 0 .403-.917l-.772-4.268 3.256-3.02a.896.896 0 0 0 .247-.959.987.987 0 0 0-.803-.636l-4.49-.62L9.902.526Z" fill="#000" />
                                            </g>
                                            <defs>
                                                <clipPath id="a">
                                                    <path fill="#fff" d="M0 0h18v15H0z" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    <?php

                                    } else {
                                    ?>
                                        <svg width="18" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <g clip-path="url(#a)">
                                                <path d="M8.997 0c.287 0 .55.163.675.422l2.144 4.416 4.787.706a.753.753 0 0 1 .419 1.275l-3.472 3.443.819 4.863a.751.751 0 0 1-1.094.787l-4.281-2.287-4.275 2.284a.746.746 0 0 1-.79-.053.757.757 0 0 1-.304-.734l.819-4.863L.972 6.82a.748.748 0 0 1-.185-.766.755.755 0 0 1 .604-.51l4.787-.705L8.322.421A.75.75 0 0 1 8.997 0Zm0 2.469L7.357 5.85a.757.757 0 0 1-.566.416l-3.697.543 2.684 2.66a.752.752 0 0 1 .213.656l-.635 3.74 3.288-1.756a.744.744 0 0 1 .706 0l3.287 1.757-.63-3.738a.743.743 0 0 1 .212-.656l2.684-2.66-3.697-.546a.755.755 0 0 1-.565-.416L8.997 2.469Z" fill="#000" />
                                            </g>
                                            <defs>
                                                <clipPath id="a">
                                                    <path fill="#fff" d="M0 0h18v16H0z" />
                                                </clipPath>
                                            </defs>
                                        </svg>
                                <?php
                                    }
                                }

                                ?>

                            </div>
                            <div class="realviews-review__date">
                                <time>juni 2024</time>
                            </div>
                            <div class="realviews-review__interval">
                                <p><a href="#">Review written after 3 months</a></p>
                            </div>
                        </div>
                        <div class="realviews-review__body">
                            <p>
                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci non aspernatur nulla enim cupiditate repudiandae vero excepturi aliquam est voluptatem aliquid inventore sint, commodi ullam amet, eos, tempora nemo ratione!
                            </p>
                        </div>
                    </div>
                <?php } //foreach 
                ?>
            <?php } else { ?>
                <p>No reviews yet.</p>
            <?php } ?>
        </div>


        <div class="realviews-actions">

            <?php if ($num_reviews > $max_reviews) { ?>
                <div class="btn show-realviews-modal"><?php echo $button_text; ?></div>

                <div class="realviews-modal">
                    <div class="realviews-modal__bg"></div>

                    <div class="realviews-modal__inner">
                        <div class="realviews-modal__header">
                            <button class="realviews-modal__close">
                                <svg width="15" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.383 5.883a1.252 1.252 0 0 0-1.77-1.77L7.5 8.23 3.383 4.117a1.252 1.252 0 0 0-1.77 1.77L5.73 10l-4.113 4.117a1.252 1.252 0 0 0 1.77 1.77L7.5 11.77l4.117 4.113a1.252 1.252 0 0 0 1.77-1.77L9.27 10l4.113-4.117Z" fill="#000" />
                                </svg>
                            </button>

                            <h2>Reviews (<?php echo $num_reviews; ?>)</h2>
                        </div>

                        <div class="realviews-modal__body">
                            <div class="realviews-wrapper">
                                <?php for ($i = 1; $i <= $num_reviews; $i++) {
                                ?>
                                    <div class="realviews-review">
                                        <div class="realviews-review__header">
                                            <div class="realviews-review__icon">
                                                <span>H</span>
                                            </div>
                                            <div class="realviews-review__user">
                                                <div class="realviews-review__username">
                                                    <span rel="author">Henk</span>
                                                </div>
                                                <div class="realviews-review__count">
                                                    <span rel="author">623 realviews</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="realviews-review__subheader">
                                            <div class="realviews-review__stars">
                                                <?php
                                                $rating = 4;

                                                for ($j = 1; $j <= 5; $j++) {
                                                    if ($rating >= $j) {
                                                ?>
                                                        <svg width="18" height="15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#a)">
                                                                <path d="M9.903.527a1.006 1.006 0 0 0-.9-.527 1.01 1.01 0 0 0-.9.527l-2.01 3.876-4.487.621a.983.983 0 0 0-.803.636.902.902 0 0 0 .247.958l3.256 3.02-.769 4.27a.914.914 0 0 0 .404.916c.309.208.718.235 1.056.068l4.01-2.007 4.008 2.007c.338.167.747.143 1.057-.068a.917.917 0 0 0 .403-.917l-.772-4.268 3.256-3.02a.896.896 0 0 0 .247-.959.987.987 0 0 0-.803-.636l-4.49-.62L9.902.526Z" fill="#000" />
                                                            </g>
                                                            <defs>
                                                                <clipPath id="a">
                                                                    <path fill="#fff" d="M0 0h18v15H0z" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                    <?php

                                                    } else {
                                                    ?>
                                                        <svg width="18" height="16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <g clip-path="url(#a)">
                                                                <path d="M8.997 0c.287 0 .55.163.675.422l2.144 4.416 4.787.706a.753.753 0 0 1 .419 1.275l-3.472 3.443.819 4.863a.751.751 0 0 1-1.094.787l-4.281-2.287-4.275 2.284a.746.746 0 0 1-.79-.053.757.757 0 0 1-.304-.734l.819-4.863L.972 6.82a.748.748 0 0 1-.185-.766.755.755 0 0 1 .604-.51l4.787-.705L8.322.421A.75.75 0 0 1 8.997 0Zm0 2.469L7.357 5.85a.757.757 0 0 1-.566.416l-3.697.543 2.684 2.66a.752.752 0 0 1 .213.656l-.635 3.74 3.288-1.756a.744.744 0 0 1 .706 0l3.287 1.757-.63-3.738a.743.743 0 0 1 .212-.656l2.684-2.66-3.697-.546a.755.755 0 0 1-.565-.416L8.997 2.469Z" fill="#000" />
                                                            </g>
                                                            <defs>
                                                                <clipPath id="a">
                                                                    <path fill="#fff" d="M0 0h18v16H0z" />
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                <?php
                                                    }
                                                }

                                                ?>

                                            </div>
                                            <div class="realviews-review__date">
                                                <time>juni 2024</time>
                                            </div>
                                            <div class="realviews-review__interval">
                                                <p>Review written after 3 months</p>
                                            </div>
                                        </div>
                                        <div class="realviews-review__body">
                                            <p>
                                                Lorem ipsum dolor sit amet consectetur adipisicing elit. Adipisci non aspernatur nulla enim cupiditate repudiandae vero excepturi aliquam est voluptatem aliquid inventore sint, commodi ullam amet, eos, tempora nemo ratione!
                                            </p>
                                        </div>
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                    </div>
                </div><!-- modal -->
            <?php }; //if 
            ?>

            <div class="realviews-write-review-wrapper" data-encoded=" <?php echo $encodedAccountIds; ?>">
                <div class="btn realviews-write-review">Write review</div>
                <div class="realviews-modal">
                    <div class="realviews-modal__bg"></div>

                    <div class="realviews-modal__inner">
                        <div class="realviews-modal__header">
                            <button class="realviews-modal__close">
                                <svg width="15" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.383 5.883a1.252 1.252 0 0 0-1.77-1.77L7.5 8.23 3.383 4.117a1.252 1.252 0 0 0-1.77 1.77L5.73 10l-4.113 4.117a1.252 1.252 0 0 0 1.77 1.77L7.5 11.77l4.117 4.113a1.252 1.252 0 0 0 1.77-1.77L9.27 10l4.113-4.117Z" fill="#000" />
                                </svg>
                            </button>

                            <h2>Write a review for <?php echo $title; ?></h2>
                        </div>

                        <div class="realviews-modal__body">
                            <form id="write-review" class="realviews-modal__form">

                                <div class="realviews-rating" id="rating-wrapper">
                                    <span>Rating:</span>
                                    <div class="realviews-stars">
                                        <?php for ($i = 5; $i >= 1; $i--) { ?>
                                            <div class="realviews-stars__star" id="<?php echo $i; ?>"></div>
                                        <?php } ?>
                                    </div>
                                    <span class="realviews-rating__display"><span class="selected-rating">0</span>/5</span>
                                </div>

                                <input type="text" id="name" name="name" placeholder="Name">
                                <textarea name="message" id="message" placeholder="Message"></textarea>
                                <button type="submit" class="btn realviews-submit-review">Submit</button>
                            </form>
                        </div>
                    </div>
                </div><!-- modal -->
            </div><!-- wrapper-->

        </div><!-- realviews-actions -->

    </section>
<?php
    return ob_get_clean();
}
