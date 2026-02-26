/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        extend: {
            fontFamily: {
                inter: ['Inter'],
            }
        },
    },

    plugins: [],
}

