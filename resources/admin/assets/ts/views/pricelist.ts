import '/@css/tailwind.css'

import mountVue from '/@admin:bootstrap/vue'
import Main from './pricelist/Main.vue'
import Index from './pricelist/Index.vue'
import Update from './pricelist/Update.vue'

mountVue(Main, [
  {
    path: '/',
    name: 'index',
    component: Index,
  },
  {
    path: '/create',
    name: 'create',
    component: Update,
  },
  {
    path: '/update/:id',
    name: 'update',
    component: Update,
  },
])
