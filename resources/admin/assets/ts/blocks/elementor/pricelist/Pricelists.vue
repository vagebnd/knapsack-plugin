<template>
  <div class="tailwind elementor-theme">
    <div v-if="isLoading">loading</div>
    <div v-else class="flex flex-col">
      <ul v-if="priceLists.length > 0" class="flex-1">
        <li v-for="list in priceLists" class="mb-3">
          <PriceList
            :id="list.id"
            :title="list.title"
            :is-active="activeIDs.has(list.id as number)"
            @delete="fetchPriceLists"
            @created="fetchPriceLists"
            @activate="activatePriceList"
          />
        </li>
      </ul>
      <p class="mb-4" v-else>{{ $t('No pricelists yet.') }}</p>
      <AddItem @create="createPriceList">
        {{ $t('add pricelist') }}
      </AddItem>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, provide, ref } from 'vue'
import { http } from '/@admin:utils/http'
import PriceList, { PriceList as PriceListType } from './Pricelist.vue'
import AddItem from './AddItem.vue'
import { $t } from '/@admin:plugins/i18n'

const props = defineProps<{
  currentValue: number[]
  types: Record<string, string>
}>()

const isLoading = ref(false)
const priceLists = ref<PriceListType[]>([])
const activeIDs = ref(new Set<number>())

const activatePriceList = ({ id, isActive }: { id: number; isActive: boolean }) => {
  if (isActive) {
    activeIDs.value.add(id)
  } else {
    activeIDs.value.delete(id)
  }

  dispatchSaveEvent()
}

const dispatchSaveEvent = () => {
  window.dispatchEvent(
    new CustomEvent('knapsack/control/save', {
      detail: Array.from(activeIDs.value),
    }),
  )
}

const fetchPriceLists = () => {
  http
    .get('pricelist')
    .then((response) => {
      priceLists.value = response.data
    })
    .catch((error) => {
      // TODO: handle error
    })
}

const createPriceList = (value: string) => {
  priceLists.value.push({
    title: value,
    type: Object.keys(props.types)[0],
    sections: [],
  })
}

onMounted(() => {
  fetchPriceLists()

  props.currentValue.forEach((id) => {
    activeIDs.value.add(id)
  })

  provide('types', props.types)
})
</script>
