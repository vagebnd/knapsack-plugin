import { Component, createApp } from 'vue'

const components = import.meta.glob('./components/**/*.ts')

const mountComponents = (widgetSelector: string, component: Component) => {
  document.querySelectorAll(`.${widgetSelector}`).forEach(async (el: Element) => {
    const app = createApp(component)
    const config = (el as HTMLElement)?.parentElement?.parentElement?.getAttribute('data-settings')

    app.provide('settings', JSON.parse(config as string))
    app.mount('#' + el.id)
  })
}

window.addEventListener('elementor/frontend/init', () => {
  elementorFrontend.hooks.addAction('frontend/element_ready/global', async ($scope: any) => {
    let widgetType = $scope.data('widget_type')

    if (!widgetType) {
      return
    }

    const widgetSelector = widgetType.replace('.default', '')
    const componentType = `./components/${widgetSelector}.ts`

    if (!Object.hasOwn(components, componentType)) {
      return
    }

    const component = (await components[componentType]()) as { default: Component }

    if (!component) {
      return
    }

    mountComponents(widgetSelector, component.default)
  })
})
