<template>
  <div class="tailwind elementor-theme">
    <div v-if="isLoading">loading</div>
    <div v-else class="flex flex-col">
      <ul v-if="priceLists.length > 0" class="flex-1">
        <li v-for="list in priceLists" class="mb-3">
          <PriceList :priceListID="list.id" @delete="fetchPriceLists" />
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
import { computed, onMounted, ref } from 'vue'
import { http } from '/@admin:utils/http'
import PriceList from './Pricelist.vue'
import AddItem from './AddItem.vue'
import { $t } from '/@admin:plugins/i18n'

export type PriceListType = {
  id?: number
  title: string
}

const isLoading = ref(false)
const priceLists = ref<PriceListType[]>([])
const selectedPriceListIDs = ref<number[]>([])
const priceListActive = ref<PriceListType>()

// const isPriceListActive = computed(() => !!priceListActive.value)
// const priceListsSelected = computed(() =>
//   priceLists.value.filter((list) => selectedPriceListIDs.value.includes(list.id)),
// )

const fetchPriceLists = () => {
  http
    .get('pricelist')
    .then((response) => {
      priceLists.value = response.data
      selectedPriceListIDs.value = response.data.map((list: PriceListType) => list.id)
    })
    .catch((error) => {
      // TODO: handle error
    })
}

const createPriceList = () => {
  priceLists.value.push({
    title: $t('new pricelist'),
  })
}

onMounted(fetchPriceLists)
</script>
