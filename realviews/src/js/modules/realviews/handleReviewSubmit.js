// import { deployReviewContract } from './deployReviewContract';

// export const handleReviewSubmit = function handleReviewSubmit(pairingData) {
//     let reviewForm = document.querySelector('#write-review');
//     if (reviewForm) {
//         const ratingWrapper = reviewForm.querySelector('#rating-wrapper');
//         const ratingDisplay = ratingWrapper.querySelector('.selected-rating');
//         let rating;

//         const stars = ratingWrapper.querySelectorAll('.realviews-stars__star');
//         [...stars].forEach((star) => {
//             star.addEventListener('click', function () {
//                 // reset active states
//                 [...stars].forEach((star) => {
//                     star.classList.remove('is-active');
//                 });

//                 rating = +star.id;
//                 ratingDisplay.innerText = +rating;
//                 star.classList.add('is-active');
//             });
//         });

//         reviewForm.addEventListener('submit', function (event) {
//             event.preventDefault();

//             const transactionId = reviewForm.dataset.transactionId;
//             const name = reviewForm.querySelector('#name').value;
//             const message = reviewForm.querySelector('#message').value;
//             const timestamp = Math.round(Date.now() / 1000); // timestamp in seconds

//             console.log(timestamp);
//             console.log(rating);
//             console.log(name);
//             console.log(message);

//             let review = {
//                 timestamp, // review timestamp
//                 transactionId, // pay transaction
//                 rating,
//                 name,
//                 message,
//             };

//             const reviewString = JSON.stringify(review);

//             // console.log('localAccountId :>>', localAccountId);

//             deployReviewContract(reviewString);
//         });
//     }
// };
