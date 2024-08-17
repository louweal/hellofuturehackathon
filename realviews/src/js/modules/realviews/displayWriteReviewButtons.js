import { decodeData } from '../decodeData';

export const displayWriteReviewButtons = function displayWriteReviewButtons() {
    let localAccountId = localStorage.getItem('accountId');
    // console.log('localAccountId :>> ', localAccountId);

    if (localAccountId) {
        let writeReviewWrappers = document.querySelectorAll('.realviews-write-review-wrapper');
        [...writeReviewWrappers].forEach((writeReviewWrapper) => {
            let transactionIds = decodeData(writeReviewWrapper.dataset.encoded);
            for (let i = transactionIds.length - 1; i >= 0; i--) {
                // loop to get newest first
                const transactionId = transactionIds[i];
                if (transactionId.includes(localAccountId)) {
                    writeReviewWrapper.classList.add('is-active');
                    break;
                }
            }
        });
    }
};
