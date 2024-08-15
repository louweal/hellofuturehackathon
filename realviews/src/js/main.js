import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { setAppMetadata } from './modules/setAppMetadata';
import { setQueryParamAndRedirect } from './modules/setQueryParamsAndRedirect';
import { decodeData } from './modules/decodeData';
import { getTinybarAmount } from './modules/getTinybarAmount';
import { getNetworkId } from './modules/getNetworkId';
import { setConnectButtonsText } from './modules/setConnectButtonsText';
import { setVisibleAccountId } from './modules/setVisibleAccountId';

// Realviews
import { handleAllReviewsToggle } from './modules/realviews/handleAllReviewsToggle';
import { handleContractCreateTest } from './modules/realviews/handleContractCreateTest';
import { handleWriteReviewToggle } from './modules/realviews/handleWriteReviewToggle';
import { handleModalsHide } from './modules/realviews/handleModalsHide';
import { handleReviewSubmit } from './modules/realviews/handleReviewSubmit';
import { displayWriteReviewButtons } from './modules/realviews/displayWriteReviewButtons';
import { fetchMirrornodeTransaction } from './modules/realviews/fetchMirrornodeTransaction';
import { getCashbackContractId } from './modules/realviews/getCashbackContractId';
// Main thread
(function () {
    'use strict';

    let hashconnect;
    let state = `HashConnectConnectionState`.Disconnected;
    let pairingData;
    let appMetadata = setAppMetadata();

    if (!hashconnect) {
        localStorage.removeItem('accountId');
    }

    handleContractCreateTest(pairingData);

    let localAccountId = localStorage.getItem('accountId');

    setVisibleAccountId(localAccountId);
    setConnectButtonsText(localAccountId ? 'disconnect_text' : 'connect_text');

    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    [...connectButtons].forEach((connectButton) => {
        connectButton.addEventListener('click', async function () {
            let buttonData = decodeData(connectButton.dataset.attributes);

            if (!pairingData) {
                await init(buttonData.network); //connect
            } else {
                hashconnect.disconnect(); // disconnect wallet
            }
        });
    });

    let transactionWrappers = document.querySelectorAll('.hederapay-transaction-wrapper');

    [...transactionWrappers].forEach((transactionWrapper) => {
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        transactionButton.addEventListener('click', async function () {
            let transactionData = decodeData(transactionButton.dataset.attributes);

            let tinybarAmount = await getTinybarAmount(transactionWrapper, transactionData);
            if (!tinybarAmount) return;

            if (!pairingData) {
                await init(transactionData.network);
            }

            handleTransaction(transactionWrapper, transactionData);
        }); // eventlistener
    }); //foreach

    async function handleTransaction(transactionWrapper, transactionData) {
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
        transactionNotices.innerText = ''; // reset

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
            console.log('Transaction ID:', transactionId);
            let receipt = await response.getReceiptWithSigner(signer);

            console.log(receipt);

            // Check if the transaction was successful
            if (receipt.status.toString() === 'SUCCESS') {
                // executed from woocommerce gateway
                if (transactionData.store === true) {
                    // transaction data needs to be stored (for reviewing later)
                    setQueryParamAndRedirect(transactionId);
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
        hashconnect = new HashConnect(getNetworkId(network), '606201a2da45f68c8084e2eea1f14ad7', appMetadata, true);

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
            setConnectButtonsText('disconnect_text');
            setVisibleAccountId(pairingData.accountIds[0]);
            displayWriteReviewButtons();
        });

        hashconnect.disconnectionEvent.on(() => {
            pairingData = null;
            localStorage.removeItem('accountId'); // remove from browser storage
            setConnectButtonsText('connect_text');
            setVisibleAccountId(undefined);
            displayWriteReviewButtons();
        });

        hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            state = connectionStatus;
        });
    }

    // Realviews
    handleAllReviewsToggle(); // show all reviews on click
    handleWriteReviewToggle(); // show write a review modal on click
    handleModalsHide(); // hide all active modals on click
    handleReviewSubmit(); // handle submission of the write review form
    displayWriteReviewButtons(); // handle visibility of the 'write review' button (active account must have a transaction record)
})();
