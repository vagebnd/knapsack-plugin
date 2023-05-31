<template>
  <Collapsable>
    <template #close>
      <h1 class="text-sm font-semibold leading-6 text-gray-900">{{ title }}</h1>
    </template>
    <template #open>
      <div class="flex justify-between items-center ml-2">
        <InputElement v-model="titleLocal" @update="emit('save')" class="w-56 h-9" icon="pencil" />
      </div>
    </template>

    <template #body>
      <div class="bg-white rounded-bl-md rounded-br-md">
        <div class="items">
          <draggable v-model="itemsLocal" group="items" item-key="id" handle=".drag-handler" @end="emit('save')">
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
            <template #footer>
              <form class="flex" @submit.prevent="addItem">
                <InputElement v-model="newItemTitle" class="flex-1" :placeholder="$t('Enter new item name')" />
                <ButtonElement type="submit" :disabled="!isNewItemButtonEnabled" class="disabled:opacity-50">
                  {{ $t('add item') }}
                </ButtonElement>
              </form>
            </template>
          </draggable>
        </div>
      </div>
    </template>
  </Collapsable>
</template>

<script lang="ts" setup>
import collect from 'collect.js'
import { computed, ref, watch } from 'vue'
import draggable from 'vuedraggable'
import { $t } from '/@admin:plugins/i18n'
import Item, { PricelistItem } from './Item.vue'

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
const isNewItemButtonEnabled = computed(() => newItemTitle.value.length > 2)

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
