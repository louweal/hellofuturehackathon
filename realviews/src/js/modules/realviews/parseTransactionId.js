export const parseTransactionId = function parseTransactionId(transactionId) {
    let splitId = transactionId.split('@');
    let accountId = splitId[0];
    let timestamp = splitId[1].replace('.', '-');
    return `${accountId}-${timestamp}`;
};

export const unparseTransactionId = function unparseTransactionId(transactionId) {
    const index = transactionId.indexOf('-');
    let newString = transactionId.slice(0, index) + '@' + transactionId.slice(index + 1);
    return newString.replace('-', '.');
};
