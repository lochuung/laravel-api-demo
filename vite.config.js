import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/pages/users/show.js',
                'resources/js/pages/products-index.js',
                'resources/js/pages/products-create.js',
                'resources/js/pages/products-edit.js'
            ],
            refresh: true,
        }),
        tailwindcss()
    ],
});
