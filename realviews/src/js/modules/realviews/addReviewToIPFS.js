const { create } = require('ipfs-http-client');

export const addReviewToIPFS = async function addReviewToIPFS(review) {
    try {
        // Create an IPFS client instance (default to connecting to a local IPFS node)
        const ipfs = create({ host: 'ipfs.infura.io', port: '5001', protocol: 'https' });

        // Read the JSON file
        const jsonData = review;

        // Add the file to IPFS
        const { cid } = await ipfs.add(jsonData);

        console.log('File added to IPFS with CID:', cid.toString());
    } catch (error) {
        console.error('Error adding file to IPFS:', error);
    }
};
