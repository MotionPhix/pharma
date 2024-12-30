<script setup lang="ts">
import axios from 'axios';
import { onMounted, onUnmounted, ref } from 'vue';
import MultiSelect from '@/Components/MultiSelect.vue';

let searchWatchId;
const distance = ref(5);
const servicesList = ref([]);
const searchQuery = ref('');
const selectedType = ref('');
const selectedServices = ref([]);
const pharmacies = ref([]);

const userLocation = ref({ latitude: null, longitude: null });

const fetchServicesList = async () => {
    try {
        const response = await axios.get(route('api.services.list'));
        servicesList.value = response.data;
    } catch (error) {
        console.error('Error fetching services list:', error);
    }
};

const fetchPharmacies = async () => {
  try {
    const response = await axios.get(route('api.pharmacies.search'), {
      params: {
        name: searchQuery.value,
        type: selectedType.value,
        services: selectedServices.value.map(service => service.id),
        distance: distance.value,
        latitude: userLocation.value.latitude,
        longitude: userLocation.value.longitude,
      },
    });
    pharmacies.value = response.data;
  } catch (error) {
    console.error('Error fetching pharmacies:', error);
  }
};

const success = position => {
  userLocation.value = {
    latitude: position.coords.latitude,
    longitude: position.coords.longitude,
  };
}

const error = err => {
  console.error(err);

  if (err.code === 1) {
    console.log('Please allow geolocation access');
  } else {
    console.log('Cannot get current location');
  }
}

onMounted(() => {
  fetchServicesList();
  searchWatchId = navigator.geolocation.watchPosition(success, error);
})

onUnmounted(() => {

  if (searchWatchId) {
    navigator.geolocation.clearWatch(searchWatchId);
  }

})
</script>

<template>

  <article class="max-w-4xl mx-auto">

  <div
    class="flex search-bar">
    <input v-model="searchQuery" type="text" placeholder="Search by name" class="w-full px-4 py-2 border">

    <select v-model="selectedType" class="px-4 py-2 border">
      <option value="">All Types</option>
      <option value="24/7">24/7</option>
      <option value="Discount">Discount</option>
      <option value="Regular">Regular</option>
    </select>

    <!-- <MultiSelect
      v-model="selectedServices"
      title="Filter by services"
      :options="servicesList" /> -->

    <button
      @click="fetchPharmacies"
      class="px-4 py-2 text-white bg-blue-500 hover:bg-blue-700">
      Search
      </button>
  </div>

  <div class="distance-filter">
    <label for="distance-slider" class="block text-sm font-medium text-gray-700">Distance (km)</label>
    <input
        id="distance-slider"
        type="range"
        v-model="distance"
        min="1"
        max="50"
        step="1"
        class="w-full"
    />

    <p class="text-sm text-gray-600">
      Up to {{ distance }} km
    </p>
  </div>
  
</article>

</template>
