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
import { computed, onMounted, ref } from 'vue'
import Section, { PricelistSection } from './Section.vue'
import { http } from '/@admin:utils/http'
import collect from 'collect.js'
import AddItem from './AddItem.vue'

type PriceList = {
  id?: number
  title: string
  sections: PricelistSection[]
}

const emit = defineEmits<{
  (event: 'created', value: number): void
  (event: 'delete'): void
}>()

const props = defineProps<{
  priceListID?: number
}>()

const priceList = ref<PriceList>({
  title: $t('new menu'),
  sections: [],
})

const tags = ref<string[]>([])
const isCreating = computed(() => props.priceListID === undefined)
const isUpdating = computed(() => !isCreating.value)

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
  http
    .delete(`pricelist/${props.priceListID}`)
    .then((response) => emit('delete'))
    .catch((error) => {
      // TODO: handle error
    })
}

const save = () => {
  const endpoint = isCreating.value ? 'pricelist/create' : `pricelist/${props.priceListID}`

  console.log(priceList.value.sections)

  http
    .post(endpoint, {
      title: priceList.value.title,
      sections: priceList.value.sections,
    })
    .then((response) => {
      if (isCreating.value) {
        emit('created', response.data.priceListID)
      }

      fetchTags()
      dispatchSaveEvent()
    })
    .catch((error) => {
      // TODO: handle error
    })
}

const deleteSection = (id: number) => {
  priceList.value.sections = priceList.value.sections.filter((section) => section.id !== id)
  save()
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
    .get(`pricelist/${props.priceListID}`)
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
