<script setup lang="ts">
import { onMounted, onUnmounted, ref, watch } from 'vue';
import { useLocation } from '@/composables/useLocation';
import axios from 'axios';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const map = ref();
const userMarker = ref(null);
const accuracyCircle = ref(null);
const pharmacyMarkers = ref([]);
const routePolyline = ref(null);

const { userLocation, error } = useLocation();

const fetchNearbyPharmacies = async () => {
  if (userLocation.value.latitude && userLocation.value.longitude) {
    try {
      const response = await axios.get(route('api.pharmacies.nearest'), {
        params: {
          latitude: userLocation.value.latitude,
          longitude: userLocation.value.longitude,
          limit: 5,
        },
      });
      return response.data;
    } catch (err) {
      console.error('Error fetching nearby pharmacies:', err);
      return [];
    }
  }
  return [];
};

const displayPharmaciesOnMap = async () => {
  const pharmacies = await fetchNearbyPharmacies();
  pharmacyMarkers.value.forEach(marker => map.value.removeLayer(marker)); // Clear existing markers

  pharmacyMarkers.value = pharmacies.map(pharmacy => {
    const marker = L.marker([pharmacy.latitude, pharmacy.longitude])
      .addTo(map.value)
      .bindPopup(`
        <strong>${pharmacy.name}</strong><br>
        ${pharmacy.address}<br>
        <button class="route-btn" data-lat="${pharmacy.latitude}" data-lng="${pharmacy.longitude}">
          Show Route
        </button>
      `);
    return marker;
  });
};

const drawRoute = async (destinationLat, destinationLng) => {
  if (routePolyline.value) map.value.removeLayer(routePolyline.value);

  const userLatLng = [userLocation.value.latitude, userLocation.value.longitude];

  try {
    const response = await axios.get('https://api.openrouteservice.org/v2/directions/driving-car', {
      params: {
        api_key: '5b3ce3597851110001cf624823fff81cb24c48f8b52305d489fe7400',
        start: `${userLatLng[1]},${userLatLng[0]}`, // Longitude,Latitude
        end: `${destinationLng},${destinationLat}`, // Longitude,Latitude
      },
    });

    const routeCoords = response.data.features[0].geometry.coordinates.map(coord => [coord[1], coord[0]]); // [lat, lng]
    routePolyline.value = L.polyline(routeCoords, { color: 'blue', weight: 4 }).addTo(map.value);
    map.value.fitBounds(routePolyline.value.getBounds());
  } catch (error) {
    console.error('Error fetching route:', error);
  }
};

const initMap = () => {
  map.value = L.map('real-time-map').setView([0, 0], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: 'OpenStreetMap',
  }).addTo(map.value);

  map.value.on('popupopen', (e) => {
    const button = e.popup._contentNode.querySelector('.route-btn');
    if (button) {
      const { lat, lng } = button.dataset;
      button.addEventListener('click', () => drawRoute(parseFloat(lat), parseFloat(lng)));
    }
  });
};

const updateUserLocationOnMap = () => {
  if (!userLocation.value.latitude || !userLocation.value.longitude) return;

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

  map.value.setView([userLocation.value.latitude, userLocation.value.longitude], 13);
  displayPharmaciesOnMap();
};

watch(userLocation, updateUserLocationOnMap);

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

.route-btn {
  background-color: #007bff;
  color: white;
  border: none;
  padding: 4px 8px;
  border-radius: 4px;
  cursor: pointer;
}
</style>
