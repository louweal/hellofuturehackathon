// import { setQueryParamAndRedirect } from '../setQueryParamsAndRedirect';
import { parseTransactionId } from './parseTransactionId';

export const fetchMirrornodeTransaction = async function fetchMirrornodeTransaction(transactionId) {
    let url = 'https://testnet.mirrornode.hedera.com/api/v1/transactions/' + parseTransactionId(transactionId);
    console.log(url);

    await new Promise((resolve) => setTimeout(resolve, 11000));

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {},
        });
        const text = await response.text(); // Parse it as text
        const data = JSON.parse(text); // Try to parse the response as JSON
        // The response was a JSON object
        console.log(data);
        if (data['transactions']) {
            if (data['transactions'].length > 0) {
                for (let transaction of data['transactions']) {
                    if (transaction['name'] === 'CONTRACTCREATEINSTANCE') {
                        console.log('New contract: ' + transaction.entity_id);

                        // setQueryParamAndRedirect('contract_id', transaction.entity_id);
                        break;
                    }
                }
            }
        }
    } catch (err) {
        console.log(err);
    }
};
