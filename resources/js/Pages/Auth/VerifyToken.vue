<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

defineProps<{
  token: string
}>();

const form = useForm({
  token: ''
});

const submit = () => {
  form.post(route('auth.get.user'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Verify Token" />

        <div
          v-if="$page.props.status"
          class="mb-4 text-sm font-medium text-red-600">
          {{ $page.props.status }}
        </div>

        <form @submit.prevent="submit">
          <div class="grid grid-cols-1 gap-2">
            <Label>Verification Token</Label>

            <Input
              v-model="form.token"
              placeholder="Enter the token you received" />

            <InputError :message="form.errors.token" />
          </div>

          <div class="mt-4">
            <PrimaryButton
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing">
              Verify Token
            </PrimaryButton>
          </div>
        </form>
    </GuestLayout>
</template>
