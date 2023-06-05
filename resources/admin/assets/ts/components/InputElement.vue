<template>
  <div class="flex flex-row items-center rounded-md border-none mb-4 ring-1 ring-zinc-500">
    <Icon :name="icon" class="text-current ml-1" v-if="icon" />
    <input
      type="text"
      :value="modelValue"
      @input="emit('update:modelValue', ($event.target as HTMLInputElement)?.value)"
      @blur="emit('update', ($event.target as HTMLInputElement)?.value)"
      class="w-full bg-transparent text-white p-2 min-h-0 border-none focus:ring-0 focus:border-none text-xs"
    />
  </div>
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: string | number
  icon?: string
}>()
</script>
