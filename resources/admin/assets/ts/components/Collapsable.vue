<template>
  <div class="collapsable">
    <header>
      <slot name="header"></slot>
      <button @click="toggle">
        <span v-if="isOpen">{{ $t('open') }}</span>
        <span v-else>{{ $t('close') }}</span>
      </button>
    </header>
    <div class="body">
      <slot name="body"></slot>
    </div>
  </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { $t } from '../plugins/i18n'

const isOpen = ref(false)

const toggle = () => {
  isOpen.value = !isOpen.value
}
</script>
