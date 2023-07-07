<template>
  <div>
    <ul>
      <li v-for="themeTemplate in themeTemplates">
        <button @click="installTheme(themeTemplate)">
          <h3>{{ themeTemplate.title }}</h3>
        </button>
      </li>
    </ul>
  </div>
</template>

<script lang="ts" setup>
import { ThemeTemplate } from '/@admin/assets/ts/types/theme-manager'
import { importTemplate } from '/@admin/assets/ts/utils/http/wp'

defineProps<{
  themeTemplates: ThemeTemplate[]
}>()

const installTheme = (themeThemplate: ThemeTemplate) => {
  importTemplate(themeThemplate.hash).then((response) => {
    console.log(response)
  })
}
</script>
