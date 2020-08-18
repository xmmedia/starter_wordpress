module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
    },
    purge: {
        content: [
            './public/app/themes/default/**/*.php',
            './public/app/themes/default/js/src/**/*.vue',
            './public/app/themes/default/js/src/**/*.js',
            './node_modules/vue-js-modal/dist/index.js',
            // 'node_modules/vue-multiselect/dist/vue-multiselect.min.js',
            './node_modules/flatpickr/dist/flatpickr.js',
        ],
        options: {
            whitelist: [
                // vue transition classes: https://vuejs.org/v2/guide/transitions.html#Transition-Classes
                /-enter/,
                /-leave/,
            ],
        },
    },

    theme: {
        extend: {
            colors: {
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
                'max': '1245px',
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
    variants: {
        textColor: ['responsive', 'hover', 'focus', 'group-hover'],
        borderColor: ['responsive', 'hover', 'focus', 'group-hover'],
        opacity: ['responsive', 'hover', 'focus', 'group-hover'],
    },
    plugins: [],
};
