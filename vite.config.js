import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'node:path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/frontend_v2.css',
                'resources/js/app.js',
                'resources/js/frontend_v2.js',
                'resources/frontend/css/app.css',
                'resources/frontend/js/app.js',
                'resources/admin/css/app.css',
                'resources/admin/js/app.js',
            ],
            refresh: true,
        }),
    ],
    build: {
        rollupOptions: {
            output: {
                assetFileNames: (assetInfo) => {
                    const name = assetInfo.name ? assetInfo.name.replace(/\\/g, '/') : '';
                    if (name.startsWith('resources/admin/')) {
                        const rel = path.relative('resources/admin', name).replace(/\\/g, '/');
                        return `admin/${rel}`;
                    }
                    return 'assets/[name]-[hash][extname]';
                },
            },
        },
    },
});

