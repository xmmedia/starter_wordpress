const plugin = require('tailwindcss/plugin');

module.exports = {
    // https://tailwindcss.com/docs/upcoming-changes

    content: [
        './public/app/themes/default/**/*.php',
        './public/app/themes/default/js/src/**/*.js',
    ],
    options: {
        safelist: [
            // vue transition classes: https://vuejs.org/v2/guide/transitions.html#Transition-Classes
            /-enter/,
            /-leave/,
        ],
    },
    theme: {
        extend: {
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                'inherit': 'inherit',
            },
            borderWidth: {
                '10': '10px',
            },
            maxWidth: {
                '1/2': '50%',
                '3/5': '60%',
                '11/12': '91%',
            },
            screens: {
                'xs': '400px',
                'print': { 'raw': 'print' },
                'retina': { 'raw': '(-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi)' },
            },
            fontFamily: {
                'headings': [
                    '"Helvetica Neue"',
                    'Arial',
                    'sans-serif',
                    '"Apple Color Emoji"',
                    '"Segoe UI Emoji"',
                    '"Segoe UI Symbol"',
                    '"Noto Color Emoji"',
                ],
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        plugin(({ addBase, theme }) => {
            addBase({
                // same as: transition-all duration-300 ease-in-out
                '.transition-default': {
                    transitionProperty: theme('transitionProperty.all'),
                    transitionDuration: theme('transitionDuration.300'),
                    transitionTimingFunction: theme('transitionTimingFunction.in-out'),},
            });
        }),
    ],
};
