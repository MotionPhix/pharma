<template>
  <div class="map-layout">
    <!-- Top Navigation Bar -->
    <div class="top-bar">
      <slot name="top-bar" />
    </div>

    <!-- Map Container -->
    <div id="map-housing">
      <slot name="map" />
    </div>

    <!-- Bottom Navigation Panel -->
    <div class="bottom-panel">
      <slot name="navigation-panel" />
    </div>
  </div>
</template>

<style>
.map-layout {
  display: flex;
  flex-direction: column;
  height: 100vh;
}

.top-bar {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  z-index: 10;
  background: white;
  padding: 10px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
}

#map-housing {
  flex-grow: 1;
  margin-top: 50px;
  height: calc(100vh - 150px); /* Adjusted height to account for the bottom panel */
}

.bottom-panel {
  position: fixed;
  bottom: 0;
  left: 0;
  width: 100%;
  background: rgba(255, 255, 255, 0.9);
  padding: 15px;
  box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2);
  overflow-x: auto;
  white-space: nowrap;
  @apply scrollbar-none scroll-smooth;
}
</style>
<script setup>
</script>
