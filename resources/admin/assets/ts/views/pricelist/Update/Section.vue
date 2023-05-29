<template>
  <details class="bg-gray-100 rounded-md group max-w-3xl">
    <summary
      class="flex bg-gray-700 p-4 justify-between rounded-md group-open:rounded-bl-none group-open:rounded-br-none"
    >
      <h1 class="text-white">{{ title }}</h1>
      <button class="handle w-3">
        <Icon name="drag-handler" class="w-3 h-3 text-white" />
      </button>
    </summary>
    <div class="body p-5">
      <div class="grid grid-cols-2 gap-3 grid-flow-col mb-2">
        <div class="mb-4">
          <InputLabel>{{ $t('section name') }}</InputLabel>
          <InputElement v-model="titleLocal" @update="emit('save')" />
        </div>
        <form class="mb-4" @submit.prevent="addItem">
          <InputLabel>{{ $t('add an item') }}</InputLabel>
          <div class="flex">
            <InputElement v-model="newItemTitle" class="flex-1 cadskjhasdghjs" />
            <ButtonElement type="submit">{{ $t('add item') }}</ButtonElement>
          </div>
        </form>
      </div>

      <div class="items">
        <draggable v-model="itemsLocal" group="items" item-key="id" handle=".handle" @end="emit('save')">
          <template #item="{ element }">
            <div class="mb-4 last:mb-0">
              <Item
                v-model:title="element.title"
                v-model:content="element.content"
                v-model:price="element.price"
                v-model:images="element.images"
                v-model:tags="element.tags"
                :tagOptions="tags"
                @save="emit('save')"
              />
            </div>
          </template>
        </draggable>
      </div>
    </div>
  </details>
</template>

<script lang="ts" setup>
import collect from 'collect.js'
import { computed, ref, watch } from 'vue'
import draggable from 'vuedraggable'
import { $t } from '/@admin:plugins/i18n'
import Item, { PricelistItem } from './Item.vue'
import Icon from '/@admin:components/Icon.vue'

export type PricelistSection = {
  id: number
  title: string
  content: string
  items: PricelistItem[]
}

const emit = defineEmits(['update:title', 'update:items', 'save'])

const props = defineProps<{
  title: string
  items: PricelistItem[]
  tags: string[]
}>()

const newItemTitle = ref('')

const titleLocal = computed({
  get: () => props.title,
  set: (value) => {
    emit('update:title', value)
  },
})

const itemsLocal = computed({
  get: () => props.items,
  set: (value) => {
    emit('update:items', value)
    emit('save')
  },
})

const nextItemID = computed(() => {
  if (itemsLocal.value.length === 0) {
    return 1
  }

  return (collect(itemsLocal.value).pluck('id').max() || 0) + 1
})

const addItem = () => {
  if (newItemTitle.value.length <= 2) {
    return
  }

  itemsLocal.value = [
    ...itemsLocal.value,
    {
      id: nextItemID.value,
      title: newItemTitle.value,
      content: '',
      price: 0,
      images: [],
      tags: [],
    },
  ]

  newItemTitle.value = ''
}
</script>
