import { addReviewToIPFS } from './addReviewToIPFS';

export const handleReviewSubmit = function handleReviewSubmit() {
    let reviewForm = document.querySelector('#write-review');
    if (reviewForm) {
        const ratingWrapper = reviewForm.querySelector('#rating-wrapper');
        const rating = ratingWrapper.querySelector('.selected-rating');
        let ratingValue;

        const stars = ratingWrapper.querySelectorAll('.realviews-stars__star');
        [...stars].forEach((star) => {
            star.addEventListener('click', function () {
                // reset active states
                [...stars].forEach((star) => {
                    star.classList.remove('is-active');
                });

                ratingValue = star.id;
                rating.innerText = ratingValue;
                star.classList.add('is-active');
            });
        });

        // addReviewToIPFS();

        reviewForm.addEventListener('submit', function (event) {
            event.preventDefault();

            console.log(reviewForm.dataset.transactionId);

            const name = reviewForm.querySelector('#name').value;
            const message = reviewForm.querySelector('#message').value;

            console.log(ratingValue);
            console.log(name);
            console.log(message);

            // todo: create contract
        });
    }
};
