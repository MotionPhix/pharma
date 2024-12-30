<script setup lang="ts">
import { defineProps, watch } from 'vue';

defineProps({
  directions: {
    type: Array,
    required: true,
  },
  currentStep: {
    type: Number,
    required: true,
  },
  distanceRemaining: {
    type: Number,
    required: true,
  },
  durationRemaining: {
    type: Number,
    required: true,
  },
});

// Automatically scroll to the current step
watch(currentStep, () => {
  const stepElement = document.getElementById(`step-${currentStep}`);
  stepElement?.scrollIntoView({ behavior: 'smooth', block: 'center' });
});
</script>

<template>
  <div class="absolute bottom-5 right-5 bg-white shadow-lg rounded-lg p-4 w-80 max-h-80 overflow-y-auto">
    <h3 class="text-lg font-bold text-gray-800 mb-3">Directions</h3>
    <ul>
      <li
        v-for="(step, index) in directions"
        :key="index"
        :id="`step-${index}`"
        :class="{
          'bg-blue-100': index === currentStep,
          'p-2 rounded-lg': true,
        }"
        class="mb-2"
      >
        <p class="text-sm text-gray-800">{{ step.instruction }}</p>
        <p class="text-xs text-gray-500">{{ (step.distance / 1000).toFixed(2) }} km</p>
      </li>
    </ul>
    <div class="mt-4 border-t pt-2">
      <p class="text-sm font-semibold">Distance Remaining: {{ (distanceRemaining / 1000).toFixed(2) }} km</p>
      <p class="text-sm font-semibold">Estimated Time: {{ (durationRemaining / 60).toFixed(2) }} minutes</p>
    </div>
  </div>
</template>

<style scoped>
/* Improved scrollable list UX */
ul::-webkit-scrollbar {
  width: 8px;
}
ul::-webkit-scrollbar-thumb {
  background-color: #d1d5db;
  border-radius: 4px;
}
ul::-webkit-scrollbar-thumb:hover {
  background-color: #a1a1aa;
}
</style>
