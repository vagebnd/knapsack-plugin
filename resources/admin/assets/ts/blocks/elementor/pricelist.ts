import '/@css/tailwind.css'

import mountVue from '/@admin:bootstrap/vue'
import PriceLists from '/@admin:blocks/elementor/pricelist/pricelists.vue'

const init = () => {
  let currentValue

  const pricelistView = elementor.modules.controls.BaseData.extend({
    onReady() {
      Promise.resolve().then(() => {
        currentValue = this.getCurrentValue()
        mountVue(PriceLists, currentValue)
      })

      window.addEventListener('knapsack/control/save', this.saveListener.bind(this))
      window.addEventListener('knapsack/iframe/reload', this.save.bind(this))
    },
    onBeforeDestroy() {
      window.removeEventListener('knapsack/control/save', this.saveListener)
      window.removeEventListener('knapsack/iframe/reload', this.save)
    },
    saveListener(event: CustomEvent) {
      this.currentValue = event.detail
      this.save()
    },
    save() {
      this.setValue(this.currentValue)
    },
  })

  elementor.addControlView('pricelist', pricelistView)
}

if (elementor) {
  init()
} else {
  window.addEventListener('elementor/init', init)
}
