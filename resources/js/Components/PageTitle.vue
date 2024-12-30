<script setup lang="ts">
import { Separator } from './ui/separator';

defineProps<{
  heading: string
}>()
</script>

<template>
	<h2 class="mb-4 font-serif text-2xl font-semibold">
    {{ heading }}
  </h2>

  <Separator class="mb-6" />
</template>
