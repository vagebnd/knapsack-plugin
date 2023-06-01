<template>
  <Pricelist :priceListID="route.params.id" />
</template>

<script lang="ts" setup>
import { useRoute } from 'vue-router'
import Pricelist from './Update/Pricelist.vue'

const route = useRoute()
</script>
