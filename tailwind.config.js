import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                ink: '#2b2420',      // header/footer/dark text — charcoal
                maroon: '#8a1f2d',   // primary accent — active states, buttons
                gold: '#d4af6a',     // subtle accent — logo fallback, highlights
                cream: '#f1ede8',    // page background
                panel: '#f7f5f2',    // sidebar background
                border: '#e4ded6',   // hairline borders
                muted: '#8a7d6f',    // secondary/label text
                body: '#4a4038',     // primary body text
                amber: '#c8862b',    // status: pending
                sage: '#3f6b4a',     // status: settled
            },
        },
    },

    plugins: [forms],
};
