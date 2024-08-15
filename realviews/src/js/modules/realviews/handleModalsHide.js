export const handleModalsHide = function handleModalsHide() {
    let body = document.querySelector('body');
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
