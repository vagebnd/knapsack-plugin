<template>
  <div class="importer">
    <h1>Import</h1>
    <ThemeTemplateList v-if="hasTemplates" :themeTemplates="themeTemplates" />
  </div>
</template>

<script lang="ts" setup>
import { computed, onMounted, ref } from 'vue'
import { Action } from './importer/ActionList.vue'
import { getTemplates } from '/@admin:utils/http/theme-manager'
import { ThemeTemplate } from '/@admin:types/theme-manager'
import ThemeTemplateList from './importer/ThemeTemplateList.vue'

const canImport = ref(true)
const actions = ref<Action[]>()
const themeTemplates = ref<ThemeTemplate[]>([])
const hasTemplates = computed(() => themeTemplates.value.length > 0)

onMounted(() => {
  getTemplates().then((response) => {
    themeTemplates.value = response.data.data
  })
})
</script>
