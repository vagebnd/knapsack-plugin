import { defineConfig } from 'vite'
import { resolve } from 'path'
import hotfile from './resources/vite/hotfile'
import fg from 'fast-glob'
import vue from '@vitejs/plugin-vue'
import mergeWithSharedConfig from './vite.config.shared'
import dotenv from 'dotenv'

dotenv.config()

const rootPath = './resources/'

export default defineConfig(
  mergeWithSharedConfig({
    plugins: [hotfile(), vue()],
    root: rootPath,
    base: process.env.APP_ENV === 'development' ? '/' : '/public',
    build: {
      manifest: true,
      emptyOutDir: true,
      outDir: resolve(__dirname, 'public/admin/assets'),
      assetsDir: '',
      rollupOptions: {
        input: fg.sync(resolve(__dirname, './resources/admin/assets/ts/*.ts')).sort(),
      },
    },
    define: {
      __VUE_I18N_FULL_INSTALL__: true,
      __VUE_I18N_LEGACY_API__: false,
      __INTLIFY_PROD_DEVTOOLS__: false,
    },
    resolve: {
      alias: {
        '/@css': resolve(__dirname, './resources/admin/assets/css'),
        '/@admin': resolve(__dirname, './resources/admin'),
        '/@admin:blocks': resolve(__dirname, './resources/admin/assets/ts/blocks'),
        '/@admin:bootstrap': resolve(__dirname, './resources/admin/assets/ts/bootstrap'),
        '/@admin:components': resolve(__dirname, './resources/admin/assets/ts/components'),
        '/@admin:css': resolve(__dirname, './resources/admin/assets/css'),
        '/@admin:data': resolve(__dirname, './resources/admin/assets/ts/data'),
        '/@admin:layouts': resolve(__dirname, './resources/admin/assets/ts/layouts'),
        '/@admin:plugins': resolve(__dirname, './resources/admin/assets/ts/plugins'),
        '/@admin:types': resolve(__dirname, './resources/admin/assets/ts/types'),
        '/@admin:utils': resolve(__dirname, './resources/admin/assets/ts/utils'),
        '/@admin:views': resolve(__dirname, './resources/admin/assets/ts/views'),
      },
    },
  }),
)
