import '/@css/tailwind.css'

import mountVue from '/@admin:bootstrap/vue'
import PriceLists from '/@admin:blocks/elementor/pricelist/pricelists.vue'

const init = () => {
  const pricelistView = elementor.modules.controls.BaseData.extend({
    onReady() {
      Promise.resolve().then(() => mountVue(PriceLists))
      window.addEventListener('vgb/pricelist/saved', this.save.bind(this))
    },
    onBeforeDestroy() {
      window.removeEventListener('vgb/pricelist/saved', this.save)
    },
    save() {
      this.setValue(6502)
    },
  })

  elementor.addControlView('pricelist', pricelistView)
}

if (elementor) {
  init()
} else {
  window.addEventListener('elementor/init', init)
}
