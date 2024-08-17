// import { arrayify, defaultAbiCoder } from 'ethers';
import { arrayify } from '@ethersproject/bytes';
import { defaultAbiCoder } from '@ethersproject/abi';

// Decode the ABI-encoded data
export const decodeHexString = function decodeHexString(hex) {
    // Create a buffer from the hex string
    const buffer = arrayify(hex);

    // Decode the string using ethers' default abi decoder
    const decoded = defaultAbiCoder.decode(
        ['string'], // Type of data to decode
        buffer,
    );

    // const decoded = defaultAbiCoder.decode(
    //     ['string'], // Type of data to decode
    //     buffer.slice(64), // Skip the first 64 bytes (32-byte offset and length for the string)
    // );

    return decoded[0];
};
