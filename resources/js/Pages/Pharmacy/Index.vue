<script setup lang="ts">
import {ref} from 'vue';
import {usePage} from '@inertiajs/vue3';
import {Input} from '@/Components/ui/input';
import {Button} from '@/Components/ui/button';
import PharmacyCard from '@/Pages/Pharmacy/Partials/PharmacyCard.vue'
import AppLayout from "@/Layouts/AppLayout.vue";

const {pharmacies} = usePage().props as {
  pharmacies: Array<{
    id: number;
    uuid: string;
    name: string;
    address: string;
    phone: string;
    email: string;
    website: string;
    is_favorite?: boolean;
    average_rating?: number;
    distance?: number;
  }>;
};

const searchQuery = ref('');

const filteredPharmacies = async () => {
  const response = await fetch(route('api.pharmacies.search'), {
    method: 'GET',
    body: JSON.stringify({
      q: searchQuery,
    }),
  });

  return await response.json();
}
</script>

<template>
  <AppLayout>
    <div class="max-w-2xl mx-auto px-4 py-12">
      <div class="mb-6">
        <Input
          v-model="searchQuery"
          placeholder="Search pharmacies by name, location, services, distance from you, etc"
          class="w-full"
        />
      </div>

      <h1 class="text-2xl font-bold mb-4">
        Find Nearby Pharmacies
      </h1>

      <PharmacyCard :pharmacies="pharmacies"/>
    </div>
  </AppLayout>
</template>
