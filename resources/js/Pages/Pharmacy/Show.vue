<template>
  <GlobalModal
  :close-button="false" v-slot="{ close }"
  max-width="md" panel-classes="rounded-xl bg-white"
  :close-explicitly="true">
      <!-- Modal for Pharmacy Details -->
  <div>
      <h2 class="text-xl font-bold">{{ selectedPharmacy.name }}</h2>
      <p>Address: {{ selectedPharmacy.address }}</p>
      <p>Phone: {{ selectedPharmacy.phone }}</p>
      <p>Hours: {{ selectedPharmacy.hours }}</p>
      <p>Services: {{ selectedPharmacy.services.join(', ') }}</p>
      <button @click="closeModal" class="px-4 py-2 text-white bg-red-500 rounded">Close</button>
  </div>
</GlobalModal>
</template>
