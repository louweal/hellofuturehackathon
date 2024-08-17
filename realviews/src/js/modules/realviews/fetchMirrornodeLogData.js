import { decodeHexString } from './decodeHexString';
import { parseTransactionId } from './parseTransactionId';

export const fetchMirrornodeLogData = async function fetchMirrornodeLogData(transactionId) {
    let url = 'https://testnet.mirrornode.hedera.com/api/v1/contracts/results/' + parseTransactionId(transactionId);
    // let url =
    //     'https://testnet.mirrornode.hedera.com/api/v1/contracts/results/' +
    //     parseTransactionId('0.0.4505361@1723884935.870049393');

    console.log(url);

    // await new Promise((resolve) => setTimeout(resolve, 11000));

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {},
        });
        const text = await response.text(); // Parse it as text
        const data = JSON.parse(text); // Try to parse the response as JSON
        // The response was a JSON object
        // console.log(data);
        let logs = data['logs'];

        for (let log of logs) {
            let hexData = log['data'];
            if (hexData) {
                console.log(hexData);
                let decodedData = await decodeHexString(hexData);
                // console.log(decodedData);
                return decodedData; // just returns the first log
            }
        }
    } catch (err) {
        console.log(err);
        return null;
    }
};
