export const validateForm = function validateForm(reviewForm, rating) {
    const notices = reviewForm.querySelector('.write-review-notices');
    notices.innerText = ''; // reset notices

    let submitButton = reviewForm.querySelector('.realviews-submit-review');
    const transactionId = submitButton.dataset.transactionId;
    if (!transactionId) {
        console.log('transaction id missing');
        return;
    }
    const name = reviewForm.querySelector('#name').value;
    const message = reviewForm.querySelector('#message').value;
    let passed = true;

    if (!name || name === '') {
        notices.innerText += ' Name is required. ';
        passed = false;
    } else {
        if (name.length <= 2) {
            notices.innerText += ' Name is too short. ';
            passed = false;
        }
    }

    if (!message || message === '') {
        notices.innerText += ' Message is required. ';
        passed = false;
    }

    if (!rating) {
        notices.innerText += ' Rating is required. ';
        passed = false;
    } else {
        if (!(rating > 0 && rating <= 5)) {
            notices.innerText += ' Rating is invalid. ';
            passed = false;
        }
    }

    if (message.length > 900) {
        notices.innerText += 'Review is too long. ';
        passed = false;
    }

    if (!passed) {
        return { passed, undefined };
    }

    let review = {
        transactionId, // pay transaction
        rating,
        name,
        message,
    };

    const reviewString = JSON.stringify(review);
    return { passed, reviewString };
};
