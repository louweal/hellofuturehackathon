import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import { LedgerId } from '@hashgraph/sdk';
import { TransferTransaction, Hbar, AccountId } from '@hashgraph/sdk';

export const initConnection = () => {
    const appMetadata = {
        name: 'WOO Shop',
        description: 'WOO Shop',
        icons: ['https://comwoo-karakimse.savviihq.com/wp-content/uploads/2024/07/cropped-favicon.png'],
        url: 'https://comwoo-karakimse.savviihq.com/',
    };

    let hashconnect;
    let state = HashConnectConnectionState.Disconnected;
    let pairingData;

    let connectButton = document.querySelector('.hederapay-connect-button');
    let walletId = document.querySelector('#hederapay-output');

    if (connectButton) {
        connectButton.addEventListener('click', async function () {
            let network = connectButton.dataset.network;

            if (!pairingData) {
                //connect
                await init();
            } else {
                // disconnect wallet
                hashconnect.disconnect();
            }
        });
    }

    let payButtons = document.querySelectorAll('.hederapay-transaction-button');
    console.log(payButtons);

    [...payButtons].forEach((payButton) => {
        payButton.addEventListener('click', async function () {
            let tinyBars = payButton.dataset.tinybarAmount;
            let network = payButton.dataset.network;

            console.log(tinyBars);

            if (!pairingData) {
                await init();
            }

            let amount = payButton.dataset.hbar;

            let fromAccount = AccountId.fromString(pairingData.accountIds[0]); // assumes paired and takes first paired account id
            const toAccount = AccountId.fromString(payButton.dataset.receiver); //'0.0.4507369';

            let signer = hashconnect.getSigner(fromAccount);
            let transaction = await new TransferTransaction()
                .addHbarTransfer(fromAccount, Hbar.fromTinybars(-1 * amount)) //Sending account
                .addHbarTransfer(toAccount, Hbar.fromTinybars(amount)) //Receiving account
                .freezeWithSigner(signer);
            // let response = await hashconnect.sendTransaction(fromAccount, transaction);

            let response = await transaction.executeWithSigner(signer);
            console.log(response);

            const transactionId = response.transactionId;
            console.log('Transaction ID:', transactionId.toString());
            let receipt = await response.getReceiptWithSigner(signer);

            console.log(receipt);
        }); // eventlistener
    }); //foreach

    async function init() {
        // Create the hashconnect instance
        hashconnect = new HashConnect(LedgerId.TESTNET, '606201a2da45f68c8084e2eea1f14ad7', appMetadata, true);

        // Register events
        setUpHashConnectEvents();

        // Initialize
        await hashconnect.init();

        // if (pairingData) {
        //     console.log('Already paired with wallet.');
        //     return;
        // }

        // Open pairing modal
        hashconnect.openPairingModal();
    }

    function setUpHashConnectEvents() {
        let connectButton = document.querySelector('.hederapay-connect-button');
        let walletId = document.querySelector('#hederapay-output');

        console.log(connectButton);

        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
            connectButton.innerText = 'Disconnect wallet';
            if (walletId) walletId.innerText = pairingData.accountIds[0];
        });

        hashconnect.disconnectionEvent.on(() => {
            pairingData = null;
            connectButton.innerText = 'Connect wallet';
            if (walletId) walletId.innerText = '';
        });

        hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            state = connectionStatus;
        });
    }

    // function sendTransaction(accountId, transaction) {
    //     hashconnect
    //         .sendTransaction(accountId, transaction)
    //         .then((response) => {
    //             // Handle success
    //             console.log('handle success');
    //         })
    //         .catch((err) => {
    //             // Handle error
    //             console.log('handle error');
    //         });
    // }
};
