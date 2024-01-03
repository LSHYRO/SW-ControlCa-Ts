import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { liveDesigner } from '@pinegrow/vite-plugin'

export default defineConfig({
    plugins: [
        liveDesigner({
            tailwindcss: {
                /* Please ensure that you update the filenames and paths to accurately match those used in your project. */
                configPath: 'tailwind.config.js',
                cssPath: '@/assets/css/tailwind.css',
                // themePath: false, // Set to false so that Design Panel is not used
                // restartOnConfigUpdate: true,
                restartOnThemeUpdate: true,
              },
              devServerUrls: {
<<<<<<< HEAD
                local: "http://192.168.4.22:8000/",
=======
                local: "http://192.168.1.72:8000/",
>>>>>>> 5bd64cc70686f02646073d84d6e4c18ca682cfda
              }
            //... 
        }),
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
<<<<<<< HEAD
        host: '192.168.4.22',
=======
        host: '192.168.1.72',
>>>>>>> 5bd64cc70686f02646073d84d6e4c18ca682cfda
        port: 5173,
    }
});
