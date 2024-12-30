<script setup lang="ts">
import {ref} from 'vue';
import axios from 'axios';
import {Swiper, SwiperSlide} from 'swiper/vue';
import 'swiper/css';
import {router, usePage} from '@inertiajs/vue3';
import {Button} from '@/Components/ui/button';
import {visitModal} from '@inertiaui/modal-vue'

withDefaults(
  defineProps<{
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
    }>
  }>(),
  {
    pharmacies: () => []
  }
);

const page = usePage()

const toggleFavorite = (pharmacy: { is_favorite: boolean, uuid: string }) => {
  if (!page.props.auth.user) {
    return router.get('/login', {replace: true})
  }

  const isFavorite = ref(pharmacy.is_favorite);

  const action = isFavorite.value
    ? axios.delete(route('favorites.remove', pharmacy.uuid))
    : axios.post(route('favorites.add', pharmacy.uuid));

  action.then(() => {
    isFavorite.value = !isFavorite.value;
    pharmacy.is_favorite = isFavorite.value;
  });
};

function openRating(uuid) {
  visitModal(route('pharmacies.ratings.index', uuid), {
    navigate: true
  })
}
</script>

<template>

  <Swiper
    :space-between="15"
    class="flex flex-col h-full"
    :breakpoints="{
      640: { slidesPerView: 2 },
      768: { slidesPerView: 3 },
      1024: { slidesPerView: 3 }
      }">
    <SwiperSlide
      v-for="pharmacy in pharmacies" :key="pharmacy.id"
      class="h-full border border-gray-200 rounded-lg shadow-sm card">
      <article class="h-full flex flex-col">
        <div class="p-4 card-header bg-gray-50">

          <h3 class="text-lg font-bold text-gray-800">
            {{ pharmacy.name }}
          </h3>

          <div class="flex items-center justify-between text-sm text-gray-600">
            {{ pharmacy.address }}
          </div>
        </div>

        <div class="flex-1"></div>

        <div class="px-4 my-2 flex items-center gap-2">
          <Button
            size="sm"
            @click="toggleFavorite"
            class="size-8 hover:bg-blue-700"
            :class="{ 'bg-green-500': pharmacy.is_favorite }">
            <svg
              xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
              <path
                d="M11.5 8H20.196C20.8208 8 21.1332 8 21.3619 8.10084C22.3736 8.5469 21.9213 9.67075 21.7511 10.4784C21.7187 10.6318 21.6188 10.7251 21.5 10.8013M7.5 8H3.80397C3.17922 8 2.86684 8 2.63812 8.10084C1.6264 8.5469 2.07874 9.67075 2.24894 10.4784C2.27952 10.6235 2.37896 10.747 2.51841 10.8132C3.09673 11.0876 3.50177 11.6081 3.60807 12.2134L4.20066 15.5878C4.46138 17.0725 4.55052 19.1942 5.8516 20.2402C6.8062 21 8.18162 21 10.9325 21H12"
                stroke="currentColor" :stroke-width="pharmacy.is_favorite ? '2.5' : '1.5'" stroke-linecap="round"/>
              <path
                d="M14.1418 13.4418C15.3486 12.7108 16.4018 13.0054 17.0345 13.4747C17.294 13.6671 17.4237 13.7633 17.5 13.7633C17.5763 13.7633 17.706 13.6671 17.9655 13.4747C18.5982 13.0054 19.6514 12.7108 20.8582 13.4418C22.4419 14.4013 22.8002 17.5666 19.1472 20.237C18.4514 20.7457 18.1035 21 17.5 21C16.8965 21 16.5486 20.7457 15.8528 20.237C12.1998 17.5666 12.5581 14.4013 14.1418 13.4418Z"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M6.5 11L10 3M15 3L17.5 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
          </Button>

          <Button
            size="sm"
            @click="openRating(pharmacy.uuid)"
            class="text-sm text-white bg-yellow-600 font--medium size-8 hover:bg-yellow-700">
            {{ pharmacy.average_rating > 1 ? pharmacy.average_rating : '⭐' }}
          </Button>
        </div>

        <div class="p-4 divide-y card-body">
          <div class="pb-2">
            <p class="text-sm font-bold text-gray-800">Phone</p>
            <p class="flex items-center gap-2 text-sm text-gray-600">
              <svg
                class="w-4 h-4 shrink-0"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                <path
                  d="M4.74038 14.3685L6.69351 12.9816C7.24445 12.5904 7.80305 12.3282 8.44034 12.1585C9.17201 11.9636 9.5 11.5644 9.5 10.711C9.5 8.54628 14.5 8.31594 14.5 10.711C14.5 11.5644 14.828 11.9636 15.5597 12.1585C16.202 12.3295 16.7599 12.5934 17.3065 12.9816L19.2596 14.3685C20.1434 14.9961 20.5547 15.2995 20.7842 15.7819C21 16.2358 21 16.768 21 17.8324C21 19.7461 21 20.703 20.4642 21.3164C19.8152 22.0593 18.128 21.9955 17.0917 21.9955H6.90833C5.87197 21.9955 4.21909 22.0986 3.5358 21.3164C3 20.703 3 19.7461 3 17.8324C3 16.768 3 16.2358 3.21584 15.7819C3.44526 15.2995 3.85662 14.9961 4.74038 14.3685Z"
                  stroke="currentColor" stroke-width="1.5"/>
                <path
                  d="M14 17C14 18.1046 13.1046 19 12 19C10.8954 19 10 18.1046 10 17C10 15.8954 10.8954 15 12 15C13.1046 15 14 15.8954 14 17Z"
                  stroke="currentColor" stroke-width="1.5"/>
                <path
                  d="M6.96014 3.69772C5.6417 4.07415 4.69384 4.54112 3.82645 5.10455C2.45318 5.9966 1.86443 7.60404 2.02607 9.15513C2.09439 9.81068 2.62064 10.1241 3.23089 9.95455C3.69451 9.82571 4.15888 9.7003 4.61961 9.56364C5.96706 9.16397 6.28399 8.67812 6.47124 7.29885L6.96014 3.69772ZM6.96014 3.69772C10.2186 2.76743 13.7814 2.76743 17.0399 3.69772M17.0399 3.69772C18.3583 4.07415 19.3062 4.54112 20.1735 5.10455C21.5468 5.9966 22.1356 7.60404 21.9739 9.15513C21.9056 9.81068 21.3794 10.1241 20.7691 9.95455C20.3055 9.82571 19.8411 9.7003 19.3804 9.56364C18.0329 9.16397 17.716 8.67812 17.5288 7.29885L17.0399 3.69772Z"
                  stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
              </svg>

              <span>{{ pharmacy.phone }}</span>
            </p>
          </div>

          <div class="py-2">
            <p class="text-sm font-bold text-gray-800">Email</p>
            <p class="flex items-center gap-2 text-sm text-gray-600">
              <svg
                class="w-4 h-4 shrink-0"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                <path d="M7 8.5L9.94202 10.2394C11.6572 11.2535 12.3428 11.2535 14.058 10.2394L17 8.5"
                      stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path
                  d="M2.01576 13.4756C2.08114 16.5411 2.11382 18.0739 3.24495 19.2093C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C11.0393 20.5122 12.9607 20.5122 14.9012 20.4634C18.0497 20.3843 19.6239 20.3448 20.755 19.2093C21.8862 18.0739 21.9189 16.5411 21.9842 13.4756C22.0053 12.4899 22.0053 11.51 21.9842 10.5244C21.9189 7.45883 21.8862 5.92606 20.755 4.79063C19.6239 3.6552 18.0497 3.61565 14.9012 3.53654C12.9607 3.48778 11.0393 3.48778 9.09882 3.53653C5.95033 3.61563 4.37608 3.65518 3.24495 4.79062C2.11382 5.92605 2.08113 7.45882 2.01576 10.5243C1.99474 11.51 1.99474 12.4899 2.01576 13.4756Z"
                  stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
              </svg>

              <span class="truncate">{{ pharmacy.email }}</span>
            </p>
          </div>

          <div class="pt-2">
            <p class="text-sm font-bold text-gray-800">Website</p>
            <a
              :href="pharmacy.website"
              target="_blank"
              rel="noopener noreferrer"
              class="flex items-center gap-2 text-sm text-blue-600 hover:underline">
              <svg
                class="w-4 h-4 shrink-0"
                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                <path
                  d="M11.0991 3.00012C7.45013 3.00669 5.53932 3.09629 4.31817 4.31764C3.00034 5.63568 3.00034 7.75704 3.00034 11.9997C3.00034 16.2424 3.00034 18.3638 4.31817 19.6818C5.63599 20.9999 7.75701 20.9999 11.9991 20.9999C16.241 20.9999 18.3621 20.9999 19.6799 19.6818C20.901 18.4605 20.9906 16.5493 20.9972 12.8998"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <path
                  d="M20.556 3.49612L11.0487 13.0586M20.556 3.49612C20.062 3.00151 16.7343 3.04761 16.0308 3.05762M20.556 3.49612C21.05 3.99074 21.0039 7.32273 20.9939 8.02714"
                  stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>

              <span class="truncate">{{ pharmacy.website }}</span>
            </a>
          </div>
        </div>
      </article>
    </SwiperSlide>
  </Swiper>
</template>
