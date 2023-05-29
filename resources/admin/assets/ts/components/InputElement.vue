<template>
  <div class="flex flex-row items-center rounded-md focus:ring-sky-500 border-0 ring-gray-300 ring-1">
    <Icon :name="icon" class="text-current ml-2 w-3" v-if="icon" />
    <input
      type="text"
      :value="modelValue"
      @input="emit('update:modelValue', ($event.target as HTMLInputElement)?.value)"
      @blur="emit('update', ($event.target as HTMLInputElement)?.value)"
      class="h-full w-full bg-transparent px-2 border-none focus:ring-0 focus:border-none"
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
