<template>
  <div class="image">
    <img :src="thumb" />
    <button class="remove" @click="emit('delete', id)">{{ $t('remove') }}</button>
  </div>
</template>

<script lang="ts" setup>
import { $t } from '/@admin:plugins/i18n'

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
