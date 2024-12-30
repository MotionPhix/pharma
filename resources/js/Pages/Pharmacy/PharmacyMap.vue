// Map component
<script setup lang="ts">
import { onMounted, ref, onUnmounted } from 'vue';
import { visitModal } from '@inertiaui/modal-vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css'; // Import Leaflet CSS
import PharmacyCard from '@/Pages/Pharmacy/Partials/PharmacyCard.vue'
import { Separator } from '@/Components/ui/separator';
import axios from 'axios';

const map = ref();
const userLocation = ref({ latitude: null, longitude: null, accuracy: null });
const nearestPharmacies = ref([]);
let userMarker, circle, zoomed, watchId;

const options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 2000,
};

function showPharmacyInfo(uuid) {
  visitModal(route('pharmacies.quick.info', uuid));
}

// Function to calculate distance between two coordinates
const calculateDistance = (lat1, lon1, lat2, lon2) => {
  const R = 6371; // Radius of the Earth in km
  const dLat = (lat2 - lat1) * (Math.PI / 180);
  const dLon = (lon2 - lon1) * (Math.PI / 180);
  const a =
    Math.sin(dLat / 2) * Math.sin(dLat / 2) +
    Math.cos(lat1 * (Math.PI / 180)) * Math.cos(lat2 * (Math.PI / 180)) *
    Math.sin(dLon / 2) * Math.sin(dLon / 2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
  return R * c; // Distance in km
};

// Fetch nearest pharmacies from backend
const fetchNearestPharmacies = async () => {
  if (userLocation.value.latitude && userLocation.value.longitude) {
    try {
      const response = await axios.get(route('api.pharmacies.nearest'), {
        params: {
          latitude: userLocation.value.latitude,
          longitude: userLocation.value.longitude,
          limit: 6,
        },
      });
      nearestPharmacies.value = response.data;
      updateMapMarkers();
    } catch (error) {
      console.error('Error fetching nearest pharmacies:', error);
    }
  }
};

// Custom marker icons
const userMarkerIcon = L.divIcon({
  className: "my-custom-pin",
  iconAnchor: [0, 12],
  labelAnchor: [-6, 0],
  popupAnchor: [0, -18],
  html: `<span style="background-color: red; width: 1.5rem; height: 1.5rem; display: block; left: -0.75rem; top: -0.75rem; position: relative; border-radius: 1.5rem 1.5rem 0; transform: rotate(45deg); border: 1px solid #FFFFFF;">
           <span style="background-color: white; width: 0.5rem; height: 0.5rem; display: block; position: absolute; top: 0.5rem; left: 0.5rem; border-radius: 50%;"></span>
         </span>`
});

const pharmacyMarkerIcon = L.divIcon({
  className: "my-custom-pin",
  iconAnchor: [0, 12],
  labelAnchor: [-6, 0],
  popupAnchor: [0, -18],
  html: `<span style="background-color: green; width: 1.5rem; height: 1.5rem; display: block; left: -0.75rem; top: -0.75rem; position: relative; border-radius: 1.5rem 1.5rem 0; transform: rotate(45deg); border: 1px solid #FFFFFF;">
           <span style="background-color: white; width: 0.5rem; height: 0.5rem; display: block; position: absolute; top: 0.5rem; left: 0.5rem; border-radius: 50%;"></span>
         </span>`
});

// Function to initialize the map
const initMap = async () => {
  map.value = L.map('map').setView([-13.9631, 33.7741], 13); // Central Region, Malawi

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: 'OpenStreetMap',
  }).addTo(map.value);

  setTimeout(() => {
    updateMapMarkers();
  }, 300);
};

const updateMapMarkers = () => {
  if (!map.value) return;

  nearestPharmacies.value.forEach(pharmacy => {
    const marker = L.marker([pharmacy.latitude, pharmacy.longitude], { icon: pharmacyMarkerIcon }).addTo(map.value);
    marker.on('click', () => showPharmacyInfo(pharmacy.uuid));
  });
};

const success = position => {
  userLocation.value = {
    latitude: position.coords.latitude,
    longitude: position.coords.longitude,
    accuracy: position.coords.accuracy,
  };

  if (userMarker) {
    map.value.removeLayer(userMarker);
    map.value.removeLayer(circle);
  }

  userMarker = L.marker([userLocation.value.latitude, userLocation.value.longitude], { icon: userMarkerIcon }).addTo(map.value);
  circle = L.circle([userLocation.value.latitude, userLocation.value.longitude], { radius: userLocation.value.accuracy }).addTo(map.value);

  if (!zoomed) {
    zoomed = map.value.fitBounds(circle.getBounds());
  }

  map.value.setView([userLocation.value.latitude, userLocation.value.longitude], 13);

  fetchNearestPharmacies();
};

const error = err => {
  console.error(err);

  if (err.code === 1) {
    console.log('Please allow geolocation access');
  } else {
    console.log('Cannot get current location');
  }
};

// Watch user position and update map
// navigator.geolocation.watchPosition(success, error, options);

onMounted(() => {
  initMap();

  // Start geolocation watcher
  watchId = navigator.geolocation.watchPosition(success, error, options);
});

onUnmounted(() => {
  if (watchId) {
    navigator.geolocation.clearWatch(watchId); // Clear geolocation watcher
  }

  if (map.value) {
    map.value.off(); // Remove map event listeners
    map.value.remove(); // Destroy the map instance
    map.value = null; // Clear the reference
  }
});
</script>

<template>
  <div>
    <div class="max-w-4xl mx-4 my-12 sm:mx-auto dashboard sm:0">

      <h2 class="pl-2 text-2xl font-medium">
        Pharmacies close to you.
      </h2>

      <Separator class="my-4" />

      <PharmacyCard :pharmacies="nearestPharmacies" />

    </div>

    <div id="map" style="height: 400px; z-index: 0;"></div>
  </div>
</template>
