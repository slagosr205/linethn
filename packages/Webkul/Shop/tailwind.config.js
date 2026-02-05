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
                navyBlue: "#0a0a0a",
                lightOrange: "#1a1a1a",
                darkGreen: '#40994A',
                darkBlue: '#0044F2',
                darkPink: '#F85156',
                gold: {
                    50:  '#FDF8ED',
                    100: '#F9EDCF',
                    200: '#F2D99E',
                    300: '#E8C06A',
                    400: '#D4AF37',
                    500: '#C6A962',
                    600: '#B8960F',
                    700: '#8B7536',
                    800: '#6B5A2A',
                    900: '#4A3E1D',
                },
                luxury: {
                    black: '#0a0a0a',
                    charcoal: '#1a1a1a',
                    dark: '#111111',
                    grey: '#2a2a2a',
                    muted: '#8a8a8a',
                    light: '#f5f0e8',
                    cream: '#FAF6EF',
                    ivory: '#FFFFF0',
                },
            },

            fontFamily: {
                poppins: ["Poppins"],
                dmserif: ["DM Serif Display"],
                playfair: ["Playfair Display", "serif"],
                cormorant: ["Cormorant Garamond", "serif"],
            },

            boxShadow: {
                'gold': '0 4px 20px rgba(212, 175, 55, 0.15)',
                'gold-lg': '0 8px 40px rgba(212, 175, 55, 0.2)',
                'luxury': '0 4px 30px rgba(0, 0, 0, 0.08)',
                'luxury-lg': '0 10px 50px rgba(0, 0, 0, 0.12)',
            },

            backgroundImage: {
                'gold-gradient': 'linear-gradient(135deg, #D4AF37 0%, #F5E6CC 50%, #D4AF37 100%)',
                'gold-shimmer': 'linear-gradient(90deg, transparent 0%, rgba(212,175,55,0.3) 50%, transparent 100%)',
                'dark-gradient': 'linear-gradient(180deg, #0a0a0a 0%, #1a1a1a 100%)',
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
