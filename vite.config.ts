import { defineConfig } from "vite";
import { resolve } from 'path'
import hotfile from './resources/vite/hotfile'
import fg from 'fast-glob'

const rootPath = './resources/assets/ts'
const input = fg.sync(resolve(__dirname, './resources/assets/ts/*.ts')).sort()

export default defineConfig({
    plugins: [hotfile()],
    root: rootPath,
    base: process.env.APP_ENV === 'development'
        ? '/'
        : '/public',
    build: {
        manifest: true,
        emptyOutDir: true,
        outDir: resolve(__dirname, 'public/assets'),
        assetsDir: '',
        rollupOptions: {
            input,
        }
    },
    resolve: {
        alias: {
            '/@css': resolve(__dirname, './resources/assets/css'),
            '/@': resolve(__dirname, './resources/assets/ts'),
        }
    }
})
