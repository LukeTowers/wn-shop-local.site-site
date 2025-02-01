import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        '../../plugins/shoplocal/core/components/**/*.htm',
        '../../plugins/*/*/blocks/*.block',
        './theme.yaml',
        './assets/src/js/**/*.{js,vue}',
        './blocks/**/*.block',
        './layouts/**/*.htm',
        './pages/**/*.htm',
        './partials/**/*.htm',
        './content/**/*.htm',
    ],
    theme: {
        extend: {
            colors: {
                primary: 'var(--primary)',
                secondary: 'var(--secondary)',
                navy: {
                    darker: '#051016',
                    dark: '#081821',
                    DEFAULT: '#103141',
                    light: '#184962',
                },
                blue: {
                    dark: '#227F96',
                    DEFAULT: '#2DA7C7',
                    light: '#48B9D5',
                },
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        forms
    ],
};
