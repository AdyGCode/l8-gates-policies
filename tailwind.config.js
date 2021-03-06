const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')


module.exports = {
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
        colors: {
            // Build your palette here
            transparent: 'transparent',
            current: 'currentColor',
            white: colors.white,
            black: colors.black,
            blueGray: colors.blueGray,
            coolGray: colors.coolGray,
            gray: colors.gray,
            trueGray: colors.trueGray,
            warmGray: colors.warmGray,
            red: colors.red,
            orange: colors.orange,
            amber: colors.amber,
            yellow: colors.yellow,
            lime: colors.lime,
            green: colors.green,
            emerald: colors.emerald,
            teal: colors.teal,
            cyan: colors.cyan,
            sky: colors.sky,
            blue: colors.blue,
            indigo: colors.indigo,
            violet: colors.violet,
            purple: colors.purple,
            fuchsia: colors.fuchsia,
            pink: colors.pink,
            rose: colors.rose,

            /* Alternative names for some colours */
            primary: colors.blue,
            secondary: colors.purple,
            tertiary: colors.gray,
            danger: colors.red,
            warning: colors.amber,
            info: colors.blueGray,
            success: colors.green,

        },

        linearGradientColors: theme => theme('colors'),
        radialGradientColors: theme => theme('colors'),
        conicGradientColors: theme => theme('colors'),

        opacity: {
            '0': '0',
            '10': '.1',
            '20': '.2',
            '25': '.25',
            '30': '.3',
            '40': '.4',
            '50': '.5',
            '60': '.6',
            '70': '.7',
            '75': '.75',
            '80': '.8',
            '90': '.9',
            '100': '1',
        }
    },

    variants: {
        extend: {
            opacity: ['active',],
            filter: ['hover', 'focus', 'active',],
            blur: ['hover', 'focus', 'active',],
        },
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/aspect-ratio'),

        require('tailwindcss-gradients'),
        require('tailwindcss-elevation')(['responsive']),

    ],
};
