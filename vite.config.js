import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/auth.css',
                'resources/css/admin.css',
                'resources/css/app.css',
                'resources/css/docs.css',
                'resources/js/admin.js',
                'resources/js/app.js',
                // 'resources/js/bootstrap.js',
                'resources/js/docs.js',
                'resources/js/statistics.js',
            ],
            refresh: true,
        }),
    ],
});
