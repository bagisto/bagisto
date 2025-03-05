/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1350px",
            },

            padding: {
                DEFAULT: "30px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1350px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        extend: {
            colors: {
                primary: "#0F58F4",
                secondary: "#152F54",
                heading: "#191F33",
                body: "#555555",
                line: "#EBEDF5",
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
            },
            fontSize: {
                base: '14px',
            },
            fontFamily: {
                manrope: ["Manrope"],
            },
            boxShadow: {
                'custom': '0 0 20px 0 rgba(0,0,0,.12)'
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
