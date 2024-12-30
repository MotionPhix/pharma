<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { useLocation } from '@/composables/useLocation';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
// 5b3ce3597851110001cf624823fff81cb24c48f8b52305d489fe7400
const map = ref();
const userMarker = ref(null);
const accuracyCircle = ref(null);

const { userLocation, error } = useLocation();

const initMap = () => {
  map.value = L.map('real-time-map').setView([0, 0], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: 'OpenStreetMap',
  }).addTo(map.value);
};

const updateUserLocationOnMap = () => {
  if (!userLocation.value.latitude || !userLocation.value.longitude) return;

  // Update or add the user's location marker
  if (!userMarker.value) {
    userMarker.value = L.marker([userLocation.value.latitude, userLocation.value.longitude]).addTo(map.value);
    accuracyCircle.value = L.circle([userLocation.value.latitude, userLocation.value.longitude], {
      radius: userLocation.value.accuracy,
    }).addTo(map.value);
  } else {
    userMarker.value.setLatLng([userLocation.value.latitude, userLocation.value.longitude]);
    accuracyCircle.value.setLatLng([userLocation.value.latitude, userLocation.value.longitude]);
    accuracyCircle.value.setRadius(userLocation.value.accuracy);
  }

  // Center the map on the user's location
  map.value.setView([userLocation.value.latitude, userLocation.value.longitude], 13);
};

watch(userLocation, () => {
  if (map.value) {
    updateUserLocationOnMap();
  }
});

onMounted(() => {
  initMap();
});

onUnmounted(() => {
  if (map.value) {
    map.value.remove();
    map.value = null;
  }
});
</script>

<template>
  <div>
    <div v-if="error" class="text-red-500">{{ error }}</div>
    <div id="real-time-map" style="height: 400px; z-index: 0;"></div>
  </div>
</template>

<style scoped>
#real-time-map {
  border-radius: 8px;
  overflow: hidden;
}
</style>
