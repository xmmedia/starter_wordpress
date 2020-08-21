const plugin = require('tailwindcss/plugin');

module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
    },
    experimental: {
        // https://github.com/tailwindlabs/tailwindcss/releases/tag/v1.7.0#use-apply-with-variants-and-other-complex-classes
        applyComplexClasses: true,
        // https://github.com/tailwindlabs/tailwindcss/releases/tag/v1.7.0#new-color-palette
        uniformColorPalette: true,
        // https://github.com/tailwindlabs/tailwindcss/releases/tag/v1.7.0#extended-spacing-scale
        extendedSpacingScale: true,
        // https://github.com/tailwindlabs/tailwindcss/releases/tag/v1.7.0#default-line-heights-per-font-size-by-default
        defaultLineHeights: true,
        // https://github.com/tailwindlabs/tailwindcss/releases/tag/v1.7.0#extended-font-size-scale
        extendedFontSizeScale: true,
    },
    purge: {
        content: [
            './public/app/themes/default/**/*.php',
            './public/app/themes/default/js/src/**/*.vue',
            './public/app/themes/default/js/src/**/*.js',
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
        margin: ['responsive', 'focus'],
        padding: ['responsive', 'focus'],
        opacity: ['responsive', 'hover', 'focus', 'group-hover'],
    },
    plugins: [
        plugin(function({ addComponents, config }) {
            addComponents({
                // same as: transition-all duration-300 ease-in-out
                '.transition-default': {
                    transitionProperty: config('theme.transitionProperty.all'),
                    transitionDuration: config('theme.transitionDuration.300'),
                    transitionTimingFunction: config('theme.transitionTimingFunction.in-out'),
                },
            });
        }),
    ],
};
