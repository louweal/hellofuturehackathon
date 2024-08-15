export const handleAllReviewsToggle = function handleAllReviewsToggle() {
    let body = document.querySelector('body');

    let showModalButtons = document.querySelectorAll('.show-realviews-modal');
    [...showModalButtons].forEach((showModalButton) => {
        showModalButton.addEventListener('click', function () {
            let modal = showModalButton.nextElementSibling;
            modal.classList.add('is-active');
            body.classList.add('realviews-modal-open');
        });
    });

    let modals = document.querySelectorAll('.realviews-modal');
    [...modals].forEach((modal) => {
        let modalBg = modal.querySelector('.realviews-modal__bg');
        let closeModalButton = modal.querySelector('.realviews-modal__close');

        modalBg.addEventListener('click', function () {
            modal.classList.remove('is-active');
            body.classList.remove('realviews-modal-open');
        });

        closeModalButton.addEventListener('click', function () {
            modal.classList.remove('is-active');
            body.classList.remove('realviews-modal-open');
        });
    });
};
