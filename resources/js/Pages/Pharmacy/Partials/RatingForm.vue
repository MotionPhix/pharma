<script setup lang="ts">
import {ref} from "vue";
import {Label} from "@/Components/ui/label";
import {useForm, usePage} from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import {Button} from "@/Components/ui/button";
import {Card, CardContent, CardFooter, CardHeader, CardTitle} from "@/Components/ui/card";
import MazTextarea from 'maz-ui/components/MazTextarea'
import { useToast } from 'maz-ui'

const {uuid} = defineProps<{
  name: string
  uuid: string
}>()

const toast = useToast()
const page = usePage()

const form = useForm({
  rating: 1,
  review: ''
})

const ratingModal = ref()

const onSubmit = () => {
  form.post(route('pharmacies.ratings.handle', uuid), {
    onSuccess: () => {
      ratingModal.value.close()
      toast.info(page.props.status)
    }
  })
}
</script>

<template>
  <GlobalModal
    ref="ratingModal"
    v-slot="{ close }">

    <Card>
      <CardHeader>
        <CardTitle>
          Rate {{ name }}
        </CardTitle>
      </CardHeader>

      <CardContent
        class="max-h-64 overflow-y-auto grid grid-cols-1 gap-4">
        <div>
          <Label for="rating">Rating — {{ form.rating }}</Label>
          <Ratings
            v-model="form.rating"
            :starSize="32"
            starColor="#ff9800"
            inactiveColor="#bfbfbf"
            :numberOfStars="5"
            :disableClick="false"
          />

          <InputError class="mt-2" :message="form.errors.rating"/>
        </div>

        <div>
          <Label for="review">Review</Label>
          <MazTextarea
            v-model="form.review"
            placeholder="Leave a review"
            id="review" name="review"/>

          <InputError class="mt-2" :message="form.errors.review"/>
        </div>
      </CardContent>

      <CardFooter class="py-2 border-t gap-2 justify-end">
        <Button
          @click="close"
          variant="ghost"
          type="button">
          Cancel
        </Button>

        <Button
          :disabled="form.progress"
          @click="onSubmit">
          Rate
        </Button>
      </CardFooter>
    </Card>

  </GlobalModal>
</template>
