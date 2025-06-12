/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1440px",
            },

            padding: {
                DEFAULT: "90px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        extend: {
            colors: {
                'zylver-olive-green': '#556B2F',
                'zylver-gold': '#B08D57',
                'zylver-light-gold': '#D4C1A9',
                'zylver-white': '#FFFFFF',
                'zylver-cream': '#F8F6F0',
                'zylver-text-primary': '#212529',
                'zylver-text-secondary': '#495057',
                'zylver-border-grey': '#DEE2E6',
                'zylver-error': '#DC3545',
                'zylver-success': '#198754',
            },

            fontFamily: {
                lato: ["Lato", "sans-serif"],
                fraunces: ["Fraunces", "serif"],
            },
        }
    },

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
