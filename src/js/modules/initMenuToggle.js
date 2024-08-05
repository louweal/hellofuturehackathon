export const initMenuToggle = function initMenuToggle() {
    /**
     * Get the toggle element. If it is not present,
     * kill the function.
     * @type	{HTMLElement}
     */
    const menuToggle = document.querySelector(".js-toggle-menu");
    const body = document.querySelector("body");

    let menuOpen = false;

    if (!menuToggle) return false;

    /**
     * toggleMenu
     *
     * Toggles a class on the body
     *
     * @function
     * @since	1.0
     *
     * @param 	{Event} event
     */
    const toggleMenu = function toggleMenu(event) {
        if (menuOpen) {
            body.classList.remove("is-menu-open");
            menuToggle.classList.remove("is-active");
            menuOpen = false;
        } else {
            body.classList.add("is-menu-open");
            menuToggle.classList.add("is-active");
            menuOpen = true;
        }
        event.preventDefault();
    };

    // Add the toggleMenu event to the toggle
    menuToggle.addEventListener("click", toggleMenu);

    // Add event listener for esc key
    document.addEventListener(
        "keyup",
        function (event) {
            if (event.keyCode === 27 && menuOpen) {
                body.classList.remove("is-menu-open");
                menuToggle.classList.remove("is-active");
            }
        },
        false
    );

    // Add event listener for resizing screen
    window.addEventListener(
        "resize",
        function () {
            if (window.innerWidth > 1024 && menuOpen) {
                body.classList.remove("is-menu-open");
                menuToggle.classList.remove("is-active");
            }
        },
        false
    );

    // Return the toggle
    return menuToggle;
};
