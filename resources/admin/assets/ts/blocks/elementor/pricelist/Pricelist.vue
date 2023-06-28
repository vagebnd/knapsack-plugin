<template>
  <Collapsable :is-deletable="true" @delete="deletePriceList">
    <template #header>
      <span>{{ priceList.title }}</span>
    </template>
    <template #body>
      <div>
        <InputLabel>{{ $t('title') }}</InputLabel>
        <InputElement v-model="priceList.title" @update="save" />
      </div>
      <div class="mb-4">
        <InputLabel>{{ $t('display mode') }}</InputLabel>
        <SelectElement v-model="priceList.type" :options="types" @update="save" />
      </div>
      <div class="flex justify-between">
        <InputLabel>{{ $t('show in widget') }}</InputLabel>
        <Checkbox v-model="isActiveLocal" />
      </div>
      <div>
        <InputLabel>{{ $t('sections') }}</InputLabel>
        <draggable v-model="priceList.sections" group="people" item-key="id" handle=".drag-handler" @end="save">
          <template #item="{ element }">
            <div class="mb-2">
              <Section
                v-model:title="element.title"
                v-model:items="element.items"
                :id="element.id"
                :tags="tags"
                @save="save"
                @delete="deleteSection"
              />
            </div>
          </template>
          <template #footer>
            <AddItem class="mt-2" @create="addSection">{{ $t('add section') }}</AddItem>
          </template>
        </draggable>
      </div>
    </template>
  </Collapsable>
</template>

<script lang="ts" setup>
import draggable from 'vuedraggable'
import { $t } from '/@admin:plugins/i18n'
import { computed, inject, onMounted, ref } from 'vue'
import Section, { PricelistSection } from './Section.vue'
import client from '/@admin:utils/http/wp'
import collect from 'collect.js'
import AddItem from './AddItem.vue'

export type PriceList = {
  id?: number
  title: string
  type: string
  sections: PricelistSection[]
}

const emit = defineEmits<{
  (event: 'created', value: number): void
  (event: 'delete'): void
  (event: 'activate', value: { id: number; isActive: boolean }): void
}>()

const props = defineProps<{
  id?: number
  title: string
  isActive: boolean
}>()

const types = inject('types') as Record<string, string>

const priceList = ref<PriceList>({
  id: undefined,
  title: $t('new menu'),
  type: Object.keys(types)[0],
  sections: [],
})

const tags = ref<string[]>([])
const isCreating = computed(() => props.id === undefined)
const isUpdating = computed(() => !isCreating.value)

const isActiveLocal = computed({
  get: () => props.isActive,
  set: (value) => {
    emitActivate(props.id as number, value)
  },
})

const addSection = (title: string) => {
  priceList.value.sections.push({
    id: priceList.value.sections.length > 0 ? (collect(priceList.value.sections).pluck('id').max() || 0) + 1 : 1,
    title: title,
    content: '',
    items: [],
  })

  save()
}

const deletePriceList = () => {
  emitActivate(props.id as number, false)

  client
    .delete(`pricelist/${props.id}`)
    .then((response) => emit('delete'))
    .catch((error) => {
      // TODO: handle error
    })
}

const save = () => {
  const endpoint = isCreating.value ? 'pricelist/create' : `pricelist/${props.id}`

  client
    .post(endpoint, {
      title: priceList.value.title,
      type: priceList.value.type,
      sections: priceList.value.sections,
    })
    .then((response) => {
      if (isCreating.value) {
        emit('created', response.data.id)
        emitActivate(response.data.id, true)
      }

      fetchTags()
      dispatchReloadEvent()
    })
    .catch((error) => {
      // TODO: handle error
    })
}

const dispatchReloadEvent = () => {
  window.dispatchEvent(new CustomEvent('knapsack/iframe/reload'))
}

const deleteSection = (id: number) => {
  priceList.value.sections = priceList.value.sections.filter((section) => section.id !== id)
  save()
}

const fetchTags = () => {
  client
    .get('tags')
    .then((response) => {
      tags.value = response.data
    })
    .catch((error) => {})
}

const fetchPriceList = () => {
  client
    .get(`pricelist/${props.id}`)
    .then((response) => {
      priceList.value.title = response.data.title
      priceList.value.type = response.data.type
      priceList.value.sections = response.data.sections
    })
    .catch((error) => {})
}

const emitActivate = (id: number, isActive: boolean) => {
  emit('activate', {
    id,
    isActive,
  })
}

onMounted(() => {
  priceList.value.id = props.id
  priceList.value.title = props.title

  if (isUpdating.value) {
    fetchPriceList()
    fetchTags()
  } else {
    save()
  }
})
</script>
