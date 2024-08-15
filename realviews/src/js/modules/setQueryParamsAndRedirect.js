export const setQueryParamAndRedirect = function setQueryParamAndRedirect(transactionId) {
    let splitId = transactionId.split('@');
    let accountId = splitId[0];
    let timestamp = splitId[1].replace('.', '-');
    let urlTransactionId = `${accountId}-${timestamp}`;

    const currentUrl = new URL(window.location.href);
    currentUrl.searchParams.set('transaction_id', urlTransactionId); // Add the parameter to the URL

    // Redirect to the new URL with the additional parameter
    window.location.href = currentUrl.href;
};
