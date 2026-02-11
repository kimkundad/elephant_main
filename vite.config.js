import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'node:path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/frontend/css/app.css',
                'resources/frontend/js/app.js',
                'resources/admin/assets/plugins/custom/datatables/datatables.bundle.css',
                'resources/admin/assets/plugins/global/plugins.bundle.css',
                'resources/admin/assets/css/style.bundle.css',
                'resources/admin/assets/plugins/global/plugins.bundle.js',
                'resources/admin/assets/js/scripts.bundle.js',
                'resources/admin/assets/plugins/custom/datatables/datatables.bundle.js',
                'resources/admin/assets/plugins/custom/fslightbox/fslightbox.bundle.js',
                'resources/admin/assets/js/custom/apps/file-manager/list.js',
                'resources/admin/assets/js/widgets.bundle.js',
                'resources/admin/assets/js/custom/widgets.js',
                'resources/admin/assets/js/custom/apps/chat/chat.js',
                'resources/admin/assets/js/custom/utilities/modals/upgrade-plan.js',
                'resources/admin/assets/js/custom/utilities/modals/create-app.js',
                'resources/admin/assets/js/custom/utilities/modals/users-search.js',
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

