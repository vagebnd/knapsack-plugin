<template>
  <div>
    <input
      type="checkbox"
      :value="modelValue"
      v-bind:checked="modelValue"
      v-on:change="$emit('update:modelValue', ($event.target as HTMLInputElement).checked)"
      class="mb-1"
    />
  </div>
</template>

<script lang="ts" setup>
const emit = defineEmits(['update:modelValue', 'update'])

defineProps<{
  modelValue: any[] | Set<any> | undefined
}>()
</script>
