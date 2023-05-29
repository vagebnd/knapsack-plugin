<template>
  <input
    type="text"
    :value="modelValue"
    @input="emit('update:modelValue', ($event.target as HTMLInputElement)?.value)"
    @blur="emit('update', ($event.target as HTMLInputElement)?.value)"
    class="px-3 py-3 ring-1 ring-gray-300 block w-full rounded-md focus:ring-sky-500 border-0"
  />
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: string | number
}>()
</script>
