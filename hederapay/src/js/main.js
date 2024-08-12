import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { LedgerId } from '@hashgraph/sdk';

// Main thread
(function () {
    'use strict';

    let metadata = document.querySelector('#hederapay-app-metadata');

    let appMetadata = {};
    if (metadata) {
        appMetadata = {
            name: metadata.dataset.name,
            description: metadata.dataset.description,
            icons: [metadata.dataset.icon],
            url: metadata.dataset.url,
        };
    }

    let hashconnect;
    let state = HashConnectConnectionState.Disconnected;
    let pairingData;
    window.pairingData = pairingData;

    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    let clickedConnectButton;

    [...connectButtons].forEach((connectButton) => {
        connectButton.addEventListener('click', async function () {
            clickedConnectButton = connectButton;
            let network = connectButton.dataset.network;
            console.log(pairingData);
            if (!pairingData) {
                await init(network); //connect
            } else {
                hashconnect.disconnect(); // disconnect wallet
            }
        });
    });

    let transactionWrappers = document.querySelectorAll('.hederapay-transaction-wrapper');

    [...transactionWrappers].forEach((transactionWrapper) => {
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        transactionButton.addEventListener('click', async function () {
            let network = transactionButton.dataset.network;
            console.log(pairingData);
            if (!pairingData) {
                await init(network);
            }

            handleTransaction(transactionWrapper);
        }); // eventlistener
    }); //foreach

    async function handleTransaction(transactionWrapper) {
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
        transactionNotices.innerText = ''; // reset

        let tinybarAmount = await getTinybarAmount(transactionWrapper);
        if (!tinybarAmount) return;
        console.log(tinybarAmount);

        let memo = transactionButton.dataset.memo;

        console.log('window Pairingdata from handletransaction:');
        console.log(window.pairingData);

        let fromAccount = AccountId.fromString(window.pairingData.accountIds[0]); // assumes paired and takes first paired account id
        const toAccount = AccountId.fromString(transactionButton.dataset.account);

        let signer = hashconnect.getSigner(fromAccount);
        let transaction = await new TransferTransaction()
            .addHbarTransfer(fromAccount, Hbar.fromTinybars(-1 * tinybarAmount)) //Sending account
            .addHbarTransfer(toAccount, Hbar.fromTinybars(tinybarAmount)) //Receiving account
            .setTransactionMemo(memo)
            .freezeWithSigner(signer);

        try {
            let response = await transaction.executeWithSigner(signer);
            console.log(response);

            const transactionId = response.transactionId;
            console.log('Transaction ID:', transactionId.toString());
            let receipt = await response.getReceiptWithSigner(signer);

            console.log(receipt);

            let woocommerceStatusDisplay = document.querySelector('.hederapay-for-woocommerce-status'); // only for woocommerce order page

            // Check if the transaction was successful
            if (receipt.status.toString() === 'SUCCESS') {
                console.log('Transaction was successful!');

                if (transactionButton.dataset.woocommerce) {
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
                if (woocommerceStatusDisplay) {
                    woocommerceStatusDisplay.innerText = woocommerceStatusDisplay.dataset.messageFailed;
                } else {
                    transactionNotices.innerText += 'Transaction failed. Please try again. ';
                }
            }
        }
        return;
    }
    window.handleTransaction = handleTransaction;

    async function getTinybarAmount(transactionWrapper) {
        let transactionInput = transactionWrapper.querySelector('.hederapay-transaction-input');
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
        transactionNotices.innerText = ''; // reset

        let currency = transactionButton.dataset.currency;
        let amount = transactionButton.dataset.amount;

        if (!amount) {
            // check for user input
            if (transactionInput) {
                if (transactionInput.classList.contains('hederapay-transaction-input')) {
                    if (transactionInput.value != '') {
                        let amountInputValue = transactionInput.value;
                        return await convertCurrencyToTinybar(amountInputValue, currency);
                    } else {
                        transactionNotices.innerText += 'Please enter the amount you wish to donate. ';
                        return null; // do nothing; amount missing
                    }
                } else {
                    console.log('Amount missing and input field missing');
                    return null; // do nothing; amount missing and input field missing
                }
            }
        } else {
            return await convertCurrencyToTinybar(amount, currency);
        }
    }

    function getNetworkId(network) {
        let networkId;
        switch (network) {
            case 'testnet':
                networkId = LedgerId.TESTNET;
                break;
            case 'previewnet':
                networkId = LedgerId.PREVIEWNET;
                break;
            case 'mainnet':
                networkId = LedgerId.MAINNET;
                break;
            default:
                networkId = LedgerId.TESTNET;
        }
        return networkId;
    }

    async function init(network) {
        let networkId = getNetworkId(network);
        // Create the hashconnect instance
        hashconnect = new HashConnect(networkId, '606201a2da45f68c8084e2eea1f14ad7', appMetadata, true);

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

        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
            window.pairingData = pairingData;
            selectedConnectButtonText.innerText = selectedConnectButton.dataset.disconnectText;
            selectedConnectButton.classList.add('is-connected');

            let id = pairingData.accountIds[0];
            let network = selectedConnectButton.dataset.network;

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
            selectedConnectButtonText.innerText = selectedConnectButton.dataset.connectText;
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

    async function convertCurrencyToTinybar(hbarAmount, currency) {
        try {
            const hbarPriceInCurrency = await getHbarPrice(currency);
            if (hbarPriceInCurrency === undefined) {
                throw new Error('Failed to retrieve HBAR price');
            }
            let amount = hbarAmount * hbarPriceInCurrency;
            amount = amount * 1e8; // convert hbar to tinybar
            console.log(amount);
            return Math.round(amount);
        } catch (error) {
            console.error('Error converting HBAR to currency:', error);
            throw error;
        }
    }

    async function test() {
        console.log('hello');
    }
    window.test = test;
})();
