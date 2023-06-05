<template>
  <details @toggle="toggle" class="ring-1 ring-zinc-300 rounded-md overflow-hidden">
    <summary
      class="flex flex-row items-center justify-between bg-zinc-300"
      :class="{ 'border-b': isOpen, 'border-b-zinc-300': isOpen }"
    >
      <div class="flex items-center group-open:flex-1 text-xs ml-3 font-semibold leading-6 text-white">
        <Icon
          name="chevron-right"
          class="group-open:mr-3"
          :class="{
            'origin-center': isOpen,
            'rotate-90': isOpen,
          }"
        />

        <div class="flex-1 p-2 text-white">
          <slot name="header" />
        </div>
      </div>
      <button class="drag-handler py-2 px-3 flex items-center justify-center" v-if="isDraggable && isClosed">
        <Icon name="drag-handler" class="text-white" />
      </button>
      <button class="py-2 px-3 flex items-center justify-center" v-if="isOpen" @click="emit('delete')">
        <Icon name="x" class="text-white" />
      </button>
    </summary>
    <div class="body p-collapsable p-3">
      <slot name="body" />
    </div>
  </details>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import Icon from './Icon.vue'

const emit = defineEmits<{
  (event: 'delete'): void
}>()

withDefaults(
  defineProps<{
    isDraggable?: boolean
    isDeletable?: boolean
  }>(),
  {
    isDraggable: true,
    isDeletable: false,
  },
)

const isOpen = ref(false)
const isClosed = computed(() => !isOpen.value)

const toggle = (event: Event) => {
  isOpen.value = (event.target as HTMLDetailsElement).open
}
</script>
