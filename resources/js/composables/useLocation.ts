import { ref, onMounted, onUnmounted } from 'vue';

export function useLocation() {
  const userLocation = ref({ latitude: null, longitude: null, accuracy: null });
  const error = ref(null);
  let watcherId: number | null = null;

  const options = {
    enableHighAccuracy: true,
    timeout: 5000,
    maximumAge: 0,
  };

  const startWatching = () => {
    if ('geolocation' in navigator) {
      watcherId = navigator.geolocation.watchPosition(
        position => {
          userLocation.value = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy,
          };
          error.value = null;
        },
        err => {
          error.value = err.message;
        },
        options
      );
    } else {
      error.value = 'Geolocation is not supported by your browser.';
    }
  };

  const stopWatching = () => {
    if (watcherId !== null) {
      navigator.geolocation.clearWatch(watcherId);
      watcherId = null;
    }
  };

  onMounted(() => {
    startWatching();
  });

  onUnmounted(() => {
    stopWatching();
  });

  return { userLocation, error, startWatching, stopWatching };
}
