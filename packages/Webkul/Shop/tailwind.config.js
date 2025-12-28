/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',

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
                navyBlue: "#060C3B",
                lightOrange: "#F6F2EB",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
                // RAM Plaza colors (aligned with RAM Visual Identity)
                ram: {
                    pink: "#ff3e9a",
                    "pink-hover": "#ff66b6",
                    blue: "#4A90E2",
                    // Backgrounds (warm blue-gray tones)
                    dark: "#1E252B",
                    surface: "#262D34",
                    card: "#262D34",
                    input: "#2d353d",
                    hover: "#323b44",
                    // Text
                    "text-primary": "#ffffff",
                    "text-secondary": "#a0aab4",
                    "text-muted": "#6b7280",
                    // Borders
                    border: "#3a4249",
                },
            },

            fontFamily: {
                poppins: ["Poppins", "sans-serif"],
                dmserif: ["DM Serif Display", "serif"],
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
