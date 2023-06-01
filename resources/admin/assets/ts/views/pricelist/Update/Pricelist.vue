<template>
  <router-link :to="{ name: 'index' }" class="flex mt-4 pr-5 mr-3 rounded items-center">
    <Icon name="chevron-left" class="mr-1" />
    {{ $t('back') }}
  </router-link>
  <AdminLayout>
    <template #title>
      <InputElement
        v-model="priceList.title"
        @update="save"
        class="w-64 h-9 text-white bg-gray-700 ring-gray-700 focus:bg-gray-700 focus:ring-0"
        icon="pencil"
      />
    </template>
    <template #actions>
      <div class="flex">
        <ButtonIcon icon="bin" @click="deleteMenu" v-if="!isCreating" class="bg-red-500 inline">
          {{ $t('delete') }}
        </ButtonIcon>
      </div>
    </template>
    <template #body>
      <div class="list max-w-3xl">
        <draggable v-model="priceList.sections" group="people" item-key="id" handle=".drag-handler" @end="save">
          <template #item="{ element }">
            <div class="mb-2">
              <Section v-model:title="element.title" v-model:items="element.items" :tags="tags" @save="save" />
            </div>
          </template>
          <template #footer>
            <form @submit.prevent="addSection" class="flex mt-4">
              <InputElement
                v-model="newSectionTitle"
                :placeholder="$t('Enter new section title')"
                class="flex-1 bg-white"
              />
              <ButtonIcon
                icon="plus"
                type="submit"
                class="whitespace-nowrap disabled:opacity-50"
                :disabled="!isNewSectionButtonEnabled"
              >
                {{ $t('add section') }}
              </ButtonIcon>
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
import Section, { PricelistSection } from './Section.vue'
import { http } from '/@admin:utils/http'
import { useRoute, useRouter } from 'vue-router'
import collect from 'collect.js'

type PriceList = {
  id?: number
  title: string
  sections: PricelistSection[]
}

const props = defineProps<{
  priceListID?: number
}>()

const priceList = ref<PriceList>({
  title: $t('new menu'),
  sections: [],
})

const newSectionTitle = ref('')
const tags = ref<string[]>([])
const router = useRouter()
const route = useRoute()
const isCreating = computed(() => props.priceListID === undefined)
const isUpdating = computed(() => !isCreating.value)
const isNewSectionButtonEnabled = computed(() => newSectionTitle.value.length > 2)

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
      dispatchSaveEvent()
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

const dispatchSaveEvent = () => {
  window.dispatchEvent(new CustomEvent('vgb/pricelist/saved'))
}

onMounted(() => {
  if (isUpdating.value) {
    fetchMenu()
    fetchTags()
  }
})
</script>
