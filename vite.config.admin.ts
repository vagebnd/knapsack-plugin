import { defineConfig } from 'vite'
import { resolve } from 'path'
import hotfile from './resources/vite/hotfile'
import fg from 'fast-glob'
import vue from '@vitejs/plugin-vue'

const rootPath = './resources/admin/assets/ts'
const input = fg.sync(resolve(__dirname, './resources/admin/assets/ts/*.ts')).sort()

export default defineConfig({
  plugins: [hotfile(), vue()],
  root: rootPath,
  base: process.env.APP_ENV === 'development' ? '/' : '/public',
  build: {
    manifest: true,
    emptyOutDir: true,
    outDir: resolve(__dirname, 'public/admin/assets'),
    assetsDir: '',
    rollupOptions: {
      input,
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
      '/@admin:plugins': resolve(__dirname, './resources/admin/assets/ts/plugins'),
      '/@admin:components': resolve(__dirname, './resources/admin/assets/ts/components'),
      '/@admin:layouts': resolve(__dirname, './resources/admin/assets/ts/layouts'),
      '/@admin:utils': resolve(__dirname, './resources/admin/assets/ts/utils'),
      '/@admin:views': resolve(__dirname, './resources/admin/assets/ts/views'),
    },
  },
})
