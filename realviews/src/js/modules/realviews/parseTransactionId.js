export const parseTransactionId = function parseTransactionId(transactionId) {
    let splitId = transactionId.split('@');
    let accountId = splitId[0];
    let timestamp = splitId[1].replace('.', '-');
    return `${accountId}-${timestamp}`;
};
