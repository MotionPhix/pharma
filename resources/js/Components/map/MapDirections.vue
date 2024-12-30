<script setup lang="ts">
import { ref, watch } from 'vue';
import { Swiper, SwiperSlide } from 'swiper/vue';
import 'swiper/css';
import 'swiper/css/pagination';
import { Mousewheel, Pagination } from 'swiper/modules';

// Props for MapDirections
const props = defineProps<{
  directions: Array<{
    instruction: string;
    distance: number;
  }>;
  onStepChange: (newStep: number) => void;
}>();

// Model binding for current step
const model = defineModel<number>();

// Speak a step's instruction
const speakInstruction = (text: string) => {
  const utterance = new SpeechSynthesisUtterance(text);
  speechSynthesis.speak(utterance);
};

// Swiper instance reference
const mySwiper = ref(null);

// Initialize Swiper
const onSwiper = (swiper: any) => {
  mySwiper.value = swiper;
};

// Sync Swiper index with currentStep
watch(
  () => model.value,
  (newStep) => {
    if (mySwiper.value) {
      mySwiper.value.slideTo(newStep); // Navigate to the active slide
    }
  },
  { immediate: true }
);

// Handle slide change events
const onSlideChange = () => {
  const activeIndex = mySwiper.value?.activeIndex || 0;
  props.onStepChange(activeIndex); // Notify parent about active step change
  speakInstruction(props.directions[activeIndex]?.instruction || ''); // Narrate the current instruction
};
</script>

<template>
  <div
    class="map-directions-container"
    :class="{ 'h-18 sm:h-9 sm:my-0.2': directions.length > 1 }">
    <Swiper
      @swiper="onSwiper"
      :modules="[Mousewheel, Pagination]"
      direction="vertical"
      slides-per-view="1"
      space-between="20"
      :mousewheel="true"
      @slideChange="onSlideChange"
      class="map-directions-swiper">
      <SwiperSlide
        v-for="(step, index) in directions"
        :key="index"
        :id="`step-${index}`"
        class="step-slide">
        <div class="step-card" :class="{ active: index === model }">
          <div class="step-details leading-tight">
            <p class="text-black font-block leading-tight text-wrap text-xl"><strong>{{ step.instruction }}</strong></p>
            <p class="step-distance pb-0.5">{{ step.distance }} meters</p>
          </div>
        </div>
      </SwiperSlide>
    </Swiper>
  </div>
</template>

<style scoped>
.map-directions-container {
  @apply max-h-20;
  width: 100%;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.step-slide {
  display: flex;
  justify-content: center;
  align-items: center;
}

.step-card {
  display: flex;
  align-items: center;
  gap: 15px;
  width: 90%;
  max-width: 400px;
  margin: 0 auto;
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.step-card.active {
  transform: scale(1.05);
}

.step-details {
  flex: 1;
}

.step-distance {
  font-size: 1rem;
}
</style>
