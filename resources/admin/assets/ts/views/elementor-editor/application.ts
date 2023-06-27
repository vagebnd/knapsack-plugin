import '/@admin:css/tailwind.css'

import { ComponentPublicInstance, createApp } from 'vue'
import App from './components/App.vue'

interface VueInstance extends ComponentPublicInstance {
  openLibrary: () => void
  openSaveModal: () => void
}

class Application {
  public vueInstance!: VueInstance

  constructor() {
    this.initApplication()
    this.addTailwindClass()
    this.addBlocksButton()
  }

  addBlocksButton() {
    const template = document.getElementById('tmpl-elementor-add-section')

    if (!template) {
      return
    }
    const text = template.innerHTML.replace(
      '<div class="elementor-add-section-drag-title',
      '<div class="elementor-add-section-area-button elementor-add-skeleton-blocks-button" title="TheSkeleton Blocks"><i class="eicon-folder"></i></div><div class="elementor-add-section-drag-title',
    )

    template.innerHTML = text

    elementor.on('preview:loaded', () => {
      elementor.$previewContents[0].body.addEventListener('click', (e: MouseEvent) => {
        if ((e.target as HTMLElement).closest('.elementor-add-skeleton-blocks-button')) {
          e.preventDefault()
          e.stopPropagation()
          this.vueInstance.openLibrary()
        }
      })
    })
  }

  initApplication() {
    const element = document.createElement('div')
    element.id = 'skeleton-component-library'
    document.body.appendChild(element)

    const app = createApp(App)
    this.vueInstance = app.mount(`#${element.id}`) as VueInstance
  }

  addTailwindClass() {
    document.body.classList.add('tailwind')
  }

  openSaveModal() {
    this.vueInstance.openSaveModal()
  }
}

window.$skeletonApp = new Application()
