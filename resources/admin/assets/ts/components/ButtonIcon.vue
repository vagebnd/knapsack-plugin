<template>
  <button class="p-2 rounded bg-zinc-500 text-white inline-flex justify-center items-center font">
    <Icon :name="name" class="w-3 h-3 text-white" />
  </button>
</template>

<script lang="ts" setup>
defineProps<{
  name?: string
}>()
</script>
