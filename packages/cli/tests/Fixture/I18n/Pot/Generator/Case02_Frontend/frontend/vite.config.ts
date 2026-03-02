import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react-swc'
import { join } from 'node:path'
import lunaPressWordPress from '@lunapress/rollup-plugin-wp'

// https://vite.dev/config/
export default defineConfig({
    plugins: [react(), lunaPressWordPress()],
    build: {
        rollupOptions: {
            input: ['@/index.ts'],
        },
        outDir: '../frontend/assets/dist/vite',
    },
    resolve: {
        alias: [
            {
                find: '@',
                replacement: join(__dirname, 'src'),
            }
        ],
    },
})