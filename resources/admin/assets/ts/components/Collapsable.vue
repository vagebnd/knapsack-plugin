<template>
  <details @toggle="toggle">
    <summary
      class="flex flex-row items-center justify-between bg-white rounded-md"
      :class="[
        {
          'rounded-bl-none': isOpen,
          'rounded-br-none': isOpen,
          'border-b': isOpen,
          'border-gray-200': isOpen,
        },
      ]"
    >
      <div class="flex items-center py-4 pl-4 group-open:flex-1 group-open:mr-3">
        <div class="mr-1 group-open:mr-3">
          <Icon
            name="chevron-right"
            :class="{
              'origin-center': isOpen,
              'rotate-90': isOpen,
            }"
          />
        </div>

        <div v-if="isOpen" class="flex-1">
          <slot name="open" />
        </div>

        <div v-else>
          <slot name="close" />
        </div>
      </div>
      <button class="drag-handler py-4 px-4 flex items-center justify-center" v-if="isDraggable && isClosed">
        <Icon name="drag-handler" class="w-3 h-3 text-gray-500" />
      </button>
    </summary>
    <div class="body p-4 bg-white rounded-bl-md rounded-br-md">
      <slot name="body" />
    </div>
  </details>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import Icon from './Icon.vue'

withDefaults(
  defineProps<{
    isDraggable?: boolean
  }>(),
  {
    isDraggable: true,
  },
)

const isOpen = ref(false)
const isClosed = computed(() => !isOpen.value)

const toggle = (event: Event) => {
  isOpen.value = (event.target as HTMLDetailsElement).open
}
</script>
