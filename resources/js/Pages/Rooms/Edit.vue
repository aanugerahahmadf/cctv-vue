<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({ room: Object, buildings: Array });

const form = useForm({
  building_id: props.room.building_id,
  name: props.room.name,
  floor: props.room.floor || '',
  description: props.room.description || '',
});

const submit = () => form.put(route('admin.rooms.update', props.room.id));
</script>

<template>
  <AdminLayout>
    <Head title="Edit Room" />

    <div class="max-w-2xl">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Edit Room</h1>

      <form @submit.prevent="submit" class="grid gap-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-6 rounded-lg">
        <div>
          <InputLabel value="Building" />
          <select v-model="form.building_id" class="mt-1 w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm p-2">
            <option v-for="b in buildings" :key="b.id" :value="b.id">{{ b.name }}</option>
          </select>
          <InputError :message="form.errors.building_id" class="mt-2" />
        </div>
        <div>
          <InputLabel value="Name" />
          <TextInput v-model="form.name" type="text" class="mt-1 w-full" required />
          <InputError :message="form.errors.name" class="mt-2" />
        </div>
        <div>
          <InputLabel value="Floor" />
          <TextInput v-model="form.floor" type="text" class="mt-1 w-full" />
          <InputError :message="form.errors.floor" class="mt-2" />
        </div>
        <div>
          <InputLabel value="Description" />
          <TextInput v-model="form.description" type="text" class="mt-1 w-full" />
          <InputError :message="form.errors.description" class="mt-2" />
        </div>
        <div class="flex justify-end">
          <PrimaryButton :disabled="form.processing">Update</PrimaryButton>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>