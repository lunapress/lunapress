import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react-swc'
import { join } from 'node:path'
import externalGlobals, { type ModuleNameMap } from 'rollup-plugin-external-globals'

const externalGlobalsOnlyProd: ModuleNameMap = {
    react: 'window.React',
}

// https://vite.dev/config/
export default defineConfig(({ mode }) => {
    return {
        plugins: [
            react(),
            externalGlobals({
                jquery: 'window.jQuery',
                'lodash-es': 'window.lodash',
                lodash: 'window.lodash',
                moment: 'window.moment',
                'react-dom': 'window.ReactDOM',
                react: 'window.React',

                '@wordpress/hooks': 'wp.hooks',
                '@wordpress/element': 'wp.element',
                '@wordpress/blocks': 'wp.blocks',
                '@wordpress/data': 'wp.data',
                '@wordpress/api-fetch': 'wp.apiFetch',
                '@wordpress/components': 'wp.components',
                '@wordpress/warning': 'wp.warning',
                '@wordpress/compose': 'wp.compose',
                '@wordpress/i18n': 'wp.i18n',

                '@carbon-fields/core': 'cf.core',
                '@carbon-fields/hooks': 'cf.hooks',
                '@carbon-fields/@wordpress/element': "cf.vendor['@wordpress/element']",

                ...(mode !== 'development' ? externalGlobalsOnlyProd : {}),
            }),
        ],
        server: {
            cors: true,
        },
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
        optimizeDeps: {
            exclude: ['react', 'react-dom'],
        },
    }
})
