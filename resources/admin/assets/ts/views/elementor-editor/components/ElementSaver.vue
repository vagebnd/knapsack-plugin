<template>
  <div>
    <Dialog :is-open="isOpen">
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
import { ref } from 'vue'
import { Container } from '/@admin:types/elementor'
import { viewElement, updateElement, createElement } from '/@admin:utils/http/theme-manager'
import { toast } from 'vue-sonner'
import Dialog from './Dialog.vue'
import { $t } from '/@admin:plugins/i18n'

const isOpen = ref(false)
const container = ref<Container>()
const content = ref('')
const isFetching = ref(true)
const isSubmitting = ref(false)
const isCreating = ref(true)
const elementTitle = ref('')

let resolver: (value: unknown) => void
let rejector: (reason?: unknown) => void

const open = (containerValue: Container, contentValue: string) => {
  content.value = contentValue
  container.value = containerValue
  isOpen.value = true

  fetchElementData()

  return new Promise((resolve, reject) => {
    resolver = resolve
    rejector = reject
  })
}

const fetchElementData = () => {
  if (!container.value) {
    return rejector(new Error('Container is not defined'))
  }

  viewElement(container.value.id)
    .then((response) => {
      elementTitle.value = response.data.title
      isCreating.value = false
    })
    .finally(() => {
      isFetching.value = false
    })
}

const submitForm = () => {
  if (!container.value) {
    return rejector(new Error('Container is not defined'))
  }

  if (elementTitle.value === '') {
    return toast.error($t('Add a title.'))
  }

  const data = {
    title: elementTitle.value,
    content: content.value,
    external_id: container.value.id,
    type: container.value.type,
  }

  if (isCreating.value) {
    createElement(data)
      .then((response) => {
        toast.success($t('Element created successfully.'))
        resolver(response.data)
      })
      .finally(finish)
  }

  updateElement(container.value.id, data)
    .then((response) => {
      toast.success($t('Element updated successfully.'))
      resolver(response.data)
    })
    .finally(finish)

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
