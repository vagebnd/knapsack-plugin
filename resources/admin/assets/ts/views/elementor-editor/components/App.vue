<template>
  <div>
    <Library ref="library" />
    <ElementSaver ref="elementSaver" />

    <Toaster position="bottom-right" richColors />
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'

import Library from './Library.vue'
import ElementSaver from './ElementSaver.vue'
import { Toaster } from 'vue-sonner'

const library = ref<HTMLElement>()
const elementSaver = ref<HTMLElement>()

defineExpose({
  library,
  elementSaver,
})
</script>
