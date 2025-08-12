<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  stats: Object,
})
</script>

<template>
  <Head title="Admin Dashboard" />
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 p-6">
    <div class="max-w-7xl mx-auto">
      <h1 class="text-2xl font-bold mb-6">KILANG PERTAMINA INTERNASIONAL — ADMIN</h1>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="rounded-xl p-4 bg-white dark:bg-gray-800 shadow">
          <div class="text-sm opacity-70">Total Users</div>
          <div class="text-3xl font-bold">{{ stats.total_users }}</div>
        </div>
        <div class="rounded-xl p-4 bg-white dark:bg-gray-800 shadow">
          <div class="text-sm opacity-70">Total Buildings</div>
          <div class="text-3xl font-bold">{{ stats.total_buildings }}</div>
        </div>
        <div class="rounded-xl p-4 bg-white dark:bg-gray-800 shadow">
          <div class="text-sm opacity-70">Total CCTV</div>
          <div class="text-3xl font-bold">{{ stats.total_cameras }}</div>
        </div>
        <div class="rounded-xl p-4 bg-white dark:bg-gray-800 shadow">
          <div class="text-sm opacity-70">Online / Offline / Maint</div>
          <div class="text-3xl font-bold">{{ stats.online }} / {{ stats.offline }} / {{ stats.maintenance }}</div>
        </div>
      </div>

      <div class="flex gap-3">
        <Link class="px-4 py-2 rounded-lg bg-blue-600 text-white" href="/maps">Maps</Link>
        <Link class="px-4 py-2 rounded-lg bg-slate-700 text-white" href="/admin/users">Users</Link>
        <Link class="px-4 py-2 rounded-lg bg-slate-700 text-white" href="/admin/locations">Locations</Link>
        <Link class="px-4 py-2 rounded-lg bg-slate-700 text-white" href="/admin/cameras">Cameras</Link>
      </div>
    </div>
  </div>
</template>