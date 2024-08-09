import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';
import { LedgerId } from '@hashgraph/sdk';

export const initConnection = () => {
    let metadata = document.querySelector('#hederapay-app-metadata');

    console.log(metadata);

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

    let connectButton = document.querySelector('.hederapay-connect-button');

    if (connectButton) {
        connectButton.addEventListener('click', async function () {
            let network = connectButton.dataset.network;
            if (!pairingData) {
                await init(network); //connect
            } else {
                hashconnect.disconnect(); // disconnect wallet
            }
        });
    }

    let transactionWrappers = document.querySelectorAll('.hederapay-transaction-wrapper');

    [...transactionWrappers].forEach((transactionWrapper) => {
        let transactionInput = transactionWrapper.querySelector('.hederapay-transaction-input');
        let transactionButton = transactionWrapper.querySelector('.hederapay-transaction-button');
        let transactionNotices = transactionWrapper.querySelector('.hederapay-transaction-notices');
        transactionNotices.innerText = ''; // reset

        transactionButton.addEventListener('click', async function () {
            let tinybarAmount = transactionButton.dataset.tinybarAmount;

            console.log(tinybarAmount);

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

            if (!pairingData) {
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
        hashconnect.openPairingModal();
    }

    function setUpHashConnectEvents() {
        let connectButton = document.querySelector('.hederapay-connect-button');
        let pairedAccount = document.querySelector('#hederapay-paired-account');

        console.log(connectButton);

        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
            connectButton.innerText = connectButton.dataset.disconnectText;
            if (pairedAccount) {
                pairedAccount.innerText = pairingData.accountIds[0];
                pairedAccount.style.display = 'inline';
            }
        });

        hashconnect.disconnectionEvent.on(() => {
            pairingData = null;
            connectButton.innerText = connectButton.dataset.connectText; // test
            if (pairedAccount) {
                pairedAccount.innerText = '';
                pairedAccount.style.display = 'none';
            }
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
