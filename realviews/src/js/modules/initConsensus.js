import { HashConnect, HashConnectConnectionState } from 'hashconnect';
import {
    Client,
    AccountId,
    PublicKey,
    TopicCreateTransaction,
    TopicMessageQuery,
    TopicMessageSubmitTransaction,
    PrivateKey,
} from '@hashgraph/sdk';
import { LedgerId } from '@hashgraph/sdk';

export const greet = () => {
    console.log('Greeting!');
};

export const initConsensus = () => {
    let hashconnect;
    let state = HashConnectConnectionState.Disconnected;
    let pairingData;

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

    let topicButton = document.querySelector('.realviews-create-topic-button');

    if (topicButton) {
        topicButton.addEventListener('click', async function () {
            console.log('clicked');

            let network = topicButton.dataset.network;
            if (!pairingData) {
                await init(network); //connect
            } else {
                hashconnect.disconnect(); // disconnect wallet
            }

            let topicId = await createPrivateTopic(pairingData);
            console.log(topicId);
        });
    }

    async function createPrivateTopic(pairingData) {
        let fromAccount = AccountId.fromString(pairingData.accountIds[0]); // assumes paired and takes first paired account id

        let signer = hashconnect.getSigner(fromAccount);
        // let test = PrivateKey.fromString(pairingData.accountIds[0]);
        const submitKey = PrivateKey.generateED25519();

        let transaction = await new TopicCreateTransaction()
            .setTopicMemo('topic memo')
            .setTransactionMemo('transaction memo')
            .setSubmitKey(submitKey)
            .freezeWithSigner(signer);

        let response = await transaction.executeWithSigner(signer);

        // Grab the newly generated topic ID
        let receipt = await response.getReceiptWithSigner(signer);
        let topicId = receipt.topicId;
        console.log(`Your topic ID is: ${topicId}`);

        // Wait 5 seconds between consensus topic creation and subscription creation
        // await new Promise((resolve) => setTimeout(resolve, 5000));

        // // Create the topic
        // new TopicMessageQuery().setTopicId(topicId).subscribe(client, null, (message) => {
        //     let messageAsString = Buffer.from(message.contents, 'utf8').toString();
        //     console.log(`${message.consensusTimestamp.toDate()} Received: ${messageAsString}`);
        // });

        return topicId;
    }

    async function submitPrivateMessage(topicId) {
        // Send message to private topic
        let submitMsgTx = await new TopicMessageSubmitTransaction({
            topicId: topicId,
            message: 'Submitkey set!',
        })
            .freezeWith(client)
            .sign(myPrivateKey);

        let submitMsgTxSubmit = await submitMsgTx.execute(client);
        let getReceipt = await submitMsgTxSubmit.getReceipt(client);

        // Get the status of the transaction
        const transactionStatus = getReceipt.status;
        console.log('The message transaction status: ' + transactionStatus.toString());
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
        hashconnect.pairingEvent.on((newPairing) => {
            pairingData = newPairing;
            // let id = pairingData.accountIds[0];
        });

        hashconnect.disconnectionEvent.on(() => {
            pairingData = null;
        });

        hashconnect.connectionStatusChangeEvent.on((connectionStatus) => {
            state = connectionStatus;
        });
    }
};
