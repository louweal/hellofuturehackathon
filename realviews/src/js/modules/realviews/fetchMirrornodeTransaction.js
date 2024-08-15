export const fetchMirrornodeTransaction = async function fetchMirrornodeTransaction() {
    let testUrl = 'https://testnet.mirrornode.hedera.com/api/v1/transactions/0.0.4505361-1723726703-552864158';
    const restResponse = await fetch(testUrl, {
        method: 'GET',
        headers: {},
    });
    const text = await restResponse.json();
    console.log(text);
};
