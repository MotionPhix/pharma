<script setup lang="ts">
import {ref, onMounted, watch, onUnmounted, computed} from 'vue';
import L from 'leaflet';
import polyline from '@mapbox/polyline';
import 'leaflet/dist/leaflet.css';
import axios from 'axios';
import {useLocation} from '@/composables/usePlaces';
import {visitModal} from '@inertiaui/modal-vue'
import {Button} from '@/Components/ui/button'
import {Loader2} from 'lucide-vue-next'
import MapDirections from './MapDirections.vue';

const map = ref<L.Map | null>(null);
const userMarker = ref<L.Marker | null>(null);
const pharmacyMarkers = ref<L.Marker[]>([]);
const routeLayer = ref(null);
const directions = ref<Array<{
  distance: number
  duration: number
  instruction: string
  way_points: number[][]
}>>([]);
const selectedPharmacy = ref<{ latitude: number; longitude: number } | null>(null);
const distanceRemaining = ref<number>(0);
const durationRemaining = ref<number>(0);

const API_KEY = '5b3ce3597851110001cf624823fff81cb24c48f8b52305d489fe7400';

const mapMode = ref()

const redIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

const blueIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

const greenIcon = new L.Icon({
  iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
  iconSize: [25, 41],
  iconAnchor: [12, 41],
  popupAnchor: [1, -34],
  shadowSize: [41, 41]
});

const {userLocation, startWatching, stopWatching} = useLocation();

const showPharmacyInfo = (uuid) => {
  visitModal(route('pharmacies.quick.info', uuid), {
    listeners: {
      getRoute({lat, lng, mode}) {
        navigateToPharmacy({latitude: lat, longitude: lng}, mode)
        mapMode.value = computed(() => {
          return mode === 'foot-walking' ? 'Walking' : 'Driving'
        })
      }
    }
  });
}

const fetchNearbyPharmacies = async () => {
  const {latitude, longitude} = userLocation.value;
  if (!latitude || !longitude) return;

  try {
    const response = await axios.get(route('api.pharmacies.nearest'), {
      params: {latitude, longitude},
    });

    const pharmacies = response.data;

    // Reset previous selected marker icon
    pharmacyMarkers.value.forEach(m => {
      if (m.options.icon === greenIcon) {
        m.setIcon(blueIcon);
      }
    })

    // Clear existing markers
    pharmacyMarkers.value.forEach(marker => marker.remove());
    pharmacyMarkers.value = [];

    // Add new markers
    pharmacies.forEach(pharmacy => {
      const marker = L.marker([pharmacy.latitude, pharmacy.longitude])
        .addTo(map.value!)
        .on('click', () => {
          showPharmacyInfo(pharmacy.uuid)
          marker.setIcon(greenIcon);
        })

      pharmacyMarkers.value.push(marker);
    });
  } catch (error) {
    console.error('Error fetching nearby pharmacies:', error);
  }
};

const navigateToPharmacy = (coordinates: { latitude: number; longitude: number }, mode: string) => {
  selectedPharmacy.value = {
    latitude: coordinates.latitude,
    longitude: coordinates.longitude,
  };

  const {latitude, longitude} = userLocation.value;

  const myLocation = {
    latitude: latitude,
    longitude: longitude
  }

  if (latitude && longitude) {
    fetchRoute(myLocation, selectedPharmacy.value, mode);
  }
};

const fetchRoute = async (
  start: { latitude: number; longitude: number },
  end: { latitude: number; longitude: number },
  mode: string
) => {

  try {
    const response = await fetch(`https://api.openrouteservice.org/v2/directions/${mode}`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': API_KEY,
      },
      body: JSON.stringify({
        coordinates: [
          [start.longitude, start.latitude],
          [end.longitude, end.latitude],
        ],
      }),
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();

    if (data.routes.length === 0) {
      throw new Error('No route found');
    }

    const route = data.routes[0]; // Access the first route
    directions.value = route.segments[0].steps; // Get steps from the first segment

    // Decode the polyline geometry into coordinates
    const decodedCoordinates = polyline.decode(route.geometry);
    drawRoute(decodedCoordinates);

    // Set the distance and duration
    distanceRemaining.value = route.summary.distance;
    durationRemaining.value = route.summary.duration;
  } catch (error) {
    console.error('Error fetching route:', error);
  }
};

/*const drawRoute = (coordinates: number[][]) => {
    if (!map.value) return;

    const latLngs = coordinates.map(([lng, lat]) => [lng, lat]);

    if (routeLayer.value) {
        map.value.removeLayer(routeLayer.value);
    }

    routeLayer.value = L.polyline(latLngs, { color: 'red', weight: 3 }).addTo(map.value);

    // 1. Calculate bounds *before* fitting the map
    const bounds = routeLayer.value.getBounds();

    // 2. Use a combination of events for maximum reliability:
    map.value.once('zoomend', () => {
        map.value!.fitBounds(bounds);
    });
    map.value.once('moveend', () => {
        map.value!.fitBounds(bounds);
    });

    // 3. Set the view and trigger the events. Use padding to avoid cutting off the route
    map.value.fitBounds(bounds, { padding: [50, 50] });
};*/

