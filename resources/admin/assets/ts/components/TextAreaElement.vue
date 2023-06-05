<template>
  <textarea
    :value="modelValue"
    @input="emit('update:modelValue', ($event.target as HTMLTextAreaElement)?.value)"
    @blur="emit('update')"
    class="p-2 block w-full rounded-md border-none resize-none ring-1 ring-zinc-500 bg-transparent text-xs text-white"
    rows="3"
  >
  </textarea>
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: string | number
}>()
</script>
