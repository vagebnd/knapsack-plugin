<template>
  <div class="text-right">
    <ButtonIcon name="plus" @click="toggle" v-if="!isActive" />
    <form class="flex" @submit.prevent="emitCreate" v-else>
      <InputElement v-model="title" class="flex-1 !mb-0" :placeholder="$t('Enter title')" />
      <ButtonElement type="button" @click="toggle" class="ml-2">
        {{ $t('cancel') }}
      </ButtonElement>
      <ButtonElement type="submit" :disabled="!isCreateButtonEnabled" class="disabled:opacity-50 ml-2">
        <slot>
          {{ $t('add item') }}
        </slot>
      </ButtonElement>
    </form>
  </div>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { $t } from '/@admin:plugins/i18n'

const emit = defineEmits<{
  (event: 'create', value: string): void
}>()

const isActive = ref(false)
const title = ref('')
const isCreateButtonEnabled = computed(() => title.value.length > 0)

const toggle = () => {
  if (isActive.value) {
    title.value = ''
  }

  isActive.value = !isActive.value
}

const emitCreate = () => {
  emit('create', title.value)
  toggle()
}
</script>
