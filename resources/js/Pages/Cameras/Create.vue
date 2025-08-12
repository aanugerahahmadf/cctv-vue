<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({ buildings: Array, rooms: Array });

const form = useForm({
  building_id: props.buildings?.[0]?.id || '',
  room_id: '',
  name: '',
  ip_address: '',
  rtsp_url: '',
  status: 'online',
  latitude: '',
  longitude: '',
  is_public: true,
});

const submit = () => form.post(route('admin.cameras.store'));
</script>

<template>
  <AdminLayout>
    <Head title="Create Camera" />

    <div class="max-w-3xl">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Create Camera</h1>

      <form @submit.prevent="submit" class="grid gap-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <InputLabel value="Building" />
            <select v-model="form.building_id" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm p-2">
              <option v-for="b in buildings" :key="b.id" :value="b.id">{{ b.name }}</option>
            </select>
            <InputError :message="form.errors.building_id" class="mt-2" />
          </div>
          <div>
            <InputLabel value="Room (optional)" />
            <select v-model="form.room_id" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm p-2">
              <option value="">-</option>
              <option v-for="r in rooms" :key="r.id" :value="r.id">{{ r.name }}</option>
            </select>
            <InputError :message="form.errors.room_id" class="mt-2" />
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <InputLabel value="Name" />
            <TextInput v-model="form.name" type="text" class="mt-1 w-full" required />
            <InputError :message="form.errors.name" class="mt-2" />
          </div>
          <div>
            <InputLabel value="Status" />
            <select v-model="form.status" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm p-2">
              <option value="online">Online</option>
              <option value="offline">Offline</option>
              <option value="maintenance">Maintenance</option>
            </select>
            <InputError :message="form.errors.status" class="mt-2" />
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
          <div>
            <InputLabel value="IP Address" />
            <TextInput v-model="form.ip_address" type="text" class="mt-1 w-full" required />
            <InputError :message="form.errors.ip_address" class="mt-2" />
          </div>
          <div>
            <InputLabel value="RTSP URL" />
            <TextInput v-model="form.rtsp_url" type="text" class="mt-1 w-full" />
            <InputError :message="form.errors.rtsp_url" class="mt-2" />
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
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
        <div class="flex items-center gap-2">
          <input id="is_public" type="checkbox" v-model="form.is_public" class="rounded border-gray-300 dark:border-gray-700" />
          <label for="is_public" class="text-sm text-gray-700 dark:text-gray-300">Public</label>
        </div>
        <div class="flex justify-end">
          <PrimaryButton :disabled="form.processing">Save</PrimaryButton>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>