export const handleWriteReviewToggle = function handleWriteReviewToggle() {
    let body = document.querySelector('body');

    let showWriteModalButtons = document.querySelectorAll('.realviews-write-review');
    [...showWriteModalButtons].forEach((showWriteModalButton) => {
        showWriteModalButton.addEventListener('click', function () {
            let writeModal = showWriteModalButton.nextElementSibling;
            writeModal.classList.add('is-active');
            body.classList.add('realviews-modal-open');
        });
    });
};
