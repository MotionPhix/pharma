<script setup lang="ts">
import { Button } from '@/Components/ui/button';
import {
  Card,
  CardContent,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card';
import {
  Tabs,
  TabsContent,
  TabsList,
  TabsTrigger,
} from '@/Components/ui/tabs';
import { Separator } from '@/Components/ui/separator';
import { ref } from 'vue';
import QuickActions from './QuickActions.vue';

const props = defineProps<{
  pharmacy: {
    name: string
    address?: string
    latitude: number
    longitude: number
    phone: string
    email: string
    website?: string
    hours?: string
    services: Array<{
      id: number
      uuid: string
      name: string
      description?: string
    }>;
  };
}>();

const infoModal = ref()

const locate = () => {
  infoModal.value
    .close()
    .emit('getRoute', {
      lat: props.pharmacy.latitude,
      lng: props.pharmacy.longitude
    })
}
</script>

<template>
  <GlobalModal
    ref="infoModal"
    max-width="md"
    position="top"
    v-slot="{ close, emit }"
    :close-button="false"
    panel-classes="shadow-md rounded-xl bg-white sm:p-6"
    :close-explicitly="true">
  <Tabs default-value="info" class="w-full">
    <TabsList class="grid w-full grid-cols-3">
      <TabsTrigger value="info">Pharmacy Info</TabsTrigger>
      <TabsTrigger value="services">Services</TabsTrigger>
      <TabsTrigger value="openinghours">Opening Hours</TabsTrigger>
    </TabsList>

    <TabsContent value="info">
      <Card>
        <CardHeader>
          <CardTitle>{{ pharmacy.name }}</CardTitle>
        </CardHeader>

        <Separator />

        <CardContent
          class="pt-4 overflow-y-auto divide-y scrollbar-thin max-h-72">

          <dl class="max-w-md py-2 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
              <div
                class="flex flex-col">
                  <dt
                    class="mb-1 font-semibold">
                    Address
                  </dt>

                  <dd class="text-gray-500 dark:text-gray-400">
                    {{  pharmacy.address || 'Not available' }}
                  </dd>
              </div>
          </dl>

          <dl class="max-w-md py-2 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
              <div
                class="flex flex-col pb-3">
                  <dt
                    class="mb-1 font-semibold">
                    Phone
                  </dt>

                  <dd class="text-gray-500 dark:text-gray-400">
                    <a
                      class="flex items-center gap-2 text-blue-500"
                      :href="`tel:${pharmacy.phone}`">
                      <svg
                        class="w-5 h-5 shrink-0"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                        <path d="M4.74038 14.3685L6.69351 12.9816C7.24445 12.5904 7.80305 12.3282 8.44034 12.1585C9.17201 11.9636 9.5 11.5644 9.5 10.711C9.5 8.54628 14.5 8.31594 14.5 10.711C14.5 11.5644 14.828 11.9636 15.5597 12.1585C16.202 12.3295 16.7599 12.5934 17.3065 12.9816L19.2596 14.3685C20.1434 14.9961 20.5547 15.2995 20.7842 15.7819C21 16.2358 21 16.768 21 17.8324C21 19.7461 21 20.703 20.4642 21.3164C19.8152 22.0593 18.128 21.9955 17.0917 21.9955H6.90833C5.87197 21.9955 4.21909 22.0986 3.5358 21.3164C3 20.703 3 19.7461 3 17.8324C3 16.768 3 16.2358 3.21584 15.7819C3.44526 15.2995 3.85662 14.9961 4.74038 14.3685Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M14 17C14 18.1046 13.1046 19 12 19C10.8954 19 10 18.1046 10 17C10 15.8954 10.8954 15 12 15C13.1046 15 14 15.8954 14 17Z" stroke="currentColor" stroke-width="1.5" />
                        <path d="M6.96014 3.69772C5.6417 4.07415 4.69384 4.54112 3.82645 5.10455C2.45318 5.9966 1.86443 7.60404 2.02607 9.15513C2.09439 9.81068 2.62064 10.1241 3.23089 9.95455C3.69451 9.82571 4.15888 9.7003 4.61961 9.56364C5.96706 9.16397 6.28399 8.67812 6.47124 7.29885L6.96014 3.69772ZM6.96014 3.69772C10.2186 2.76743 13.7814 2.76743 17.0399 3.69772M17.0399 3.69772C18.3583 4.07415 19.3062 4.54112 20.1735 5.10455C21.5468 5.9966 22.1356 7.60404 21.9739 9.15513C21.9056 9.81068 21.3794 10.1241 20.7691 9.95455C20.3055 9.82571 19.8411 9.7003 19.3804 9.56364C18.0329 9.16397 17.716 8.67812 17.5288 7.29885L17.0399 3.69772Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                      {{ pharmacy.phone }}
                    </a>
                  </dd>
              </div>
          </dl>

          <dl class="max-w-md py-2 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
              <div
                class="flex flex-col">
                  <dt
                    class="mb-1 font-semibold">
                    Email
                  </dt>

                  <dd class="text-gray-500 dark:text-gray-400">
                    <a
                      class="flex items-center gap-2 text-blue-500"
                      :href="`mailto:${pharmacy.email}`">
                      <svg
                        class="w-5 h-5 shrink-0"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                        <path d="M7 8.5L9.94202 10.2394C11.6572 11.2535 12.3428 11.2535 14.058 10.2394L17 8.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M2.01576 13.4756C2.08114 16.5411 2.11382 18.0739 3.24495 19.2093C4.37608 20.3448 5.95033 20.3843 9.09883 20.4634C11.0393 20.5122 12.9607 20.5122 14.9012 20.4634C18.0497 20.3843 19.6239 20.3448 20.755 19.2093C21.8862 18.0739 21.9189 16.5411 21.9842 13.4756C22.0053 12.4899 22.0053 11.51 21.9842 10.5244C21.9189 7.45883 21.8862 5.92606 20.755 4.79063C19.6239 3.6552 18.0497 3.61565 14.9012 3.53654C12.9607 3.48778 11.0393 3.48778 9.09882 3.53653C5.95033 3.61563 4.37608 3.65518 3.24495 4.79062C2.11382 5.92605 2.08113 7.45882 2.01576 10.5243C1.99474 11.51 1.99474 12.4899 2.01576 13.4756Z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round" />
                    </svg>
                      {{ pharmacy.email }}
                    </a>
                  </dd>
              </div>
          </dl>

          <dl class="max-w-md py-2 text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
              <div
                class="flex flex-col">
                  <dt
                    class="mb-1 font-semibold">
                    Website
                  </dt>

                  <dd class="text-gray-500 dark:text-gray-400">
                    <a
                      class="flex items-center gap-2 text-blue-500"
                      :href="pharmacy.website" target="_blank" rel="noopener noreferrer">
                      <svg
                        class="w-5 h-5 shrink-0"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                        <path d="M11.0991 3.00012C7.45013 3.00669 5.53932 3.09629 4.31817 4.31764C3.00034 5.63568 3.00034 7.75704 3.00034 11.9997C3.00034 16.2424 3.00034 18.3638 4.31817 19.6818C5.63599 20.9999 7.75701 20.9999 11.9991 20.9999C16.241 20.9999 18.3621 20.9999 19.6799 19.6818C20.901 18.4605 20.9906 16.5493 20.9972 12.8998" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.556 3.49612L11.0487 13.0586M20.556 3.49612C20.062 3.00151 16.7343 3.04761 16.0308 3.05762M20.556 3.49612C21.05 3.99074 21.0039 7.32273 20.9939 8.02714" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                      {{ pharmacy.website || 'Not available' }}
                    </a>
                  </dd>
              </div>
          </dl>
        </CardContent>

        <CardFooter
          class="flex-row-reverse justify-between gap-2 py-2 border-t-2">
          <QuickActions :close="close" :emit="emit" :pharmacy="pharmacy" />
        </CardFooter>
      </Card>
    </TabsContent>

    <TabsContent value="services">
      <Card>
        <CardHeader>
          <CardTitle>Services Offered</CardTitle>
        </CardHeader>

        <Separator />

        <CardContent
          class="pt-4 overflow-y-auto max-h-72 scrollbar-thin">
          <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700">
              <div
                class="flex gap-2 py-2"
                v-for="service in pharmacy.services" :key="service.uuid">
                <svg
                  class="mt-1 shrink-0 size-6"
                  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none">
                  <path d="M14.9805 7.01556C14.9805 7.01556 15.4805 7.51556 15.9805 8.51556C15.9805 8.51556 17.5687 6.01556 18.9805 5.51556" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M9.99491 2.02134C7.49644 1.91556 5.56618 2.20338 5.56618 2.20338C4.34733 2.29053 2.01152 2.97385 2.01154 6.96454C2.01156 10.9213 1.9857 15.7993 2.01154 17.7439C2.01154 18.932 2.74716 21.7033 5.29332 21.8518C8.38816 22.0324 13.9628 22.0708 16.5205 21.8518C17.2052 21.8132 19.4847 21.2757 19.7732 18.7956C20.0721 16.2263 20.0126 14.4407 20.0126 14.0157" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  <path d="M21.9999 7.01556C21.9999 9.77698 19.7592 12.0156 16.9951 12.0156C14.231 12.0156 11.9903 9.77698 11.9903 7.01556C11.9903 4.25414 14.231 2.01556 16.9951 2.01556C19.7592 2.01556 21.9999 4.25414 21.9999 7.01556Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                  <path d="M6.98053 13.0156H10.9805" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                  <path d="M6.98053 17.0156H14.9805" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>

                <div>
                  <dt
                    class="mb-1 font-semibold">
                    {{ service.name }}
                  </dt>

                  <dd class="text-gray-500 dark:text-gray-400">
                    {{ service.description }}
                  </dd>
                </div>
              </div>
          </dl>
        </CardContent>

        <CardFooter class="justify-end py-2 border-t-2">
          <Button variant="ghost" @click="close">Close</Button>
        </CardFooter>
      </Card>
    </TabsContent>

    <TabsContent value="openinghours">
      <Card>
        <CardHeader>
          <CardTitle>Opening Hours</CardTitle>
        </CardHeader>

        <Separator />

        <CardContent class="overflow-y-auto max-h-72 scrollbar-thin">
          <table
            class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400">
            <thead class="sticky top-0 text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
              <tr>
                <th scope="col" class="px-6 py-3">
                    Days
                </th>

                <th scope="col" class="px-6 py-3">
                    Open
                </th>

                <th scope="col" class="px-6 py-3">
                    Close
                </th>
              </tr>
            </thead>

            <tbody>
                <tr
                  v-for="time in pharmacy.opening_hours" :key="time.id"
                  class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                       {{ time.day }}
                    </th>
                    <td
                      class="px-6 py-4"
                      :class="{ 'col-span-2' : !time.close_time}">
                        {{ time.open_time ?? 'Closed' }}
                    </td>

                    <td class="px-6 py-4" v-if="time.close_time">
                        {{ time.close_time }}
                    </td>
                </tr>
            </tbody>
        </table>
        </CardContent>

        <CardFooter class="justify-end gap-4 py-2 border-t-2">
          <Button variant="ghost" @click="close">Close</Button>
        </CardFooter>
      </Card>
    </TabsContent>
  </Tabs>
</GlobalModal>
</template>
