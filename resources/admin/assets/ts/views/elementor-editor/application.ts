import '/@admin:css/tailwind.css'

import { createApp } from 'vue'
import App from './components/App.vue'
import { registerComponents } from '../../bootstrap/components'
import { VueInstance } from '/@admin:types/elementor'

class Application {
  constructor() {
    this.mountVue()
    this.addTailwindClass()
    this.addBlocksButton()
  }

  mountVue() {
    const element = document.createElement('div')
    element.id = 'skeleton-component-library'
    document.body.appendChild(element)

    const app = createApp(App)
    registerComponents(app)

    window.$skeletonApp = app.mount(`#${element.id}`) as VueInstance
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
          window.$skeletonApp.library.open()
        }
      })
    })
  }

  addTailwindClass() {
    document.body.classList.add('tailwind')
  }
}

new Application()
