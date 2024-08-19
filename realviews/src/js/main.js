import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { ContractId, ContractExecuteTransaction, ContractFunctionParameters } from '@hashgraph/sdk';

import { setAppMetadata } from './modules/setAppMetadata';
import { setQueryParamAndRedirect } from './modules/setQueryParamAndRedirect';
import { decodeData } from './modules/decodeData';
import { getTinybarAmount } from './modules/getTinybarAmount';
import { getNetworkId } from './modules/getNetworkId';
import { setConnectButtonsText } from './modules/setConnectButtonsText';
import { setVisibleAccountId } from './modules/setVisibleAccountId';
import { setWalletConnectId } from './modules/setWalletConnectId';

// Realviews
import { handleAllReviewsToggle } from './modules/realviews/handleAllReviewsToggle';
import { handleWriteReviewToggle } from './modules/realviews/handleWriteReviewToggle';
import { handleModalsHide } from './modules/realviews/handleModalsHide';
import { displayWriteReviewButtons } from './modules/realviews/displayWriteReviewButtons';
import { parseTransactionId } from './modules/realviews/parseTransactionId';
import { loadReviews } from './modules/realviews/loadReviews';
import { validateForm } from './modules/realviews/validateForm';
import { redirectPage } from './modules/realviews/redirectPage';

