<template>
  <button class="px-3 py-2 ml-3 rounded bg-sky-500 text-white inline-flex justify-center items-center">
    <Icon :name="name" class="w-3 h-3 text-white" />
    <slot></slot>
  </button>
</template>

<script lang="ts" setup>
defineProps<{
  name: string
}>()
</script>
