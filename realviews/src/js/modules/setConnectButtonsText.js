import { decodeData } from './decodeData';

export const setConnectButtonsText = function setConnectButtonsText(attribute) {
    let connectButtons = document.querySelectorAll('.hederapay-connect-button');
    [...connectButtons].forEach((connectButton) => {
        let connectButtonData = decodeData(connectButton.dataset.attributes);
        let connectButtonText = connectButton.querySelector('.hederapay-connect-button-text');
        connectButtonText.innerText = connectButtonData[attribute];
    });
};
