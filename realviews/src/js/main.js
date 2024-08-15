import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { ContractId, ContractExecuteTransaction, ContractFunctionParameters } from '@hashgraph/sdk';

import { displayWriteReviewButtons } from './modules/displayWriteReviewButtons';
import { getCashbackContractId } from './modules/getCashbackContractId';
import { setQueryParamAndRedirect } from './modules/setQueryParamsAndRedirect';
import { decodeData } from './modules/decodeData';
import { getTinybarAmount } from './modules/getTinybarAmount';
import { getNetworkId } from './modules/getNetworkId';
import { setConnectButtonsText } from './modules/setConnectButtonsText';
import { setVisibleAccountId } from './modules/setVisibleAccountId';

// Main thread
(function () {
    'use strict';

    // testFun();

    // async function testFun() {
    //     let testUrl = 'https://testnet.mirrornode.hedera.com/api/v1/transactions/0.0.4505361-1723726703-552864158';
    //     const restResponse = await fetch(testUrl, {
    //         method: 'GET',
    //         headers: {},
    //     });
    //     const text = await restResponse.json();
    //     console.log(text);
    // }

    let createContractButton = document.querySelector('.create-contract-button');
    if (createContractButton) {
        createContractButton.addEventListener('click', async function () {
            if (!pairingData) {
                await init('testnet');
            }

            const factoryContractId = ContractId.fromString('0.0.4685895');
            console.log(factoryContractId.toString());
            let bSeconds = 1723574410;
            let bNanoseconds = 0;
            let amount = 1000; // tinybar, integer!
            let cashback = 1; // tinybar
            let shopOwner = AccountId.fromString('0.0.4507369');
            let iSeconds = 4;

            let fromAccount = AccountId.fromString(pairingData.accountIds[0]);

            let signer = hashconnect.getSigner(fromAccount);

            //Create the transaction to deploy a new CashbackReview contract
            let transaction = await new ContractExecuteTransaction()
                //Set the ID of the contract
                .setContractId(factoryContractId)
                //Set the gas for the call
                .setGas(2000000)
                //Set the function of the contract to call
                .setFunction(
                    'deployCashbackReview',
                    new ContractFunctionParameters()
                        .addUint32(bSeconds)
                        .addUint32(bNanoseconds)
                        .addUint64(cashback)
                        .addAddress(shopOwner)
                        .addUint32(iSeconds),
                )
                .setPayableAmount(Hbar.fromTinybars(amount))
                .freezeWithSigner(signer);

            let response = await transaction.executeWithSigner(signer);
            // console.log(response);

            //Confirm the transaction was executed successfully
            const transactionId = response.transactionId.toString();
            // console.log('Transaction ID:', transactionId);
            let receipt = await response.getReceiptWithSigner(signer);
            // console.log(receipt);
            console.log('The transaction status is ' + receipt.status.toString());
            if (receipt.status._code === 22) {
                // add transactionId to url and redirect
                setQueryParamAndRedirect(transactionId);
            } else {
                console.log('Oops');
            }
        });
    }

    let metadata = document.querySelector('#hederapay-app-metadata');

    let appMetadata = {};
    if (metadata) {
        let metadataData = decodeData(metadata.dataset.attributes);

        appMetadata = {
            name: metadataData.name,
            description: metadataData.description,
            icons: [metadataData.icon],
            url: metadataData.url,
        };
    }

    let hashconnect;
    let state = `HashConnectConnectionState`.Disconnected;
    let pairingData;

    if (!hashconnect) {
        localStorage.removeItem('accountId');
    }

    let localAccountId = localStorage.getItem('accountId');

    setVisibleAccountId(localAccountId);
    setConnectButtonsText(localAccountId ? 'disconnect_text' : 'connect_text');

    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    [...connectButtons].forEach((connectButton) => {
        connectButton.addEventListener('click', async function () {
            let buttonData = decodeData(connectButton.dataset.attributes);

            if (!pairingData && !localAccountId) {
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

    let reviewForm = document.querySelector('#write-review');
    if (reviewForm) {
        const ratingWrapper = reviewForm.querySelector('#rating-wrapper');
        const rating = ratingWrapper.querySelector('.selected-rating');
        let ratingValue;

        const stars = ratingWrapper.querySelectorAll('.realviews-stars__star');
        [...stars].forEach((star) => {
            star.addEventListener('click', function () {
                // reset active states
                [...stars].forEach((star) => {
                    star.classList.remove('is-active');
                });

                ratingValue = star.id;
                rating.innerText = ratingValue;
                star.classList.add('is-active');
            });
        });

        reviewForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const name = reviewForm.querySelector('#name').value;
            const message = reviewForm.querySelector('#message').value;

            console.log(ratingValue);
            console.log(name);
            console.log(message);

            // todo: create contract
        });
    }

    displayWriteReviewButtons();

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
})();
