import { App, defineAsyncComponent } from 'vue'

export const registerComponents = (app: App) => {
  const modules = import.meta.glob('../components/**/*.vue')

  for (const path in modules) {
    const mod = modules[path] as any
    const parts = path.split('/')
    const name = parts[parts.length - 1].replace('.vue', '')

    app.component(name, defineAsyncComponent(mod))
  }
}
