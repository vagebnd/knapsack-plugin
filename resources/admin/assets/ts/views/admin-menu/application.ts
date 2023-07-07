import { DefineComponent, defineComponent } from 'vue'
import mountVue from '/@shared/assets/ts/utils/vue'

async function importComponents() {
  const componentList = import.meta.glob('./components/**/*.vue')
  const components: Record<string, DefineComponent> = {}

  for (const path in componentList) {
    const componentName = path.split('/').pop()?.replace('.vue', '') as string
    const module = await (componentList[path]() as Promise<{ default: DefineComponent }>)
    components[componentName] = module.default
  }

  return components
}

async function setup() {
  const components = await importComponents()

  mountVue(
    '#admin-menu',
    defineComponent({
      components,
    }),
  )
}

setup()
