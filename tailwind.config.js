const plugin = require('tailwindcss/plugin');
const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    content: [
        './public/app/themes/default/**/*.{vue,js,php}',
    ],
    safelist: [
        // vue transition classes: https://vuejs.org/v2/guide/transitions.html#Transition-Classes
        '.md-enter-active',
        '.md-leave-active',
        '.md-enter',
        '.md-leave-active',
        {
            pattern: /^(m|p)(t|b|r|l|x|y)?-(auto|0|1|2|3|4|6|8|10|12|16|24|28|36|44|48)$/,
            variants: ['md', 'lg', 'xl'],
        },
        {
            pattern: /^max-w-[a-z0-9-]+$/,
            variants: ['md', 'lg'],
        },
        // {
        //     pattern: /^grid-cols-(3|4|5)$/,
        //     variants: ['md', 'lg'],
        // },
    ],
    theme: {
        screens: {
            'xs': '400px',
            // sm: '640px',
            // md: '768px',
            // lg: '1024px',
            // xl: '1280px',
            // '2xl': '1536px',
            ...defaultTheme.screens,
            '3xl': '1900px',
            'print': { 'raw': 'print' },
            'retina': { 'raw': '(-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi)' },
        },
        extend: {
            colors: {
                'black-transparent' : 'rgba(0,0,0,0.4)',
                'white-transparent' : 'rgba(255,255,255,0.6)',
                'white-transparent-dark' : 'rgba(255,255,255,0.8)',
            },
            borderWidth: {
                '10': '10px',
            },
            maxWidth: {
                '1/2': '50%',
                '3/5': '60%',
                '11/12': '91%',
            },
            height: {
                '120': '30rem',
                '128': '32rem',
            },
            fontFamily: {
                'headings': [
                    '"Helvetica Neue"',
                    'Arial',
                    // see https://tailwindcss.com/docs/font-family for list
                    ...defaultTheme.fontFamily.sans,
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
                    transitionTimingFunction: theme('transitionTimingFunction.in-out'),
                },
            });
        }),
    ],
};
