import { fetchMirrornodeLogData } from './fetchMirrornodeLogData';
import { formatTimestamp } from './formatTimestamp';
import { parseTransactionId, unparseTransactionId } from './parseTransactionId';

export const loadReviews = async function loadReviews(loadModalReviews = false) {
    let reviews;

    if (loadModalReviews) {
        reviews = document.querySelectorAll('.realviews-modal .realviews-review.is-loading');
    } else {
        reviews = document.querySelectorAll(
            '.realviews-latest-reviews > .realviews-wrapper > .realviews-review.is-loading',
        );
    }

    [...reviews].forEach(async (review) => {
        let rId = review.id;
        if (rId) {
            let badge = review.querySelector('.realviews-review__badge');
            let icon = review.querySelector('.realviews-review__icon');
            let name = review.querySelector('.realviews-review__username');
            let stars = review.querySelectorAll('.realviews-review__star');
            let buyDate = review.querySelector('.realviews-review__date1');
            let buyDateTime = review.querySelector('.realviews-review__date1 time');
            let reviewDate = review.querySelector('.realviews-review__date2');
            let reviewDateTime = review.querySelector('.realviews-review__date2 time');
            let body = review.querySelector('.realviews-review__body p');

            let { network, reviewData } = await fetchMirrornodeLogData(rId);
            let baseUrl = `https://${network}.mirrornode.hedera.com/api/v1/transactions/`;
            let contractBaseUrl = `https://${network}.mirrornode.hedera.com/api/v1/contracts/results/`;

            reviewData = JSON.parse(reviewData);

            // show testnet and previewnet badge
            if (network != 'mainnet') {
                badge.innerText = network;
                badge.style.display = 'block';
            }

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

            icon.innerText = reviewData.name[0].toUpperCase(); // set icon
            name.innerText = reviewData.name; // set name
            body.innerText = reviewData.message; // set message

            // set buy date info
            buyDate.setAttribute('href', `${baseUrl}${reviewData.transactionId}`);
            let bId = unparseTransactionId(reviewData.transactionId);
            let formattedBuyDate = formatTimestamp(bId.split('@')[1]);
            buyDateTime.innerText = formattedBuyDate;
            buyDateTime.addEventListener('mouseover', function () {
                buyDateTime.innerText = bId.substring(0, 7) + '...' + bId.substring(bId.length - 7);
            });
            buyDateTime.addEventListener('mouseout', function () {
                buyDateTime.innerText = formattedBuyDate;
            });

            // Set review date info
            reviewDate.setAttribute('href', `${contractBaseUrl}${parseTransactionId(rId)}`);

            let formattedReviewDate = formatTimestamp(rId.split('@')[1]);
            reviewDateTime.innerText = formattedReviewDate;
            reviewDateTime.addEventListener('mouseover', function () {
                reviewDateTime.innerText = rId.substring(0, 7) + '...' + rId.substring(rId.length - 7);
            });
            reviewDateTime.addEventListener('mouseout', function () {
                reviewDateTime.innerText = formattedReviewDate;
            });

            review.classList.remove('is-loading');
        }
    });
};
