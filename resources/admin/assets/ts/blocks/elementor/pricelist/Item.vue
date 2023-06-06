<template>
  <Collapsable
    :is-deletable="true"
    @delete="emit('delete', id)"
    class="bg-zinc-200 [&_summary]:bg-zinc-200 [&_>summary]:border-b-zinc-400"
  >
    <template #header>
      <span>{{ title }}</span>
    </template>

    <template #body>
      <div class="flex">
        <div class="flex-1">
          <InputLabel>{{ $t('title') }}</InputLabel>
          <InputElement v-model="titleLocal" @update="emit('save')" />
        </div>
        <div class="flex-3 ml-6">
          <InputLabel>{{ $t('price') }}</InputLabel>
          <InputElement v-model.number="priceLocal" @update="emit('save')" inputmode="decimal" />
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
          class="w-full !bg-transparent !max-w-none [&_input]:min-h-0 [&_.ti-input]:border-none [&_.ti-input]:bg-transparent [&_.ti-input]:ring-1 [&_.ti-input]:ring-zinc-500 [&_.ti-input]:rounded-md [&_.ti-input]:py-1 [&_input]:focus:shadow-none [&_.ti-tag]:px-3 [&_.ti-tag]:py-1 [&_.ti-tag]:bg-zinc-500 [&_.ti-tag]:rounded-md"
          :tags="currenTags"
          :autocomplete-items="autoCompleteTags"
          @tags-changed="emitTags"
        />
      </div>
      <div>
        <InputLabel class="mb-1">{{ $t('images') }}</InputLabel>
        <ImageManager :images="images" @update="imagesUpdated" class="grid grid-cols-4 gap-4" />
      </div>
    </template>
  </Collapsable>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Image } from '/@admin:components/ImageManager/Image.vue'
import VueTagsInput from '@sipec/vue3-tags-input'
import { $t } from '/@admin:plugins/i18n'
import { empty } from '/@admin:utils/object'

export type PricelistItem = {
  id: number
  title: string
  content: string
  price: number
  images: Image[]
  tags: []
}

const emit = defineEmits<{
  (event: 'update:title', value: string): void
  (event: 'update:content', value: string): void
  (event: 'update:price', value: number): void
  (event: 'update:images', value: Image[]): void
  (event: 'update:tags', value: string[]): void
  (event: 'save'): void
  (event: 'delete', value: number): void
}>()

const props = defineProps<{
  id: number
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
  return props.tagOptions
    .map((tag) => {
      return { text: tag }
    })
    .filter((tag) => !empty(tag.text))
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