// Main thread
(function () {
    'use strict';

    //realviews

    let hashconnect;
    let state = HashConnectConnectionState.Disconnected;
    let pairingData;
    let appMetadata = setAppMetadata();

    let projectId = setWalletConnectId();

    let localAccountId = localStorage.getItem('accountId');

    if (!hashconnect && localAccountId) {
        localStorage.removeItem('accountId');
    }

    setVisibleAccountId(localAccountId);
    setConnectButtonsText(undefined, localAccountId ? 'disconnect_text' : 'connect_text');

    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    [...connectButtons].forEach((connectButton) => {
        connectButton.addEventListener('click', async function () {
            let buttonData = decodeData(connectButton.dataset.attributes);

            if (!pairingData) {
                await init(buttonData.network); //connect
            } else {
                await hashconnect.disconnect(); // disconnect wallet
            }
        });
    });

    let transactionWrappers = document.querySelectorAll('.hederapay-transaction-wrapper');

    [...transactionWrappers].forEach((transactionWrapper) => {
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        transactionButton.addEventListener('click', async function () {
            let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
            transactionNotices.innerText = ''; // reset

            let transactionData = decodeData(transactionButton.dataset.attributes);
            let network = transactionData.network;

            let tinybarAmount = await getTinybarAmount(transactionWrapper, transactionData);
            if (!tinybarAmount) return;

            // connected to wrong network
            if (pairingData && pairingData.network != network) {
                console.log('wrong network');
                transactionNotices.innerText += "You're connected to the wrong network. Please reload and try again.";
                await hashconnect.disconnect(); // disconnect wallet
                // await new Promise((resolve) => setTimeout(resolve, 3000)); // mirror node will have received the transaction after ±10 seconds
                // location.reload(true);
                return;
            }

            if (!pairingData) {
                await init(network);
            }

            await handleTransaction(transactionWrapper, transactionData);
        }); // eventlistener
    }); //foreach

    async function handleTransaction(transactionWrapper, transactionData) {
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');

        let tinybarAmount = await getTinybarAmount(transactionWrapper, transactionData);
        if (!tinybarAmount) return;

        let fromAccount = AccountId.fromString(pairingData.accountIds[0]); // assumes paired and takes first paired account id
        const toAccount = AccountId.fromString(transactionData.account);

        let signer = hashconnect.getSigner(fromAccount);
        let transaction = await new TransferTransaction()
            .addHbarTransfer(fromAccount, Hbar.fromTinybars(-1 * tinybarAmount)) //Sending account
            .addHbarTransfer(toAccount, Hbar.fromTinybars(tinybarAmount)) //Receiving account
            .setTransactionMemo(transactionData.memo)
            .freezeWithSigner(signer);

        try {
            let response = await transaction.executeWithSigner(signer);
            console.log(response);

            const transactionId = response.transactionId.toString();
            // console.log('Transaction ID:', transactionId);
            let receipt = await response.getReceiptWithSigner(signer);

            console.log(receipt);

            // Check if the transaction was successful
            if (receipt.status.toString() === 'SUCCESS') {
                if (transactionData.store === true || transactionData.store === 'true') {
                    // transaction data needs to be stored (for reviewing later)
                    setQueryParamAndRedirect('transaction_id', parseTransactionId(transactionId));
                } else {
                    transactionNotices.innerText += 'Payment received. Thank you! ';
                }
                return;
            }
            console.log(`Transaction failed with status: ${receipt.status}`);
        } catch (e) {
            if (e.code === 9000) {
                transactionNotices.innerText += 'Transaction rejected by user or insufficient balance. ';
            } else {
                transactionNotices.innerText += 'Transaction failed. Please try again. ';
            }
        }
        return;
    }

    async function init(network) {
        // Create the hashconnect instance
        hashconnect = null;
        let debugMode = true;
        hashconnect = new HashConnect(getNetworkId(network), projectId, appMetadata, debugMode);

        setUpHashConnectEvents(); // Register events

        try {
            await hashconnect.init(); // Initialize

            if (!pairingData) {
                hashconnect.openPairingModal(); // Open pairing modal
            }
        } catch (e) {
            console.log(e);
        }
    }

    function setUpHashConnectEvents() {
        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
            localStorage.setItem('accountId', pairingData.accountIds[0]); // set id in local browser storage
            setConnectButtonsText(pairingData.network, 'disconnect_text');
            setVisibleAccountId(pairingData.accountIds[0]);
            displayWriteReviewButtons();
        });

        hashconnect.disconnectionEvent.on(() => {
            setConnectButtonsText(pairingData.network, 'connect_text');
            pairingData = null;
            localStorage.removeItem('accountId'); // remove from browser storage
            setVisibleAccountId(undefined);
            displayWriteReviewButtons();
        });

        hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            state = connectionStatus;
            console.log(state);
        });
    }

    // Realviews
    handleAllReviewsToggle(); // show all reviews on click
    handleWriteReviewToggle(); // show write a review modal on click
    handleModalsHide(); // hide all active modals on click
    handleReviewSubmit(); // handle submission of the write review form
    displayWriteReviewButtons(); // handle visibility of the 'write review' button (active account must have a transaction record)
    loadReviews();

    // refresh page if a new review was just added
    if (new URLSearchParams(new URL(window.location.href).search).get('review_transaction_id')) {
        redirectPage();
    }

    function handleReviewSubmit() {
        let reviewForm = document.querySelector('#write-review');
        if (reviewForm) {
            const ratingWrapper = reviewForm.querySelector('#rating-wrapper');
            const ratingDisplay = ratingWrapper.querySelector('.selected-rating');
            let rating;

            const stars = ratingWrapper.querySelectorAll('.realviews-stars__star');
            [...stars].forEach((star) => {
                star.addEventListener('click', function () {
                    // reset active states
                    [...stars].forEach((star) => {
                        star.classList.remove('is-active');
                    });

                    rating = +star.id;
                    ratingDisplay.innerText = +rating;
                    star.classList.add('is-active');
                });
            });

            reviewForm.addEventListener('submit', function (event) {
                event.preventDefault();
                let { passed, reviewString } = validateForm(reviewForm, rating);
                if (!passed) return; //form validation failed

                executeReviewTransaction(reviewString);
            });
        }
    }

    async function executeReviewTransaction(data) {
        const contractId = ContractId.fromString('0.0.4688625'); //0.0.4687987

        let fromAccount = AccountId.fromString(pairingData.accountIds[0]);
        let signer = hashconnect.getSigner(fromAccount);

        //Create the transaction to deploy a new CashbackReview contract
        let transaction = await new ContractExecuteTransaction()
            //Set the ID of the contract
            .setContractId(contractId)
            //Set the gas for the call
            .setGas(2000000)
            //Set the function of the contract to call
            .setFunction('writeReview', new ContractFunctionParameters().addString(data))
            .freezeWithSigner(signer);

        let transactionId;
        try {
            let response = await transaction.executeWithSigner(signer);

            //Confirm the transaction was executed successfully
            transactionId = response.transactionId.toString();
            let receipt = await response.getReceiptWithSigner(signer);
            console.log('The transaction status is ' + receipt.status.toString());
            if (receipt.status._code === 22) {
                // console.log(transactionId);
                setQueryParamAndRedirect('review_transaction_id', transactionId);
            } else {
                console.log(receipt.status);
            }
        } catch (e) {
            console.log(e);

            // ignore weird hashconnect errors for now..
            // console.log(transactionId);
            // if (transactionId) {
            //     setQueryParamAndRedirect('review_transaction_id', transactionId);
            // }
        }
    }
})();
