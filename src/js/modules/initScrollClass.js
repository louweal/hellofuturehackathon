export const initScrollClass = function initScrollClass() {
    // Class to add to the target
    const scrollClass = 'has-scrolled';

    // Threshold for the scrollTop to trigger on
    const offsetThreshold = 50;

    let scrolled = false;

    // Last scrollled position
    let lastScrollTopPosition = document.body.scrollTop || document.documentElement.scrollTop;

    const scrollToggle = () => {
        const top = document.body.scrollTop || document.documentElement.scrollTop;
        if (top >= offsetThreshold) {
            if (!scrolled) {
                document.body.classList.add(scrollClass);
                scrolled = true;
            }
        } else {
            if (scrolled) {
                document.body.classList.remove(scrollClass);
                scrolled = false;
            }
        }
        lastScrollTopPosition = top;
    };

    document.addEventListener('scroll', scrollToggle, false);
};
