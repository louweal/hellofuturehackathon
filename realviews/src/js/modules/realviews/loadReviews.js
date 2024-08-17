import { decodeData } from '../decodeData';
import { fetchMirrornodeLogData } from './fetchMirrornodeLogData';
import { formatTimestamp } from './formatTimestamp';
import { parseTransactionId } from './parseTransactionId';

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

            let body = review.querySelector('.realviews-review__body');

            let timestamp = reviewTransactionId.split('@')[1];
            console.log(timestamp);

            console.log(formatTimestamp(timestamp));

            let encodedReviewData = await fetchMirrornodeLogData(reviewTransactionId);
            let reviewData = JSON.parse(encodedReviewData);

            console.log(reviewData);

            let i = 1;
            [...stars].forEach((star) => {
                if (reviewData.rating >= i) star.classList.add('is-solid');
                i += 1;
            });

            icon.innerText = reviewData.name[0];
            name.innerText = reviewData.name;
            buyDate.setAttribute('href', '#');

            buyDateTime.innerText = formatTimestamp(reviewData.timestamp + '.00000');
            reviewDate.setAttribute(
                'href',
                'https://hashscan.io/testnet/transactionsById/' + parseTransactionId(reviewTransactionId),
            );
            let formattedReviewDate = formatTimestamp(timestamp);
            reviewDateTime.innerText = formattedReviewDate;
            body.innerText = reviewData.message;

            reviewDateTime.addEventListener('mouseover', function () {
                reviewDateTime.innerText = reviewTransactionId;
            });
            reviewDateTime.addEventListener('mouseout', function () {
                reviewDateTime.innerText = formattedReviewDate;
            });
        }
    });
};
