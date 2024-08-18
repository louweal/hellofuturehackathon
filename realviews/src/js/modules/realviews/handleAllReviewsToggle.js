import { loadReviews } from './loadReviews';

export const handleAllReviewsToggle = function handleAllReviewsToggle() {
    let body = document.querySelector('body');

    let showModalButtons = document.querySelectorAll('.show-realviews-modal');
    [...showModalButtons].forEach((showModalButton) => {
        showModalButton.addEventListener('click', function () {
            let modal = showModalButton.nextElementSibling;
            modal.classList.add('is-active');
            body.classList.add('realviews-modal-open');
            loadReviews(true); // load all reviews inside the modal
        });
    });
};
