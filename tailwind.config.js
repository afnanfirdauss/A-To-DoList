import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', 'Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#F0F9F4',
                    100: '#DCF0E4',
                    200: '#B8E1C9',
                    300: '#86C9A2',
                    400: '#4DA877',
                    500: '#2F855A',
                    600: '#236946',
                    700: '#1D5338',
                    800: '#19432E',
                    900: '#153727',
                    DEFAULT: '#2F855A',
                },
                surface: '#FFFFFF',
                appbg: '#F7F8FA',
                textmain: '#20252B',
                textsec: '#6B7280',
                success: '#16A34A',
                warning: '#D97706',
                danger: '#DC2626',
            },
        },
    },

    plugins: [forms],
};
