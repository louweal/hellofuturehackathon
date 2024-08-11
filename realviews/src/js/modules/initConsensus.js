const {
    AccountId,
    PrivateKey,
    Client,
    TopicCreateTransaction,
    TopicMessageQuery,
    TopicMessageSubmitTransaction,
} = require('@hashgraph/sdk');
require('dotenv').config();

export const initConsensus = () => {
    async function createPrivateTopic() {
        const myAccountId = process.env.MY_ACCOUNT_ID;
        const myPrivateKey = process.env.MY_PRIVATE_KEY;

        if (!myAccountId || !myPrivateKey) {
            throw new Error('Environment variables MY_ACCOUNT_ID and MY_PRIVATE_KEY must be present');
        }

        const client = Client.forTestnet();
        client.setOperator(myAccountId, myPrivateKey);

        // Create a new topic
        let txResponse = await new TopicCreateTransaction().setSubmitKey(myPrivateKey.publicKey).execute(client);

        // Grab the newly generated topic ID
        let receipt = await txResponse.getReceipt(client);
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
};
