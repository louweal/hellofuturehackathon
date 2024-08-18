import { decodeData } from '../decodeData';

export const displayWriteReviewButtons = function displayWriteReviewButtons() {
    let localAccountId = localStorage.getItem('accountId');

    if (localAccountId) {
        let writeReviewWrappers = document.querySelectorAll('.realviews-write-review-wrapper');
        [...writeReviewWrappers].forEach((writeReviewWrapper) => {
            let transactionIds = decodeData(writeReviewWrapper.dataset.encoded);
            for (let i = transactionIds.length - 1; i >= 0; i--) {
                // loop to get newest first
                const transactionId = transactionIds[i];
                if (transactionId.includes(localAccountId)) {
                    writeReviewWrapper.classList.add('is-active');
                    let writeReviewButton = writeReviewWrapper.querySelector('.realviews-write-review');
                    let writeReviewModal = writeReviewButton.nextElementSibling;
                    let submitButton = writeReviewModal.querySelector('.realviews-submit-review');
                    submitButton.dataset.transactionId = transactionId;
                    break;
                }
            }
        });
    }
};
