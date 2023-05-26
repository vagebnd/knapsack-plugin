<template>
  <AdminLayout>
    <template #title>
      <h1>{{ $t('update') }}</h1>
    </template>
    <template #actions>
      <router-link :to="{ name: 'index' }">{{ $t('back to menus') }}</router-link>
      <button @click="deleteMenu" v-if="!isCreating">{{ $t('delete') }}</button>
    </template>
    <template #body>
      <div class="list">
        <div>
          <label>{{ $t('pricelist name') }}</label>
          <input type="text" v-model="priceList.title" @blur="save" />
        </div>
        <draggable v-model="priceList.sections" group="people" item-key="id" handle=".handle" @end="save">
          <template #item="{ element }">
            <Section v-model:title="element.title" v-model:items="element.items" :tags="tags" @save="save" />
          </template>
          <template #footer>
            <form class="add-section" @submit.prevent="addSection">
              <input v-model="newSectionTitle" />
              <button type="submit">{{ $t('add section') }}</button>
            </form>
          </template>
        </draggable>
      </div>
    </template>
  </AdminLayout>
</template>

<script lang="ts" setup>
import AdminLayout from '/@admin:layouts/AdminLayout.vue'
import draggable from 'vuedraggable'
import { $t } from '/@admin:plugins/i18n'
import { computed, onMounted, ref } from 'vue'
import Section, { PricelistSection } from './Update/Section.vue'
import { http } from '/@admin:utils/http'
import { useRoute, useRouter } from 'vue-router'
import collect from 'collect.js'

type PriceList = {
  id?: number
  title: string
  sections: PricelistSection[]
}

const priceList = ref<PriceList>({
  title: $t('new menu'),
  sections: [],
})

const newSectionTitle = ref('')
const tags = ref<string[]>([])
const router = useRouter()
const route = useRoute()
const isCreating = computed(() => route.params.id === undefined)
const isUpdating = computed(() => !isCreating.value)

const addSection = () => {
  if (newSectionTitle.value.length <= 2) {
    return
  }

  priceList.value.sections.push({
    id: priceList.value.sections.length > 0 ? (collect(priceList.value.sections).pluck('id').max() || 0) + 1 : 1,
    title: newSectionTitle.value,
    content: '',
    items: [],
  })

  newSectionTitle.value = ''
  save()
}

const deleteMenu = () => {
  http
    .delete(`pricelist/${route.params.id}`)
    .then((response) => {
      router.replace({ name: 'index' })
    })
    .catch((error) => {
      // TODO: handle error
    })
}

const save = () => {
  const endpoint = isCreating.value ? 'pricelist/create' : `pricelist/${route.params.id}`

  http
    .post(endpoint, {
      title: priceList.value.title,
      sections: priceList.value.sections,
    })
    .then((response) => {
      if (isCreating.value) {
        router.replace({ name: 'update', params: { id: response.data.priceListID } })
      }

      fetchTags()
    })
    .catch((error) => {
      // TODO: handle error
    })
}

const fetchTags = () => {
  http
    .get('tags')
    .then((response) => {
      tags.value = response.data
    })
    .catch((error) => {})
}

const fetchMenu = () => {
  http
    .get(`pricelist/${route.params.id}`)
    .then((response) => {
      priceList.value.title = response.data.title
      priceList.value.sections = response.data.sections
    })
    .catch((error) => {})
}

onMounted(() => {
  if (isUpdating.value) {
    fetchMenu()
    fetchTags()
  }
})
</script>
