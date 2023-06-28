import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue'
import { defineComponent } from 'vue'

export default defineComponent({
  components: {
    tabgroup: TabGroup,
    tablist: TabList,
    tab: Tab,
    tabpanels: TabPanels,
    tabpanel: TabPanel,
  },
  setup() {
    return {}
  },
})
