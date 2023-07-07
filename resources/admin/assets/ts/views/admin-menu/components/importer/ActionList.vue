<template>
  <div v-if="hasPluginActions">
    <h2>Plugins</h2>
    <ul>
      <li v-for="action in pluginActions">
        <h3>{{ action.title }}</h3>
        <p>{{ action.description }}</p>
      </li>
    </ul>
  </div>
  <div v-if="hasExtensionActions">
    <h2>Plugins</h2>
    <ul>
      <li v-for="action in extensionActions">
        <h3>{{ action.title }}</h3>
        <p>{{ action.description }}</p>
      </li>
    </ul>
  </div>
</template>

<script lang="ts" setup>
import { computed } from 'vue'

export type Action = {
  type: 'plugin' | 'extension'
  title: string
  description: string
  link: string
}

const props = defineProps<{
  actions: Action[]
}>()

const pluginActions = computed(() => props.actions.filter((action) => action.type === 'plugin'))
const extensionActions = computed(() => props.actions.filter((action) => action.type === 'extension'))
const hasPluginActions = computed(() => pluginActions.value.length > 0)
const hasExtensionActions = computed(() => extensionActions.value.length > 0)
</script>
