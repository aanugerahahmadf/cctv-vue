<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  users: Object,
});
</script>

<template>
  <AdminLayout>
    <Head title="Admin - Users" />

          <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
        <div class="flex gap-2">
          <a :href="route('admin.export.users')" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-gray-700 to-gray-900 shadow-[0_8px_24px_rgba(31,41,55,0.35)] hover:shadow-[0_10px_28px_rgba(31,41,55,0.5)] hover:scale-[1.015] focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">Export CSV</a>
        </div>
      </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left">ID</th>
            <th class="px-3 py-2 text-left">Name</th>
            <th class="px-3 py-2 text-left">Email</th>
            <th class="px-3 py-2 text-left">Verified</th>
            <th class="px-3 py-2 text-left">Created</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users.data" :key="u.id" class="border-t border-gray-100 dark:border-gray-700">
            <td class="px-3 py-2">{{ u.id }}</td>
            <td class="px-3 py-2">{{ u.name }}</td>
            <td class="px-3 py-2">{{ u.email }}</td>
            <td class="px-3 py-2">
              <span :class="u.email_verified_at ? 'text-green-600' : 'text-red-600'">
                {{ u.email_verified_at ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="px-3 py-2">{{ new Date(u.created_at).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>

      <div class="p-3 flex justify-between text-xs text-gray-500 dark:text-gray-400">
        <div>
          Showing {{ users.from }} - {{ users.to }} of {{ users.total }}
        </div>
        <div class="flex gap-1">
          <Link v-for="link in users.links" :key="link.url || link.label" :href="link.url || '#'" v-html="link.label" :class="['px-2 py-1 rounded', link.active ? 'bg-blue-600 text-white' : '']" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
