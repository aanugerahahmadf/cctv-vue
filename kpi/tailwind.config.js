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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['Sora', ...defaultTheme.fontFamily.sans],
            },
            screens: {
                '3xl': '1920px',
                '4k': '2560px',
                '5k': '3200px',
            },
            keyframes: {
                'fade-in': {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                'slide-up': {
                    '0%': { opacity: '0', transform: 'translateY(16px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-6px)' },
                },
                glow: {
                    '0%, 100%': { filter: 'drop-shadow(0 0 0 rgba(255,255,255,0.0))' },
                    '50%': { filter: 'drop-shadow(0 0 12px rgba(255,255,255,0.35))' },
                },
            },
            animation: {
                'fade-in': 'fade-in 600ms ease-out both',
                'slide-up': 'slide-up 700ms ease-out both',
                float: 'float 4s ease-in-out infinite',
                glow: 'glow 3s ease-in-out infinite',
            },
        },
    },

    plugins: [forms],
};
