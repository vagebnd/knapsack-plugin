<template>
  <div>
    <form @submit.prevent="submitForm">
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
  </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import { http } from '/@admin:utils/http'
import { $t } from '/@admin:plugins/i18n'

type ElementData = {
  id: number
  title: string
}

const props = defineProps<{
  elementId?: number
}>()

const elementTitle = ref('')
const isSubmitting = ref(false)
let elementData = ref<ElementData>()

const fetchElementData = () => {
  http
    .get('/')
    .then((response) => {
      elementData.value = response.data as ElementData
      elementTitle.value = elementData.value?.title
    })
    .catch(() => {
      // TODO: :handle error
    })
}

const submitForm = () => {
  isSubmitting.value = true

  http
    .post('/')
    .then(() => {})
    .catch(() => {
      // TODO: :handle error
    })
    .finally(() => {
      isSubmitting.value = false
    })
}

onMounted(() => {
  if (props.elementId) {
    fetchElementData()
  }
})
</script>
