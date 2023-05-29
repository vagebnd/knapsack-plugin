import '/@css/tailwind.css'

import mountVue from '../bootstrap/vue'
import Pricelist from './pricelist/Pricelist.vue'
import Index from './pricelist/Index.vue'
import Update from './pricelist/Update.vue'

mountVue(Pricelist, [
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
