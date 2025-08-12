<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  rooms: Array,
  stats: Object,
});
</script>

<template>
  <AdminLayout>
    <Head title="Admin - Rooms" />

    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Rooms</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400">Manage rooms per building.</p>
      </div>
      <Link :href="route('admin.rooms.create')" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 shadow-[0_8px_24px_rgba(109,40,217,0.35)] hover:shadow-[0_10px_28px_rgba(109,40,217,0.5)] hover:scale-[1.015]">Add Room</Link>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
      <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
        <div class="text-sm text-gray-600 dark:text-gray-400">Total Rooms</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl">
        <div class="text-sm text-gray-600 dark:text-gray-400">Total Cameras</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.totalCameras }}</div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Name</th>
            <th class="px-3 py-2 text-left">Building</th>
            <th class="px-3 py-2 text-left">Cameras</th>
            <th class="px-3 py-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in rooms" :key="r.id" class="border-t border-gray-100 dark:border-gray-700">
            <td class="px-3 py-2">{{ r.name }}</td>
            <td class="px-3 py-2">{{ r.building?.name }}</td>
            <td class="px-3 py-2">{{ r.cameras_count }}</td>
            <td class="px-3 py-2">
              <Link :href="route('admin.rooms.edit', r.id)" class="text-blue-600 dark:text-blue-400 hover:underline">Edit</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AdminLayout>
</template>