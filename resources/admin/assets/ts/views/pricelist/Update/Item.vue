<template>
  <details class="group/item bg-gray-100 rounded-md">
    <summary
      class="flex bg-gray-200 p-4 justify-between rounded-md group-open/item:rounded-bl-none group-open/item:rounded-br-none"
    >
      <h1 class="text-gray-900">{{ title }}</h1>
      <button class="handle w-3"><Icon name="drag-handler" class="w-3 h-3" /></button>
    </summary>
    <div class="body p-5 bg-white">
      <div class="flex">
        <div class="mb-4 flex-1">
          <InputLabel>{{ $t('title') }}</InputLabel>
          <InputElement v-model="titleLocal" @update="emit('save')" />
        </div>
        <div class="mb-4 flex-3 ml-3">
          <InputLabel>{{ $t('price') }}</InputLabel>
          <InputElement v-model="priceLocal" @update="emit('save')" inputmode="decimal" />
        </div>
      </div>
      <div class="mb-4">
        <InputLabel>{{ $t('description') }}</InputLabel>
        <TextAreaElement v-model="contentLocal" @update="emit('save')" />
      </div>
      <div class="mb-4">
        <InputLabel>{{ $t('tags') }}</InputLabel>
        <vue-tags-input
          v-model="tag"
          :tags="currenTags"
          @tags-changed="emitTags"
          :autocomplete-items="autoCompleteTags"
          class="w-full !max-w-none"
        />
      </div>
      <div>
        <InputLabel>{{ $t('images') }}</InputLabel>
        <ImageManager :images="images" @update="imagesUpdated" />
      </div>
    </div>
  </details>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Image } from '/@admin:components/ImageManager/Image.vue'
import VueTagsInput from '@sipec/vue3-tags-input'
import { $t } from '/@admin:plugins/i18n'

export type PricelistItem = {
  id: number
  title: string
  content: string
  price: number
  images: Image[]
  tags: []
}

const emit = defineEmits(['update:title', 'update:content', 'update:price', 'update:images', 'update:tags', 'save'])

const props = defineProps<{
  title: string
  content: string
  price: number
  images: Image[]
  tags: string[]
  tagOptions: string[]
}>()

const imagesUpdated = (images: Image[]) => {
  emit('update:images', images)
  emit('save')
}

const titleLocal = computed({
  get() {
    return props.title
  },
  set(value: string) {
    emit('update:title', value)
  },
})

const contentLocal = computed({
  get() {
    return props.content
  },
  set(value: string) {
    emit('update:content', value)
  },
})

const priceLocal = computed({
  get() {
    return props.price
  },
  set(value: number) {
    emit('update:price', value)
  },
})

const autoCompleteTags = computed(() => {
  return props.tagOptions.map((tag) => {
    return { text: tag }
  })
})

const currenTags = computed(() => {
  return props.tags.map((tag) => {
    return { text: tag }
  })
})

const emitTags = (tags: { text: string }[]) => {
  const newTags = tags.filter((tag) => tag.text !== null).map((tag) => tag.text)
  emit('update:tags', newTags)
  emit('save')
}

const tag = ref('')
</script>
