// Import modules.
import { initSliders } from './modules/initSliders';
import AOS from 'aos';

// Main thread
(async function () {
    'use strict';

    // Use modules
    initSliders();

    AOS.init({
        duration: 600,
        once: true,
        offset: 0,
        easing: 'ease-in-out-cubic',
    });
})();
