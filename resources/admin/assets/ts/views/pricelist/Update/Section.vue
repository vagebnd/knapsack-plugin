<template>
  <div class="section">
    <header>
      <input
        :value="title"
        @input="$emit('update:title', ($event.target as HTMLInputElement)?.value)"
        @blur="emit('save')"
      />
      <button class="handle">handle</button>
    </header>
    <div class="body">
      <div class="items">
        <draggable v-model="itemsLocal" group="items" item-key="id" handle=".handle" @end="emit('save')">
          <template #item="{ element }">
            <div class="item">
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
            <form class="add-item" @submit.prevent="addItem">
              <input v-model="newItemTitle" />
              <button type="submit">{{ $t('add item') }}</button>
            </form>
          </template>
        </draggable>
      </div>
    </div>
  </div>
</template>

<script lang="ts" setup>
import collect from 'collect.js'
import { ref, watch } from 'vue'
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

const itemsLocal = ref(props.items)
const newItemTitle = ref('')

const addItem = () => {
  if (newItemTitle.value.length <= 2) {
    return
  }

  itemsLocal.value.push({
    id: itemsLocal.value.length > 0 ? (collect(itemsLocal.value).pluck('id').max() || 0) + 1 : 1,
    title: newItemTitle.value,
    content: '',
    price: 0,
    images: [],
    tags: [],
  })

  newItemTitle.value = ''
  emitItems()
}

const emitItems = () => {
  emit('update:items', itemsLocal.value)
  emit('save')
}

watch(
  () => props.items,
  (items) => (itemsLocal.value = items),
  { immediate: true },
)
</script>
