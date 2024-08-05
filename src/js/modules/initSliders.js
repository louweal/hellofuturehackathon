import Swiper, { Navigation, Pagination, Autoplay, EffectFade } from 'swiper';

export const initSliders = () => {
    // Check if Swiper is loaded in
    if (!Swiper) return false;

    // Get all sliders
    const sliders = document.querySelectorAll('.js-slider');

    if (sliders) {
        [...sliders].forEach((slider) => {
            const type = slider.hasAttribute('data-slider') ? slider.getAttribute('data-slider') : 'default';

            const options = {
                home: {
                    modules: [Autoplay, EffectFade],
                    direction: 'horizontal',
                    effect: 'slide',
                    slidesPerView: 'auto',
                    spaceBetween: 0,
                    speed: 2000,
                    touchReleaseOnEdges: true,
                    wrapperClass: 'slider__wrapper',
                    slideClass: 'slider__slide',
                    loop: true,
                    autoplay: {
                        delay: 4000,
                    },
                    on: {
                        init: function () {
                            slider.classList.add('slider--init');
                        },
                    },
                },

                products: {
                    direction: 'horizontal',
                    effect: 'slide',
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                    speed: 2000,
                    touchReleaseOnEdges: true,
                    watchSlidesProgress: true,
                    wrapperClass: 'slider__wrapper',
                    slideClass: 'slider__slide',
                    breakpoints: {
                        1400: {
                            slidesPerView: 3,
                            spaceBetween: 30,
                            allowTouchMove: false,
                        },
                    },
                    on: {
                        init: function () {
                            slider.classList.add('slider--init');
                        },
                    },
                },
            };

            const swiper = new Swiper(slider, options[type]);
        });
    }
};
