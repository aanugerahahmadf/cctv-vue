<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
  name: '',
  slug: '',
  description: '',
  latitude: '',
  longitude: '',
});

const submit = () => {
  form.post(route('admin.buildings.store'));
};
</script>

<template>
  <AdminLayout>
    <Head title="Create Building" />

    <div class="max-w-2xl">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Create Building</h1>

      <form @submit.prevent="submit" class="grid gap-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 rounded-lg">
        <div>
          <InputLabel value="Name" />
          <TextInput v-model="form.name" type="text" class="mt-1 w-full" required />
          <InputError :message="form.errors.name" class="mt-2" />
        </div>
        <div>
          <InputLabel value="Slug" />
          <TextInput v-model="form.slug" type="text" class="mt-1 w-full" required />
          <InputError :message="form.errors.slug" class="mt-2" />
        </div>
        <div>
          <InputLabel value="Description" />
          <TextInput v-model="form.description" type="text" class="mt-1 w-full" />
          <InputError :message="form.errors.description" class="mt-2" />
        </div>
        <div class="grid grid-cols-2 gap-3">
          <div>
            <InputLabel value="Latitude" />
            <TextInput v-model="form.latitude" type="number" step="any" class="mt-1 w-full" />
            <InputError :message="form.errors.latitude" class="mt-2" />
          </div>
          <div>
            <InputLabel value="Longitude" />
            <TextInput v-model="form.longitude" type="number" step="any" class="mt-1 w-full" />
            <InputError :message="form.errors.longitude" class="mt-2" />
          </div>
        </div>
        <div class="flex justify-end">
          <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>