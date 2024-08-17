export const getCashbackContractId = async function getCashbackContractId(transactionId) {
    let splitId = transactionId.split('@');
    let accountId = splitId[0];
    let timestamp = splitId[1].replace('.', '-');
    let url = `https://testnet.mirrornode.hedera.com/api/v1/transactions/${accountId}-${timestamp}`;
    console.log(url);
    const restResponse = await fetch(url, {
        method: 'GET',
        headers: {},
    });
    const text = await restResponse.json();
    console.log(text);

    try {
        const data = JSON.parse(text); // Try to parse the response as JSON
        // The response was a JSON object
        // Do your JSON handling here
        console.log(data);
        if (data.transactions) {
            if (data.transactions.length > 0) {
                let transactions = data.transaction;
                for (let transaction of transactions) {
                    if (transaction.name === 'CONTRACTCREATEINSTANCE') {
                        console.log('New contract: ' + transaction.entity_id);
                        break;
                    }
                }
            }
        }
    } catch (err) {
        // The response wasn't a JSON object
        console.log(err);
        console.log(text);
    }
};
