<template>
  <div>
    <Dialog :is-open="isOpen" :full-screen="true">
      <header>
        <button @click="close">close</button>
      </header>
      <Loader v-if="isLoading" />
      <ul v-else>
        <li v-for="element in elements" :key="element.hash">
          <button @click="selectElement(element)">{{ element.title }}</button>
        </li>
      </ul>
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Dialog from './Dialog.vue'
import buildElements from '/@admin:data/elements.json'
import { getElements } from '/@admin:utils/http/theme-manager'
import { ElementData } from '/@admin:types/elementor'

const isOpen = ref(false)
const elements = ref<ElementData[]>([])
const isBuild = import.meta.env.VITE_IS_BUILD === 'true' ? true : false
const isLoading = ref(false)

let resolver: (value: unknown) => void
let rejector: (reason?: unknown) => void

const open = () => {
  isOpen.value = true

  if (isBuild) {
    elements.value = buildElements as ElementData[]
  } else {
    isLoading.value = true

    getElements()
      .then((response) => {
        elements.value = response.data.data
      })
      .finally(() => {
        isLoading.value = false
      })
  }

  return new Promise((resolve, reject) => {
    resolver = resolve
    rejector = reject
  })
}

const selectElement = (element: any) => {
  resolver(element)
  close()
}

const close = () => {
  isOpen.value = false
}

defineExpose({
  open,
})
</script>
