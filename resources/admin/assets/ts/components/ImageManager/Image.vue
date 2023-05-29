<template>
  <div class="relative">
    <img :src="thumb" class="w-36 h-36 rounded-md" />
    <button @click="emit('delete', id)" class="absolute bottom-2 right-2 bg-white p-1 rounded">
      <Icon name="bin" class="text-gray-500" />
    </button>
  </div>
</template>

<script lang="ts" setup>
export type Image = {
  id: number
  thumb: string
}

const emit = defineEmits<{
  (event: 'delete', id: number): void
}>()

const props = defineProps<{
  id: number
  thumb: string
}>()
</script>
