import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        outDir: 'assets/dist',
        assetsDir: '',
    },
    plugins: [
        laravel({
            publicDirectory: 'assets/dist',
            input: [
                'assets/src/css/theme-shoplocal.css',
                'assets/src/js/theme-shoplocal.js',
            ],
            refresh: {
                paths: [
                    './**/*.htm',
                    './**/*.block',
                    'assets/src/**/*.css',
                    'assets/src/**/*.js',
                ]
            },
        }),
    ],
});
