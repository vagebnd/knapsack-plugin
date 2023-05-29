import { VueElement, createApp } from 'vue'
import { RouteRecordRaw, createRouter, createWebHashHistory } from 'vue-router'
import { empty } from '/@admin:utils/object'
import { registerComponents } from './components'

export default function mountVue(rootApp: VueElement, routes: RouteRecordRaw[] = []) {
  const app = createApp(rootApp)

  if (!empty(routes)) {
    app.use(getRouter(routes))
  }

  app.mount('#vue')
  registerComponents(app)

  return app
}

function getRouter(routes: RouteRecordRaw[]) {
  return createRouter({
    history: createWebHashHistory(),
    routes,
    scrollBehavior: () => {
      return { top: 0 }
    },
  })
}
