import { decodeHexString } from './decodeHexString';
import { parseTransactionId } from './parseTransactionId';

export const fetchMirrornodeLogData = async function fetchMirrornodeLogData(transactionId) {
    let networks = ['testnet', 'mainnet', 'previewnet'];
    // console.log('fetch');

    for (let network of networks) {
        let url = `https://${network}.mirrornode.hedera.com/api/v1/contracts/results/${parseTransactionId(transactionId)}`;

        try {
            const response = await fetch(url, {
                method: 'GET',
                headers: {},
            });
            const text = await response.text(); // Parse it as text
            const data = JSON.parse(text); // Try to parse the response as JSON
            // The response was a JSON object

            let logs = data['logs'];
            if (logs) {
                for (let log of logs) {
                    let hexData = log['data'];
                    if (hexData) {
                        let decodedData = await decodeHexString(hexData);
                        return { network, reviewData: decodedData }; // just returns the first log
                    }
                }
            }
        } catch (err) {
            console.log(err);
            return null;
        }
    }
};
