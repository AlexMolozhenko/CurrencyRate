import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
// import './currency.js';
export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js','resources/js/currency.js'],
            refresh: true,
        }),
    ],
});
