<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  cameras: Object,
});

const start = (id) => router.post(route('admin.cameras.start', id));
const stop = (id) => router.post(route('admin.cameras.stop', id));
const snap = (id) => router.post(route('admin.cameras.snapshot', id));
const record = (id) => router.post(route('admin.cameras.record', id), { seconds: 10 });
</script>

<template>
  <AdminLayout>
    <Head title="Admin - Cameras" />

    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cameras</h1>
      <div class="flex gap-2">
        <Link :href="route('admin.cameras.create')" class="px-3 py-2 bg-blue-600 text-white rounded-lg">Add Camera</Link>
        <a href="/export/cameras.csv" class="px-3 py-2 bg-gray-700 text-white rounded-lg">Export CSV</a>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">Name</th>
            <th class="px-3 py-2 text-left">IP</th>
            <th class="px-3 py-2 text-left">Building</th>
            <th class="px-3 py-2 text-left">Room</th>
            <th class="px-3 py-2 text-left">Status</th>
            <th class="px-3 py-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in cameras.data" :key="c.id" class="border-t border-gray-100 dark:border-gray-700">
            <td class="px-3 py-2">
              <Link :href="route('cameras.show', c.id)" class="text-blue-600 dark:text-blue-400 hover:underline">{{ c.name }}</Link>
            </td>
            <td class="px-3 py-2">{{ c.ip_address }}</td>
            <td class="px-3 py-2">{{ c.building || '-' }}</td>
            <td class="px-3 py-2">{{ c.room || '-' }}</td>
            <td class="px-3 py-2">
              <span :class="{
                'text-green-600': c.status === 'online',
                'text-red-600': c.status === 'offline',
                'text-yellow-600': c.status === 'maintenance',
              }">{{ c.status }}</span>
            </td>
            <td class="px-3 py-2 flex gap-2">
              <button @click="start(c.id)" class="px-2 py-1 bg-green-600 text-white rounded">Start</button>
              <button @click="stop(c.id)" class="px-2 py-1 bg-red-600 text-white rounded">Stop</button>
              <button @click="snap(c.id)" class="px-2 py-1 bg-gray-700 text-white rounded">Snapshot</button>
              <button @click="record(c.id)" class="px-2 py-1 bg-purple-700 text-white rounded">Record</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="p-3 flex justify-between text-xs text-gray-500 dark:text-gray-400">
        <div>
          Showing {{ cameras.from }} - {{ cameras.to }} of {{ cameras.total }}
        </div>
        <div class="flex gap-1">
          <Link v-for="link in cameras.links" :key="link.url || link.label" :href="link.url || '#'" v-html="link.label" :class="['px-2 py-1 rounded', link.active ? 'bg-blue-600 text-white' : '']" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
