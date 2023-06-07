<template>
  <div class="relative">
    <Icon name="chevron-down" class="absolute right-2 top-3" />
    <select
      :value="modelValue"
      @change="emitChange"
      class="p-2 h-8 w-full rounded-md border-none ring-1 ring-zinc-500 bg-transparent text-xs text-white"
    >
      <option v-for="(value, label) in options" :value="value">{{ label }}</option>
    </select>
  </div>
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: string | number
  options: Record<string, string | number>
}>()

const emitChange = (event: Event) => {
  emit('update:modelValue', (event.target as HTMLInputElement).value)
  emit('update', (event.target as HTMLInputElement)?.value)
}
</script>
