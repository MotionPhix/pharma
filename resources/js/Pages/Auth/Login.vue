<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Separator } from '@/Components/ui/separator';
import { Button } from '@/Components/ui/button';
import AppLogo from '@/Components/AppLogo.vue';
import { ref } from 'vue';

const loginModal = ref()

const form = useForm({
  email: '',
});

const submitEmailLogin = () => {
  form.post(route('auth.request.token'), {
    onSuccess: () => {
      form.reset()
      loginModal.value.close()
    }
  });
};

const redirectToProvider = (provider) => {
  window.location.href = route('auth.socialite.redirect', { provider });
};
</script>

<template>
  <Head title="Log in" />

  <GlobalModal
    ref="loginModal"
    max-width="md"
    v-slot="{ close }"
    :close-button="false"
    class="grid grid-cols-1"
    panel-classes="shadow-md rounded-xl bg-white sm:p-6"
    :close-explicitly="true">

    <div class="flex">
      <Link href="/">
        <AppLogo class="w-20 h-20 fill-current" />
      </Link>
    </div>

    <Separator class="my-4" />

    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 grid-items-center">
      <Button
        size="lg"
        type="button"
        variant="outline"
        @click="() => redirectToProvider('google')" >
        <svg
          class="w-5 h-5"
          viewBox="-3 0 262 262"
          xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid" fill="#000000"><g stroke-width="0"></g><g stroke-linecap="round" stroke-linejoin="round"></g><g><path d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027" fill="#4285F4"></path><path d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1" fill="#34A853"></path><path d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782" fill="#FBBC05"></path><path d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251" fill="#EB4335"></path>
          </g>
        </svg>
        Sign in with Google
      </Button>

      <Button
        size="lg"
        type="button"
        variant="outline"
        @click="() => redirectToProvider('facebook')">
        <svg viewBox="0 0 266.895 266.895" xmlns="http://www.w3.org/2000/svg" fill="#000000"><g stroke-width="0"></g><g stroke-linecap="round" stroke-linejoin="round"></g><g><path d="M252.164 266.895c8.134 0 14.729-6.596 14.729-14.73V14.73c0-8.137-6.596-14.73-14.729-14.73H14.73C6.593 0 0 6.594 0 14.73v237.434c0 8.135 6.593 14.73 14.73 14.73h237.434z" fill="#485a96"></path><path d="M184.152 266.895V163.539h34.692l5.194-40.28h-39.887V97.542c0-11.662 3.238-19.609 19.962-19.609l21.329-.01V41.897c-3.689-.49-16.351-1.587-31.08-1.587-30.753 0-51.807 18.771-51.807 53.244v29.705h-34.781v40.28h34.781v103.355h41.597z" fill="#ffffff"></path></g></svg>
        Sign in with Facebook
      </Button>
    </div>

    <Separator class="my-4" label="OR" />

    <form @submit.prevent="submitEmailLogin">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          type="email"
          class="block w-full mt-1"
          v-model="form.email"
          autofocus
          placeholder="Enter your email address"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="flex justify-end w-full gap-4 mt-4">
        <Button type="button" variant="ghost" @click="close">
          Cancel
        </Button>

        <PrimaryButton
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing">
          Login
        </PrimaryButton>
      </div>
    </form>
  </GlobalModal>
</template>
