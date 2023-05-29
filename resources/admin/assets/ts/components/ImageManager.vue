<template>
  <draggable v-model="imagesLocal" group="items" item-key="id" handle=".image-container" @end="emitUpdate" class="flex">
    <template #item="{ element }">
      <div class="image-container mr-4">
        <Image :id="element.id" :thumb="element.thumb" @delete="deleteImage(element.id)" />
      </div>
    </template>
    <template #footer>
      <button
        type="button"
        @click="mediaUploader.open"
        class="ring-1 ring-gray-300 rounded-md border-0 flex w-36 h-36 font-medium leading-6 items-center justify-center"
      >
        <Icon name="plus" class="text-gray-400 w-8" />
      </button>
    </template>
  </draggable>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'
import draggable from 'vuedraggable'
import { $t } from '/@admin:plugins/i18n'
import Image, { Image as ImageType } from '/@admin:components/ImageManager/Image.vue'

const emit = defineEmits<{
  (event: 'update', value: ImageType[]): void
}>()

const props = defineProps<{
  images: ImageType[]
}>()

const imagesLocal = ref(props.images)

const mediaUploader = (wp.media.frames.file_frame = wp.media({
  title: $t('Choose Image'),
  button: {
    text: $t('Choose Image'),
  },
  multiple: true,
}))

const deleteImage = (id: number) => {
  imagesLocal.value = imagesLocal.value.filter((image) => image.id !== id)
  emitUpdate()
}

const selectImages = () => {
  const selection = mediaUploader?.state()?.get('selection')?.toJSON() || []

  if (selection.length === 0) {
    return
  }

  selection.forEach((image: any) => {
    imagesLocal.value.push({
      id: image.id,
      thumb: image.sizes.thumbnail.url,
    })
  })

  emitUpdate()
}

const emitUpdate = () => {
  emit('update', imagesLocal.value)
}

watch(
  () => props.images,
  (images) => (imagesLocal.value = images),
  { immediate: true },
)

mediaUploader.on('select', selectImages)
</script>
