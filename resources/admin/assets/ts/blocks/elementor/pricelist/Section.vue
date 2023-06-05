<template>
  <Collapsable class="bg-zinc-300 [&_>summary]:border-b-zinc-500" @delete="emit('delete', id)">
    <template #header>
      <span class="text-xs font-semibold leading-6">{{ title }}</span>
    </template>

    <template #body>
      <div class="rounded-bl-md rounded-br-md">
        <div class="mb-3 flex-1">
          <InputLabel>{{ $t('title') }}</InputLabel>
          <InputElement v-model="titleLocal" @update="emit('save')" />
        </div>
        <div>
          <InputLabel>{{ $t('items') }}</InputLabel>
          <div class="items">
            <draggable v-model="itemsLocal" group="items" item-key="id" handle=".drag-handler" @end="emit('save')">
              <template #item="{ element }">
                <div class="mb-2">
                  <Item
                    v-model:title="element.title"
                    v-model:content="element.content"
                    v-model:price="element.price"
                    v-model:images="element.images"
                    v-model:tags="element.tags"
                    :id="element.id"
                    :tagOptions="tags"
                    @save="emit('save')"
                    @delete="deleteItem"
                    class="[&_.body]:bg-collapsable-nested-bg"
                  />
                </div>
              </template>
              <template #footer>
                <AddItem class="[&_button]:bg-zinc-200" @create="addItem" />
              </template>
            </draggable>
          </div>
        </div>
      </div>
    </template>
  </Collapsable>
</template>

<script lang="ts" setup>
import collect from 'collect.js'
import { computed } from 'vue'
import draggable from 'vuedraggable'
import { $t } from '/@admin:plugins/i18n'
import Item, { PricelistItem } from './Item.vue'
import AddItem from './AddItem.vue'

export type PricelistSection = {
  id: number
  title: string
  content: string
  items: PricelistItem[]
}

const emit = defineEmits<{
  (event: 'update:title', value: string): void
  (event: 'update:items', value: PricelistItem[]): void
  (event: 'save'): void
  (event: 'delete', value: number): void
}>()

const props = defineProps<{
  id: number
  title: string
  items: PricelistItem[]
  tags: string[]
}>()

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

const addItem = (title: string) => {
  console.log(title)
  itemsLocal.value = [
    ...itemsLocal.value,
    {
      id: nextItemID.value,
      title,
      content: '',
      price: 0,
      images: [],
      tags: [],
    },
  ]
}

const deleteItem = (id: number) => {
  itemsLocal.value = itemsLocal.value.filter((item) => item.id !== id)
  emit('save')
}
</script>
