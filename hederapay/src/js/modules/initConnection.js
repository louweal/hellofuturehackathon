import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { LedgerId } from '@hashgraph/sdk';

export const initConnection = () => {
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

    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    let clickedConnectButton;

    [...connectButtons].forEach((connectButton) => {
        connectButton.addEventListener('click', async function () {
            clickedConnectButton = connectButton;
            let network = connectButton.dataset.network;
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
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
        transactionNotices.innerText = ''; // reset

        transactionButton.addEventListener('click', async function () {
            let tinybarAmount = transactionButton.dataset.tinybarAmount;
            // console.log(tinybarAmount);

            if (!tinybarAmount) {
                // check for user input
                if (transactionInput) {
                    if (transactionInput.classList.contains('hederapay-transaction-input')) {
                        console.log('found!');
                        if (transactionInput.value != '') {
                            let currency = transactionButton.dataset.currency;
                            let amount = transactionInput.value;
                            // console.log(amount);
                            tinybarAmount = await convertCurrencyToTinybar(amount, currency);
                            // console.log(tinybarAmount);
                        } else {
                            console.log('empty value');
                            transactionNotices.innerText += 'Please enter the amount you wish to donate.';
                            return; // do nothing; amount missing
                        }
                    } else {
                        console.log('amount missing and input field missing');
                        return; // do nothing; amount missing and input field missing
                    }
                }
            }

            let memo = transactionButton.dataset.memo;
            let network = transactionButton.dataset.network;

            console.log(state);
            if (state != HashConnectConnectionState.Connected) {
                await init(network);
            }

            let fromAccount = AccountId.fromString(pairingData.accountIds[0]); // assumes paired and takes first paired account id
            const toAccount = AccountId.fromString(transactionButton.dataset.account);

            let signer = hashconnect.getSigner(fromAccount);
            let transaction = await new TransferTransaction()
                .addHbarTransfer(fromAccount, Hbar.fromTinybars(-1 * tinybarAmount)) //Sending account
                .addHbarTransfer(toAccount, Hbar.fromTinybars(tinybarAmount)) //Receiving account
                .setTransactionMemo(memo)
                .freezeWithSigner(signer);

            let response = await transaction.executeWithSigner(signer);
            console.log(response);

            const transactionId = response.transactionId;
            console.log('Transaction ID:', transactionId.toString());
            let receipt = await response.getReceiptWithSigner(signer);

            console.log(receipt);
            // Check if the transaction was successful
            if (receipt.status.toString() === 'SUCCESS') {
                console.log('Transaction was successful!');
                transactionButton.setAttribute('data-success', '');
                transactionButton.setAttribute('data-transaction-id', transactionId.toString());

                console.log(transactionButton.dataset.woocommerce);

                if (transactionButton.dataset.woocommerce) {
                    const currentUrl = new URL(window.location.href);
                    // Add the parameter to the URL
                    currentUrl.searchParams.set('transaction', 'success');
                    // Redirect to the new URL with the additional parameter
                    window.location.href = currentUrl.href;
                }
            } else {
                console.log(`Transaction failed with status: ${receipt.status}`);
            }
        }); // eventlistener
    }); //foreach

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

        // Register events
        setUpHashConnectEvents();

        // Initialize
        await hashconnect.init();

        // Open pairing modal
        if (!pairingData) {
            hashconnect.openPairingModal();
        }
    }

    function setUpHashConnectEvents() {
        // let connectButtons = document.querySelectorAll('.hederapay-connect-button');
        let pairedAccountDisplays = document.querySelectorAll('.hederapay-paired-account');

        let selectedConnectButton = clickedConnectButton || document.querySelector('.hederapay-connect-button');
        let selectedConnectButtonText = selectedConnectButton.querySelector('.hederapay-connect-button-text');

        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
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
};
