const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "header.php",
        "footer.php",
        "index.php",
        "singular.php",
        "404.php",
        "./template-parts/*.php",
        "./template-parts/*/*.php",
        "./lib/ajax.php",
    ],
    theme: {
        container: {
            center: true,
            padding: "1rem",
            screens: {
                sm: "100%",
                md: "100%",
                lg: "100%",
                xl: "75rem",
            },
        },
        screens: {
            xs: "480px",
            ...defaultTheme.screens,
        },
        extend: {},
    },
    plugins: [],
};
