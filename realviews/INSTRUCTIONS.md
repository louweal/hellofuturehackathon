# Instructions

Additional instructions for developers.

## Plugin zipping

Create a new plugin zip, containing all files except the `node_modules`

```
zip -r realviews.zip realviews -x "realviews/node_modules/*"
```

## Compiling contracts

Install [solc-js](https://www.npmjs.com/package/solc) globally:

```
npm install -g solc
```

Then navigate to the `/contracts` directory and compile contract `Review.sol` like this:

```
solcjs --bin Review.sol
```

## Deploying contracts

### Testnet

Rename `.env.sample` to `.env` and put in your Hedera testnet credentials (Account Id and Private Key). From the plugin root directory run:

```
node utils/deployContract.js
```

### Mainnet

Rename `.env.sample` to `.env` and put in your Hedera mainnet credentials (Account Id and Private Key). From the plugin root directory run:

```
node utils/deployContract.js mainnet
```
