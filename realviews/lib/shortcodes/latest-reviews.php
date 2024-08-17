<?php
add_shortcode('realviews_latest_reviews', 'realviews_latest_reviews_function', 5);
function realviews_latest_reviews_function($atts)
{
    if (is_product()) {
        global $product;
        $post_id = $product->get_id();
    } else {
        global $post;
        $post_id = $post->ID;
    }

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

    // delete_post_meta($post_id, '_transaction_ids');
    // delete_post_meta($post_id, '_contract_ids');

    // delete_post_meta($post_id, '_review_transaction_ids');


    $transaction_ids = get_post_meta($post_id, '_transaction_ids', true);
    // debug($transaction_ids);

    // $contract_ids = get_post_meta($post_id, '_contract_ids', true);
    // debug($contract_ids);


    $review_transaction_ids = get_post_meta($post_id, '_review_transaction_ids', true);
    // debug($review_transaction_ids);

    $encodedTransactionIds = base64_encode(json_encode($transaction_ids));     // Encode the JSON string using Base64

    ob_start();

?>
    <section class="realviews">
        <?php $num_reviews = $review_transaction_ids ? count($review_transaction_ids) : 0;
        ?>

        <h2>Reviews (<?php echo $num_reviews; ?>)</h2>
        <div class="realviews-wrapper">

            <?php if ($num_reviews > 0) { ?>
                <?php for ($i = 0; $i < min($num_reviews, $max_reviews); $i++) {
                    $review_transaction_id = $review_transaction_ids[$i];
                ?>
                    <?php
                    require 'parts/review.php';
                    ?>
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
                                <?php for ($i = 0; $i < $num_reviews; $i++) {
                                    $review_transaction_id = $review_transaction_ids[$i];
                                    require 'parts/review.php';
                                } ?>
                            </div>
                        </div>
                    </div>
                </div><!-- modal -->
            <?php }; //if 
            ?>
            <div class="realviews-write-review-wrapper" data-encoded="<?php echo $encodedTransactionIds; ?>">
                <?php $review_transaction_id_param = isset($_GET['review_transaction_id']) ? $_GET['review_transaction_id'] : null;
                if (!$review_transaction_id_param) { ?>
                    <div class="btn realviews-write-review">Write review</div>
                <?php } else { ?>
                    <p>Success</p>
                    <?php
                    add_meta_to_post($post_id, '_review_transaction_ids', $review_transaction_id_param);
                    ?>
                <?php } ?>
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
