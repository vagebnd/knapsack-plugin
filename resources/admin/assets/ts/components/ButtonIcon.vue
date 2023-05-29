<template>
  <button class="px-3 py-2 ml-3 rounded bg-indigo-500 text-white inline-flex justify-center items-center">
    <Icon :name="icon" class="w-3 h-3 text-white mr-1" v-if="icon" />
    <slot></slot>
    <Icon :name="icon" class="w-3 h-3 text-white" v-if="iconAfter" />
  </button>
</template>

<script lang="ts" setup>
defineProps<{
  icon?: string
  iconAfter?: string
}>()
</script>
