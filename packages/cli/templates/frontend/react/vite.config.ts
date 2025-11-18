import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react-swc'
import { join } from 'node:path'
import lunaPressWordPress from '@lunapress/rollup-plugin-wp'

// https://vite.dev/config/
export default defineConfig(() => {
    return {
        plugins: [react(), lunaPressWordPress()],
        build: {
            manifest: true,
            emptyOutDir: true,
            copyPublicDir: false,
            rollupOptions: {
                input: ['@module/TestNotice/index.tsx'],
            },
            output: {
                dir: '../assets/dist/vite',
            },
        },
        resolve: {
            alias: [
                {
                    find: '@',
                    replacement: join(__dirname, 'src'),
                },
                {
                    find: '@shared',
                    replacement: join(__dirname, 'src/shared'),
                },
                {
                    find: '@module',
                    replacement: join(__dirname, 'src/modules'),
                },
            ],
        },
    }
})
