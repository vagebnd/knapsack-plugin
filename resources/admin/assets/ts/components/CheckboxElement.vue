<template>
  <input
    type="checkbox"
    :value="modelValue"
    @input="emit('update:modelValue', ($event.target as HTMLInputElement)?.value)"
    @blur="emit('update', ($event.target as HTMLInputElement)?.value)"
  />
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: string | number
  //   value: string | number
}>()
</script>
