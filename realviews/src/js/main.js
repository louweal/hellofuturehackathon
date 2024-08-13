// Main thread
(function () {
    'use strict';

    let body = document.querySelector('body');

    let showModalButtons = document.querySelectorAll('.show-realviews-modal');

    [...showModalButtons].forEach((showModalButton) => {
        showModalButton.addEventListener('click', function () {
            let modal = showModalButton.nextElementSibling;
            modal.classList.add('is-active');
            body.classList.add('realviews-modal-open');
        });
    });

    let showWriteModalButtons = document.querySelectorAll('.realviews-write-review');
    [...showWriteModalButtons].forEach((showWriteModalButton) => {
        showWriteModalButton.addEventListener('click', function () {
            let writeModal = showWriteModalButton.nextElementSibling;
            writeModal.classList.add('is-active');
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

    let writeReviewWrappers = document.querySelectorAll('.realviews-write-review-wrapper');
    [...writeReviewWrappers].forEach((writeReviewWrapper) => {
        let accountId = decodeData(writeReviewWrapper.dataset.encoded);
        console.log(accountId);

        let localAccountId = localStorage.getItem('accountId');
        console.log(localAccountId);

        if (localAccountId) {
            if (accountId === localAccountId) {
                writeReviewWrapper.classList.add('is-active');
            } else {
                console.log('Different account');
            }
        } else {
            console.log('not paired');
        }
    });
})();
