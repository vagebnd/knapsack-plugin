<template>
  <i class="w-3 flex justify-center items-center text-white [&_svg]:w-full [&_svg]:h-full" v-html="iconRaw"></i>
</template>

<script lang="ts" setup>
import { ref, watch } from 'vue'

const icons = import.meta.glob('../../icons/**/*.svg', { as: 'raw' })

const iconRaw = ref<string>('')

const props = defineProps<{
  name: string
}>()

const setIcon = async () => {
  const path = `../../icons/${props.name}.svg`
  const icon = icons[path]

  if (icon) {
    const svg = await icon()
    iconRaw.value = svg
  }
}

watch(props, () => setIcon(), { immediate: true })
</script>
