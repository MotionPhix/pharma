<script setup lang="ts">
import { defineProps, computed } from 'vue';
import { ArrowLeftIcon, ArrowRightIcon, ArrowUpIcon, ArrowDownIcon, ArrowUpLeft, ArrowUpRight } from 'lucide-vue-next';

const props = defineProps({
  instruction: {
    type: String,
    required: true,
  },
  distance: {
    type: Number,
    required: true,
  },
});

const icon = computed(() => {
  const instruction = props.instruction.toLowerCase();
  if (instruction.includes('left')) return 'left';
  if (instruction.includes('right')) return 'right';
  if (instruction.includes('straight')) return 'straight';
  if (instruction.includes('u-turn')) return 'u-turn';
  return 'default';
});

const iconComponent = computed(() => {
  switch (icon.value) {
    case 'left':
      return ArrowLeftIcon;
    case 'right':
      return ArrowRightIcon;
    case 'straight':
      return ArrowUpIcon;
    case 'u-turn':
      return ArrowUpLeft; // or UTurnRightIcon based on the instruction
    default:
      return ArrowUpIcon; // default icon
  }
});
</script>

<template>
  <div class="flex items-start gap-2 w-fit">
    <component
      :is="iconComponent"
      class="w-6 h-6 text-gray-500 shrink-0" />

    <div class="text-md">
      <span class="block">{{ instruction }}</span>

      <span class="block font-semibold text-blue-500">
        {{ distance }} meters
      </span>
    </div>
  </div>
</template>
