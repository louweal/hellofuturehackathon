import { decodeData } from './decodeData';

export const displayWriteReviewButtons = function displayWriteReviewButtons() {
    let localAccountId = localStorage.getItem('accountId');
    console.log('localAccountId :>> ', localAccountId);

    if (localAccountId) {
        let writeReviewWrappers = document.querySelectorAll('.realviews-write-review-wrapper');
        [...writeReviewWrappers].forEach((writeReviewWrapper) => {
            let accountIds = decodeData(writeReviewWrapper.dataset.encoded);

            if (accountIds.includes(localAccountId)) {
                writeReviewWrapper.classList.add('is-active');
            } else {
                console.log('Different account');
            }
        });
    }
};
