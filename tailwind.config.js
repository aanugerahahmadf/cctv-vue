import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: [
                    'Inter Variable',
                    'Inter',
                    'Plus Jakarta Sans',
                    'Manrope',
                    ...defaultTheme.fontFamily.sans,
                ],
                display: [
                    'Plus Jakarta Sans',
                    'Inter Variable',
                    'Inter',
                    'Manrope',
                    ...defaultTheme.fontFamily.sans,
                ],
            },
        },
    },

    plugins: [forms],
};
