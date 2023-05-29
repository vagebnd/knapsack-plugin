<template>
  <textarea
    :value="modelValue"
    @input="emit('update:modelValue', ($event.target as HTMLTextAreaElement)?.value)"
    @blur="emit('update')"
    class="px-3 py-3 ring-1 ring-gray-300 block w-full rounded-md focus:ring-sky-500 border-0"
  >
  </textarea>
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: string | number
}>()
</script>
