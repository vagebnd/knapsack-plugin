<template>
  <AdminLayout>
    <template #title>
      <h1>{{ $t('pricelist') }}</h1>
    </template>
    <template #actions>
      <router-link :to="{ name: 'create' }">{{ $t('create') }} </router-link>
    </template>
    <template #body>
      <div v-if="isLoading">{{ $t('loading') }}</div>
      <ul v-else>
        <li v-for="priceList in priceLists" :key="priceList.id">
          {{ priceList.title }}
          <router-link :to="{ name: 'update', params: { id: priceList.id } }">
            {{ $t('edit') }}
          </router-link>
        </li>
      </ul>
    </template>
  </AdminLayout>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import AdminLayout from '/@admin:layouts/AdminLayout.vue'
import { $t } from '/@admin:plugins/i18n'
import { http } from '/@admin:utils/http'

type PriceList = {
  id: number
  title: string
}

const isLoading = ref(false)
const priceLists = ref<PriceList[]>([])

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

onMounted(fetchPriceLists)
</script>
