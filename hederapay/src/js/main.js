import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { LedgerId } from '@hashgraph/sdk';

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

    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    let clickedConnectButton;

    [...connectButtons].forEach((connectButton) => {
        connectButton.addEventListener('click', async function () {
            clickedConnectButton = connectButton;

            let buttonData = decodeData(connectButton.dataset.attributes);
            let network = buttonData.network;
            if (!pairingData) {
                await init(network); //connect
            } else {
                hashconnect.disconnect(); // disconnect wallet
            }
        });
    });

    let transactionWrappers = document.querySelectorAll('.hederapay-transaction-wrapper');

    [...transactionWrappers].forEach((transactionWrapper) => {
        let transactionInput = transactionWrapper.querySelector('.hederapay-transaction-input');
        if (transactionInput) {
            transactionInput.addEventListener('change', function (event) {
                if (transactionInput.value != '') {
                    transactionButton.removeAttribute('disabled');
                } else {
                    transactionButton.setAttribute('disabled', ''); // enable button
                }
            });
        }

        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        transactionButton.addEventListener('click', async function () {
            let transactionData = decodeData(transactionButton.dataset.attributes);
            let network = transactionData.network;

            if (!pairingData) {
                await init(network);
            }

            handleTransaction(transactionWrapper, transactionData);
        }); // eventlistener
    }); //foreach

    async function handleTransaction(transactionWrapper, transactionData) {
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
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

            const transactionId = response.transactionId;
            console.log('Transaction ID:', transactionId.toString());
            let receipt = await response.getReceiptWithSigner(signer);

            console.log(receipt);

            // Check if the transaction was successful
            if (receipt.status.toString() === 'SUCCESS') {
                // executed from woocommerce gateway
                if (transactionButton.dataset.woocommerce == 'true') {
                    const currentUrl = new URL(window.location.href);
                    // Add the parameter to the URL
                    currentUrl.searchParams.set('transaction', 'success');
                    // Redirect to the new URL with the additional parameter
                    window.location.href = currentUrl.href;
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
    window.handleTransaction = handleTransaction;

    async function getTinybarAmount(transactionWrapper, transactionData) {
        let transactionInput = transactionWrapper.querySelector('.hederapay-transaction-input');
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
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

        await hashconnect.init(); // Initialize

        if (!pairingData) {
            hashconnect.openPairingModal(); // Open pairing modal
        }
    }

    function setUpHashConnectEvents() {
        // let connectButtons = document.querySelectorAll('.hederapay-connect-button');
        let pairedAccountDisplays = document.querySelectorAll('.hederapay-paired-account');

        let selectedConnectButton = clickedConnectButton || document.querySelector('.hederapay-connect-button');
        let selectedConnectButtonText = selectedConnectButton.querySelector('.hederapay-connect-button-text');
        let selectedConnectButtonData = decodeData(selectedConnectButton.dataset.attributes);

        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
            selectedConnectButtonText.innerText = selectedConnectButtonData.disconnect_text;
            selectedConnectButton.classList.add('is-connected');

            let id = pairingData.accountIds[0];
            let network = selectedConnectButtonData.network;

            let url = ''; // no url for previewnet
            if (network === 'testnet') {
                url = 'https://testnet.dragonglass.me/accounts/' + id;
            } else {
                url = 'https://app.dragonglass.me/accounts/' + id;
            }

            [...pairedAccountDisplays].forEach((pairedAccountDisplay) => {
                pairedAccountDisplay.innerHTML = `<a target="_blank" href="${url}">${id}</a>`;
                pairedAccountDisplay.style.display = 'inline';
            });
        });

        hashconnect.disconnectionEvent.on(() => {
            pairingData = null;
            selectedConnectButtonText.innerText = selectedConnectButtonData.connect_text;
            selectedConnectButton.classList.remove('is-connected');

            [...pairedAccountDisplays].forEach((pairedAccountDisplay) => {
                pairedAccountDisplay.innerHTML = '';
                pairedAccountDisplay.style.display = 'none';
            });
        });

        hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            state = connectionStatus;
        });
    }

    //helper functions

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
