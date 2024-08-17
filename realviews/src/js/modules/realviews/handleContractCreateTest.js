// import { ContractId, ContractExecuteTransaction, ContractFunctionParameters } from '@hashgraph/sdk';

// export const handleContractCreateTest = function handleContractCreateTest(pairingData) {
//     let createContractButton = document.querySelector('.create-contract-button');
//     if (createContractButton) {
//         createContractButton.addEventListener('click', async function () {
//             if (!pairingData) {
//                 await init('testnet');
//             }

//             const factoryContractId = ContractId.fromString('0.0.4685895');
//             console.log(factoryContractId.toString());
//             let bSeconds = 1723574410;
//             let bNanoseconds = 0;
//             let amount = 1000; // tinybar, integer!
//             let cashback = 1; // tinybar
//             let shopOwner = AccountId.fromString('0.0.4507369');
//             let iSeconds = 4;

//             let fromAccount = AccountId.fromString(pairingData.accountIds[0]);

//             let signer = hashconnect.getSigner(fromAccount);

//             //Create the transaction to deploy a new CashbackReview contract
//             let transaction = await new ContractExecuteTransaction()
//                 //Set the ID of the contract
//                 .setContractId(factoryContractId)
//                 //Set the gas for the call
//                 .setGas(2000000)
//                 //Set the function of the contract to call
//                 .setFunction(
//                     'deployCashbackReview',
//                     new ContractFunctionParameters()
//                         .addUint32(bSeconds)
//                         .addUint32(bNanoseconds)
//                         .addUint64(cashback)
//                         .addAddress(shopOwner)
//                         .addUint32(iSeconds),
//                 )
//                 .setPayableAmount(Hbar.fromTinybars(amount))
//                 .freezeWithSigner(signer);

//             let response = await transaction.executeWithSigner(signer);
//             // console.log(response);

//             //Confirm the transaction was executed successfully
//             const transactionId = response.transactionId.toString();
//             // console.log('Transaction ID:', transactionId);
//             let receipt = await response.getReceiptWithSigner(signer);
//             // console.log(receipt);
//             console.log('The transaction status is ' + receipt.status.toString());
//             if (receipt.status._code === 22) {
//                 // add transactionId to url and redirect
//                 setQueryParamAndRedirect(transactionId);
//             } else {
//                 console.log('Oops');
//             }
//         });
//     }
// };
