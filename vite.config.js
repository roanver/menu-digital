import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { createRequire } from 'module';
const require = createRequire(import.meta.url);
const tailwindcss = require('tailwindcss');
const autoprefixer = require('autoprefixer');

export default defineConfig({
    css: {
        postcss: {
            plugins: [tailwindcss, autoprefixer],
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
