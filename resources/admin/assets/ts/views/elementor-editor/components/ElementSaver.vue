<template>
  <div>
    <Dialog :is-open="isOpen">
      <button @click="close">close</button>
      <Loader v-if="isFetching" />
      <form @submit.prevent="submitForm" v-else>
        <fieldset>
          <div>
            <label>{{ $t('title') }}</label>
            <input type="text" v-model="elementTitle" />
          </div>
        </fieldset>
        <fieldset>
          <button type="submit">{{ $t('submit') }}</button>
        </fieldset>
      </form>
    </Dialog>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Container } from '/@admin:types/elementor'
import { viewElement, updateElement, createElement } from '/@admin:utils/http/theme-manager'
import { toast } from 'vue-sonner'
import Dialog from './Dialog.vue'
import { $t } from '/@admin:plugins/i18n'
import Screenshot from '../utils/Screenshot'
import tus from '/@admin:utils/tus'

let resolver: (value: unknown) => void
let rejector: (reason?: unknown) => void

const isOpen = ref(false)
const container = ref<Container>()
const content = ref('')
const isFetching = ref(false)
const isSubmitting = ref(false)
const elementTitle = ref('')
const elementHash = ref<string | undefined>()

const isCreating = computed(() => !elementHash.value)

const open = (containerValue: Container, contentValue: string) => {
  content.value = contentValue
  container.value = containerValue
  elementHash.value = containerValue.settings?.attributes?.theme_mananager_hash
  isOpen.value = true

  if (!isCreating.value) {
    fetchElementData()
  }

  return new Promise((resolve, reject) => {
    resolver = resolve
    rejector = reject
  })
}

const close = () => {
  isOpen.value = false
}

const fetchElementData = () => {
  if (!container.value) {
    return rejector(new Error('Container is not defined'))
  }

  isFetching.value = true

  viewElement(elementHash.value as string)
    .then((response) => {
      elementTitle.value = response.data.title
    })
    .finally(() => {
      isFetching.value = false
    })
}

const submitForm = async () => {
  if (!container.value) {
    return rejector(new Error('Container is not defined'))
  }

  if (elementTitle.value === '') {
    return toast.error($t('Add a title.'))
  }

  const screenshot = new Screenshot()
  const blob = await screenshot.getBlob(container.value.view.el)

  if (!blob) {
    return toast.error($t('Error while creating the screenshot.'))
  }

  const upload = await tus.upload(blob, 'screenshot')

  if (!upload) {
    return toast.error($t('Error while uploading the screenshot.'))
  }

  const data = {
    title: elementTitle.value,
    type: container.value.type,
    screenshotID: upload?.url?.split('/').pop(),
    content: content.value,
  }

  if (isCreating.value) {
    createElement(data)
      .then((response) => {
        toast.success($t('Element created successfully.'))
        resolver(response.data)
      })
      .finally(finish)
  } else {
    updateElement(elementHash.value as string, data)
      .then((response) => {
        toast.success($t('Element updated successfully.'))
        resolver(response.data)
      })
      .finally(finish)
  }

  isSubmitting.value = true
}

const finish = () => {
  isOpen.value = false
  isSubmitting.value = false
  isOpen.value = false
}

defineExpose({
  open,
})
</script>
