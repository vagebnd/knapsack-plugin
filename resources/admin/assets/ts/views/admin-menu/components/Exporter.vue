<template>
  <div class="export">
    <header>
      <h1>
        {{ $t('Select a template') }}
      </h1>
    </header>
    <div class="body">
      <div v-if="isFetching || isExporting">loading...</div>
      <ul v-else>
        <li v-for="template in templates" :key="template.hash">
          <button @click="selectTemplate(template.hash)">
            {{ template.title }}
          </button>
        </li>
        <li>
          <button @click="isNewTemplateVisible = true">
            {{ $t('new template') }}
          </button>
          <form v-if="isNewTemplateVisible" ref="form" @submit.prevent="selectTemplate(null, newTemplateTitle)">
            <div>
              <label>{{ $t('title') }}</label>
              <input type="text" v-model="newTemplateTitle" />
            </div>
            <div>
              <button type="submit">{{ $t('create') }}</button>
            </div>
          </form>
        </li>
      </ul>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import { getTemplates } from '/@admin:utils/http/theme-manager'
import { ThemeTemplate } from '/@admin:types/theme-manager'
import { $t } from '/@admin:plugins/i18n'
import { onClickOutside } from '@vueuse/core'
import { exportTemplate } from '/@admin:utils/http/wp'

const templates = ref<ThemeTemplate[]>([])
const isNewTemplateVisible = ref(false)
const isExporting = ref(false)
const isFetching = ref(false)
const newTemplateTitle = ref('')
const form = ref<HTMLFormElement | null>(null)

onClickOutside(form, () => {
  isNewTemplateVisible.value = false
})

const selectTemplate = (hash: string | null, string = '') => {
  isNewTemplateVisible.value = false
  isExporting.value = true

  exportTemplate(hash, string)
    .then(fetchTemplates)
    .finally(() => {
      isExporting.value = false
    })
}

const fetchTemplates = () => {
  isFetching.value = true

  getTemplates()
    .then((response) => {
      templates.value = response.data.data
    })
    .finally(() => {
      isFetching.value = false
    })
}

onMounted(fetchTemplates)
</script>
