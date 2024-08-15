import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { LedgerId } from '@hashgraph/sdk';

/* IMPORTANT: THIS SCRIPT IS NOT ENQUEUED WHEN REALVIEWS IS ACTIVE */

// Main thread
(function () {
    'use strict';

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
    let state = HashConnectConnectionState.Disconnected;
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
                if (transactionData.store === true || transactionData.store === 'true') {
                    const currentUrl = new URL(window.location.href);
                    // Add the parameter to the URL
                    let urlTransactionId = transactionId.replace('@', '-');
                    currentUrl.searchParams.set('transaction_id', urlTransactionId);

                    // Redirect to the new URL with the additional parameter
                    window.location.href = currentUrl.href;
                } else {
                    transactionNotices.innerText += 'Payment received. Thank you! ';
                    console.log(transactionData.store);
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

    async function getTinybarAmount(transactionWrapper, transactionData) {
        let transactionInput = transactionWrapper.querySelector('.hederapay-transaction-input');
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
        transactionNotices.innerText = ''; // reset

        let currency = transactionData.currency;
        let amount = transactionData.amount;

        if (!amount) {
            // check for user input
            if (transactionInput) {
                if (transactionInput.value != '') {
                    let amountInputValue = transactionInput.value;
                    return await convertCurrencyToTinybar(amountInputValue, currency);
                } else {
                    transactionNotices.innerText += 'Please enter the amount.';
                    return null; // do nothing; amount missing
                }
            } else {
                return null; // do nothing; amount missing and input field missing
            }
        } else {
            return await convertCurrencyToTinybar(amount, currency);
        }
    }

    function getNetworkId(network) {
        if (network == 'mainnet') return LedgerId.MAINNET;
        if (network == 'previewnet') return LedgerId.PREVIEWNET;
        return LedgerId.TESTNET;
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
        });

        hashconnect.disconnectionEvent.on(() => {
            pairingData = null;
            localStorage.removeItem('accountId'); // remove from browser storage
            setConnectButtonsText('connect_text');
            setVisibleAccountId(undefined);
        });

        hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            state = connectionStatus;
        });
    }

    //helper functions

    function setVisibleAccountId(pairedAccount) {
        let pairedAccountDisplays = document.querySelectorAll('.hederapay-paired-account');
        [...pairedAccountDisplays].forEach((pairedAccountDisplay) => {
            if (pairedAccount) {
                pairedAccountDisplay.innerHTML = pairedAccount;
                pairedAccountDisplay.style.display = 'inline';
            } else {
                pairedAccountDisplay.innerHTML = '';
                pairedAccountDisplay.style.display = 'none';
            }
        });
    }

    function setConnectButtonsText(attribute) {
        let connectButtons = document.querySelectorAll('.hederapay-connect-button');
        [...connectButtons].forEach((connectButton) => {
            let connectButtonData = decodeData(connectButton.dataset.attributes);
            let connectButtonText = connectButton.querySelector('.hederapay-connect-button-text');
            connectButtonText.innerText = connectButtonData[attribute];
        });
    }

    async function getHbarPrice(currency) {
        const url = 'https://api.coingecko.com/api/v3/simple/price?ids=hedera-hashgraph&vs_currencies=' + currency;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            const data = await response.json();
            return data['hedera-hashgraph'][currency];
        } catch (error) {
            console.error('Error fetching HBAR price:', error);
            throw error;
        }
    }

    async function convertCurrencyToTinybar(amount, currency) {
        try {
            const hbarPriceInCurrency = await getHbarPrice(currency);
            if (hbarPriceInCurrency === undefined) {
                throw new Error('Failed to retrieve HBAR price');
            }
            return Math.round((amount / hbarPriceInCurrency) * 1e8);
        } catch (error) {
            console.error('Error converting HBAR to currency:', error);
            throw error;
        }
    }

    function decodeData(encodedData) {
        let jsonData = atob(encodedData);
        return JSON.parse(jsonData);
    }
})();
