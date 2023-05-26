<template>
  <header>
    <input
      :value="title"
      @input="emit('update:title', ($event.target as HTMLInputElement)?.value)"
      @blur="emit('save')"
    />
    <button class="handle">handle</button>
  </header>
  <div class="body">
    <textarea
      :value="content"
      @input="emit('update:content', ($event.target as HTMLTextAreaElement)?.value)"
      @blur="emit('save')"
    >
    </textarea>
    <input
      :value="price"
      @input="emit('update:price', parseFloat(($event.target as HTMLInputElement)?.value))"
      @blur="emit('save')"
      inputmode="decimal"
    />
    <ImageManager :images="images" @update="imagesUpdated" />
    <vue-tags-input v-model="tag" :tags="currenTags" @tags-changed="emitTags" :autocomplete-items="autoCompleteTags" />
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import ImageManager from '/@admin:components/ImageManager.vue'
import { Image } from '/@admin:components/ImageManager/Image.vue'
import VueTagsInput from '@sipec/vue3-tags-input'

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
  console.log(tags)

  const newTags = tags.filter((tag) => tag.text !== null).map((tag) => tag.text)
  emit('update:tags', newTags)
  emit('save')
}

const tag = ref('')
</script>
