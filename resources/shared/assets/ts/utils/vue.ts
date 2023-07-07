import { DefineComponent, createApp, defineComponent } from 'vue'
import { registerComponents } from '/@admin/assets/ts/bootstrap/components'

export default function mountVue(selector: string, component: DefineComponent) {
  if (!component) {
    component = defineComponent({})
  }

  const app = createApp(component)
  registerComponents(app)
  app.mount(selector)
  return app
}
