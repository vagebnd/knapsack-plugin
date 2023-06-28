import { createApp } from 'vue'

export default function mountVue(selector: string, options: Record<string, unknown> = {}) {
  const app = createApp(options)
  app.mount(selector)
  return app
}