const drawRoute = async (coordinates: number[][]) => {
  if (routeLayer.value) map.value.removeLayer(routeLayer.value);

  try {
    const routeCoords = coordinates.map(([lng, lat]) => [lng, lat]);
    routeLayer.value = L.polyline(routeCoords, {color: 'blue', weight: 4}).addTo(map.value);
    map.value.fitBounds(routeLayer.value.getBounds());
  } catch (error) {
    console.error('Error fetching route:', error);
  }
};

/*const drawRoute = (coordinates: number[][]) => {
  const latLngs = coordinates.map(([lng, lat]) => [lat, lng]);

  if (!map.value || !map.value._loaded) return; // Ensure the map is ready

  if (routeLayer.value) {
    routeLayer.value.setLatLngs(latLngs); // Update existing route
  } else {
    routeLayer.value = L.polyline(latLngs, { color: 'blue', weight: 5 }).addTo(map.value);
  }

  map.value.fitBounds(routeLayer.value.getBounds()); // Adjust view
}*/

/*const initMap = () => {
  watch(
    () => userLocation.value,
    (newLocation) => {
      if (!newLocation || !newLocation.latitude || !newLocation.longitude) return;

      const { latitude, longitude } = newLocation;

      if (!map.value) {
        // Initialize the map
        map.value = L.map('map', {
          center: [latitude, longitude],
          zoom: 13,
          layers: [
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: 'OpenStreetMap',
            }),
          ],
        });

        userMarker.value = L.marker([latitude, longitude]).addTo(map.value);
      } else {
        // Update user marker position
        userMarker.value!.setLatLng([latitude, longitude]);
        map.value.setView([latitude, longitude]);
      }

      fetchNearbyPharmacies(); // Fetch pharmacies whenever the location updates
    },
    { immediate: true }
  );
};*/

const initMap = () => {
  watch(
    () => userLocation.value,
    (newLocation) => {
      if (!newLocation || !newLocation.latitude || !newLocation.longitude) return;

      const {latitude, longitude} = newLocation;

      if (!map.value) {
        // Initialize the map only if it doesn't exist yet
        map.value = L.map('map', {
          center: [latitude, longitude],
          zoom: 13,
          layers: [
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
              maxZoom: 19,
              attribution: 'OpenStreetMap',
            }),
          ],
        });
      }

      // Update user marker position regardless of map initialization state
      userMarker.value = L.marker([latitude, longitude]).addTo(map.value);

      // Set user marker with red icon
      if (userMarker.value) {
        userMarker.value.setLatLng([latitude, longitude]);
      } else {
        userMarker.value = L.marker([latitude, longitude], {icon: redIcon}).addTo(map.value); // Use red icon
      }

      map.value.setView([latitude, longitude]);

      fetchNearbyPharmacies(); // Fetch pharmacies whenever the location updates
    },
    {immediate: true}
  );
};

onMounted(() => {
  startWatching();
  initMap();
});

onUnmounted(() => {
  stopWatching();
});
</script>

<template>
  <div class="navigation-container">

    <div id="map" style="height: 600px; z-index: 0;">

      <div v-if="! map" class="flex h-full items-center justify-center w-full">
        <Button disabled variant="ghost">
          <svg
            style="height: 2rem; width: 2rem"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
            <radialGradient id="a12" cx=".66" fx=".66" cy=".3125" fy=".3125" gradientTransform="scale(1.5)">
              <stop offset="0" stop-color="#FF156D"></stop>
              <stop offset=".3" stop-color="#FF156D" stop-opacity=".9"></stop>
              <stop offset=".6" stop-color="#FF156D" stop-opacity=".6"></stop>
              <stop offset=".8" stop-color="#FF156D" stop-opacity=".3"></stop>
              <stop offset="1" stop-color="#FF156D" stop-opacity="0"></stop>
            </radialGradient>
            <circle
              transform-origin="center" fill="none" stroke="url(#a12)"
              stroke-width="15" stroke-linecap="round" stroke-dasharray="200 1000"
              stroke-dashoffset="0" cx="100" cy="100" r="70">
              <animateTransform
                type="rotate" attributeName="transform" calcMode="spline"
                dur="2" values="360;0" keyTimes="0;1" keySplines="0 0 1 1"
                repeatCount="indefinite">
              </animateTransform>
            </circle>
            <circle
              transform-origin="center" fill="none" opacity=".2"
              stroke="#FF156D" stroke-width="15" stroke-linecap="round"
              cx="100" cy="100" r="70">
            </circle>
          </svg>

          <span class="animate-pulse">Loading Map. Please wait ...</span>
        </Button>
      </div>

    </div>

    <MapDirections
      :directions="directions"
      :distance-remaining="distanceRemaining"
      :duration-remaining="durationRemaining"
      :map-mode="mapMode"
      v-if="directions.length"
      class="absolute w-64 overflow-y-auto bottom-5 sm:bottom-24 max-h-64 right-2 scrollbar-none"/>

  </div>
</template>

<style>
.navigation-container {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}
</style>
