import { SkeletonApp } from './elementor'

declare module '*.vue' {
  import { defineComponent } from 'vue'
  const component: ReturnType<typeof defineComponent>
  export default component
}

declare module '*.json' {
  const value: any
  export default value
}

declare module '@sipec/vue3-tags-input'

declare global {
  var wp: any
  var elementor: any
  var $e: any
  var elementorFrontend: any
  var $skeletonApp: SkeletonApp
}

interface Window {
  $skeletonApp: {
    openSaveModal: () => void
  }
}

interface ImportMetaEnv {
  readonly VITE_THEME_MANAGER_API_URL: string
  readonly VITE_THEME_MANAGER_API_KEY: string
  readonly VITE_THEME_MANAGER_THEME_ID: string
  readonly VITE_IS_BUILD: string
}
interface ImportMeta {
  readonly env: ImportMetaEnv
}
