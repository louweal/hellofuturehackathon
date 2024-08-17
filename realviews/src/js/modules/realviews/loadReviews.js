import { fetchMirrornodeLogData } from './fetchMirrornodeLogData';
import { formatTimestamp } from './formatTimestamp';
import { parseTransactionId, unparseTransactionId } from './parseTransactionId';

export const loadReviews = async function loadReviews() {
    let reviews = document.querySelectorAll('.realviews-review');

    [...reviews].forEach(async (review) => {
        let reviewTransactionId = review.id;
        if (reviewTransactionId) {
            let icon = review.querySelector('.realviews-review__icon');
            let name = review.querySelector('.realviews-review__username');
            let stars = review.querySelectorAll('.realviews-review__star');
            let buyDate = review.querySelector('.realviews-review__date1');
            let buyDateTime = review.querySelector('.realviews-review__date1 time');
            let reviewDate = review.querySelector('.realviews-review__date2');
            let reviewDateTime = review.querySelector('.realviews-review__date2 time');
            let body = review.querySelector('.realviews-review__body p');

            let reviewData = JSON.parse(await fetchMirrornodeLogData(reviewTransactionId));

            // set stars
            let i = 1;
            [...stars].forEach((star) => {
                if (reviewData.rating >= i) {
                    star.classList.add('is-solid');
                } else {
                    star.classList.remove('is-solid');
                }
                i += 1;
            });

            icon.innerText = reviewData.name[0]; // set icon
            name.innerText = reviewData.name; // set name
            body.innerText = reviewData.message; // set message

            // set buy date info
            buyDate.setAttribute('href', 'https://hashscan.io/testnet/transactionsById/' + reviewData.transactionId);
            let unparsedTransactionId = unparseTransactionId(reviewData.transactionId);
            let formattedBuyDate = formatTimestamp(unparsedTransactionId.split('@')[1]);
            // console.log(unparsedTransactionId.split('@')[1]);
            buyDateTime.innerText = formattedBuyDate;
            buyDateTime.addEventListener('mouseover', function () {
                buyDateTime.innerText = unparsedTransactionId;
            });
            buyDateTime.addEventListener('mouseout', function () {
                buyDateTime.innerText = formattedBuyDate;
            });

            // Set review date info
            reviewDate.setAttribute(
                'href',
                'https://hashscan.io/testnet/transactionsById/' + parseTransactionId(reviewTransactionId),
            );

            let formattedReviewDate = formatTimestamp(reviewTransactionId.split('@')[1]);
            reviewDateTime.innerText = formattedReviewDate;
            reviewDateTime.addEventListener('mouseover', function () {
                reviewDateTime.innerText = reviewTransactionId;
            });
            reviewDateTime.addEventListener('mouseout', function () {
                reviewDateTime.innerText = formattedReviewDate;
            });

            review.classList.remove('is-loading');
        }
    });
};
