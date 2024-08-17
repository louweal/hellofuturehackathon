import { AccountId, ContractId, ContractExecuteTransaction, ContractFunctionParameters } from '@hashgraph/sdk';

export const deployReviewContract = async function deployReviewContract(data) {
    const factoryContractId = ContractId.fromString('0.0.4686716');
    console.log(factoryContractId.toString());

    // console.log('pairingData :>> ', pairingData);

    let localAccountId = localStorage.getItem('accountId');
    console.log('localAccountId :>> ', localAccountId);

    let fromAccount = AccountId.fromString(localAccountId);
    let signer = hashconnect.getSigner(fromAccount);

    //Create the transaction to deploy a new CashbackReview contract
    let transaction = await new ContractExecuteTransaction()
        //Set the ID of the contract
        .setContractId(factoryContractId)
        //Set the gas for the call
        .setGas(2000000)
        //Set the function of the contract to call
        .setFunction('deployReview', new ContractFunctionParameters().addString(data))
        .freezeWithSigner(signer);

    let response = await transaction.executeWithSigner(signer);
    // console.log(response);

    //Confirm the transaction was executed successfully
    const transactionId = response.transactionId.toString();
    // console.log('Transaction ID:', transactionId);
    let receipt = await response.getReceiptWithSigner(signer);
    // console.log(receipt);
    console.log('The transaction status is ' + receipt.status.toString());
    if (receipt.status._code === 22) {
        // add transactionId to url and redirect
        setQueryParamAndRedirect(transactionId);
    } else {
        console.log('Oops');
    }
};
