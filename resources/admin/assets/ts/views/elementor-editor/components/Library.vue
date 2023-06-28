<template>
  <div>
    <Dialog :is-open="isOpen" :full-screen="true"> </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Dialog from './Dialog.vue'

const isOpen = ref(false)

const open = () => {
  isOpen.value = true
}

defineExpose({
  open,
})
</script>
