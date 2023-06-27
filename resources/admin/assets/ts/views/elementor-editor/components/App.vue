<template>
  <div>
    <Dialog :is-open="isLibraryOpen" :full-screen="true">
      <div class="mt-2">
        <p class="text-sm text-gray-500">
          Your payment has been successfully submitted. We’ve sent you an email with all of the details of your order.
        </p>
      </div>

      <div class="mt-4">
        <button
          type="button"
          class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
          @click="closeLibrary"
        >
          Got it, thanks!
        </button>
      </div>
    </Dialog>

    <Dialog :is-open="isSaveModalOpen">
      <SaveElementForm :element-id="elementID" />

      <div class="mt-4">
        <button
          type="button"
          class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
          @click="closeSaveModal"
        >
          cancel
        </button>
      </div>
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import Dialog from './Dialog.vue'
import SaveElementForm from './SaveElementForm.vue'

const isLibraryOpen = ref(false)
const isSaveModalOpen = ref(false)
const elementID = ref(-1)

const openLibrary = () => {
  isLibraryOpen.value = true
}

const closeLibrary = () => {
  isLibraryOpen.value = false
}

const openSaveModal = (savingElementID: number) => {
  elementID.value = savingElementID
  isSaveModalOpen.value = true
}

const closeSaveModal = () => {
  isSaveModalOpen.value = false
}

defineExpose({ openLibrary, openSaveModal })
</script>
