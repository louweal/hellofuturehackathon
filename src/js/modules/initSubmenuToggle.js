export const initSubmenuToggle = function initSubmenuToggle() {
    /**
     * isMobile
     *
     * Returns a boolean of the screen width is and
     * is below a certain number
     *
     * @function
     * @since	1.0
     *
     * @returns	{Boolean}
     */
    function isMobile() {
        return window.innerWidth < 1024;
    }

    /**
     * Capture and store the current state
     * @type	{Boolean}
     */
    let mobile = false;

    /**
     * Get all the sub menu toggles
     * @type	{NodeList}
     */
    const toggles = document.querySelectorAll('.js-sub-menu-toggle');

    /**
     * getHeightOfChildren
     *
     * Get the height of all the children combined
     * in pixels.
     *
     * @function
     * @since	1.0
     *
     * @param 	{HTMLCollection} children
     * @returns	{Number}
     */
    const getHeightOfChildren = (children) =>
        Array.prototype.reduce.call(children, (acc, cur) => acc + cur.offsetHeight, 0);

    /**
     * toggleOpenClose
     *
     * Open or close the menu item
     *
     * @function
     * @since	1.0
     *
     * @param	{HTMLElement}
     */
    const toggleOpenClose = function toggleOpenClose(menu) {
        let height = getHeightOfChildren(menu.children);
        if (menu.offsetHeight === 0) {
            menu.style.height = `${height}px`;
        } else {
            menu.style.height = 0;
        }
    };

    /**
     * onToggle
     *
     * @function
     * @since	1.0
     *
     * @param 	{Event} event
     */
    const onToggle = function onToggle(event) {
        let toggle = this;
        let toggleExpanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', !toggleExpanded);
        let menu = toggle.nextElementSibling;
        if (menu) toggleOpenClose(menu);
        event.preventDefault();
    };

    /**
     * checkScreenWidth
     *
     * Add or remove the click event on the
     * toggles depending on the screen size
     *
     * @function
     * @since	1.0
     */
    const checkScreenWidth = function checkScreenWidth(event) {
        if (isMobile() && !mobile) {
            Array.prototype.forEach.call(toggles, function (toggle) {
                toggle.addEventListener('click', onToggle);
                toggle.nextElementSibling.style.height = 0;
            });
            mobile = true;
        } else if (!isMobile() && mobile) {
            Array.prototype.forEach.call(toggles, function (toggle) {
                toggle.removeEventListener('click', onToggle);
                toggle.nextElementSibling.style.height = 'auto';
            });
            mobile = false;
        }
    };

    // Add checkScreenWidth to resize event
    window.addEventListener('resize', checkScreenWidth, false);

    // Fire on load
    checkScreenWidth();

    // Return the toggles
    return toggles;
};
