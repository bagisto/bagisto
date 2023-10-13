/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                'xl': '1366px',
            },

            padding: {
                DEFAULT: '16px',
            },
        },

        screens: {
            sm: '525px',
            xl: '1366',
        },

        extend: {
            colors: {
            },

            fontFamily: {
                inter: ['Inter'],
            }
        },
    },

    plugins: [],
}

