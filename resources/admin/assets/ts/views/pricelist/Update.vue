<template>
  <AdminLayout>
    <template #title>
      <router-link :to="{ name: 'index' }" class="flex px-3 py-2 mr-3 rounded bg-sky-600 hover:bg-sky-500 items-center">
        <Icon name="chevron-left" class="mr-1" />
        {{ $t('back') }}
      </router-link>
      <InputElement
        v-model="priceList.title"
        @update="save"
        class="w-64 text-white bg-gray-700 ring-gray-700 focus:bg-gray-600 focus:ring-0"
      />
    </template>
    <template #actions>
      <div class="flex">
        <form @submit.prevent="addSection">
          <div class="flex justify-center flex-row">
            <InputElement
              v-model="newSectionTitle"
              class="w-64 text-white bg-gray-700 ring-gray-700 focus:ring-0 focus:bg-gray-600 wrap"
              :placeholder="$t('add a section')"
            />
            <ButtonElement type="submit" class="py-3 whitespace-nowrap">{{ $t('add section') }}</ButtonElement>
          </div>
        </form>
        <ButtonElement @click="deleteMenu" v-if="!isCreating" class="py-3 bg-red-500 inline">
          {{ $t('delete') }}
        </ButtonElement>
      </div>
    </template>
    <template #body>
      <div class="list">
        <draggable v-model="priceList.sections" group="people" item-key="id" handle=".handle" @end="save">
          <template #item="{ element }">
            <Section
              v-model:title="element.title"
              v-model:items="element.items"
              :tags="tags"
              @save="save"
              class="mb-4 last:mb-0"
            />
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
