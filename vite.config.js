import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/assets/*',
                    dest: 'assets'
                },
                {
                    src: 'resources/css/responsive.css',
                    dest: 'css'
                },
                {
                    src: 'resources/css/style-main.css',
                    dest: 'css'
                },
                {
                    src: 'resources/js/*',
                    dest: 'js'
                },
                {
                    src: 'resources/img/*',
                    dest: 'img'
                },
            ]
        })
    ],
});
