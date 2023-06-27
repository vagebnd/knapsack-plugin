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

declare var wp: any
declare var elementor: any
declare var $e: any
declare var elementorFrontend: any

interface Window {
  $skeletonApp: {
    openSaveModal: () => void
  }
}
