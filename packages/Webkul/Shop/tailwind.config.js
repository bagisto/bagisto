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
                pelorous: {
                    '50': '#ebffff',
                    '100': '#cefcff',
                    '200': '#a2f8ff',
                    '300': '#63effd',
                    '400': '#1cddf4',
                    '500': '#00b4cc',
                    '600': '#0399b7',
                    '700': '#0a7a94',
                    '800': '#126278',
                    '850': '#126278',
                    '900': '#145165',
                    '950': '#063646',
                },


                baseBlue: "#0099cc",
                primary: "#00b4cc",
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156'
            },

            fontFamily: {
                poppins: ['Poppins', 'sans-serif'],
                dmserif: ['Poppins'],
            }
        }
    },

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
