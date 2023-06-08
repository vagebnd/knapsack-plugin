import { resolve } from 'path'
import fg from 'fast-glob'
import _mergeWith from 'lodash.mergewith'
import { UserConfigExport } from 'vite'

export default function mergeWithSharedConfig(config: UserConfigExport) {
  return _mergeWith(
    config,
    {
      build: {
        rollupOptions: {
          input: fg.sync(resolve(__dirname, './resources/shared/assets/ts/*.ts')).sort(),
        },
      },
      resolve: {
        alias: {
          '/@shared': resolve(__dirname, './resources/shared'),
          '/@shared:plugins': resolve(__dirname, './resources/shared/assets/ts/plugins'),
          '/@shared:components': resolve(__dirname, './resources/shared/assets/ts/components'),
          '/@shared:utils': resolve(__dirname, './resources/shared/assets/ts/utils'),
          '/@shared:views': resolve(__dirname, './resources/shared/assets/ts/views'),
          '/@shared:css': resolve(__dirname, './resources/shared/assets/css'),
        },
      },
    },
    (obj: any, src: any) => {
      if (Array.isArray(obj)) {
        return obj.concat(src)
      }
    },
  )
}
