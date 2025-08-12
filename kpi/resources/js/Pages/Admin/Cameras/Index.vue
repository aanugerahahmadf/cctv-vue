<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  cameras: Object,
})
</script>

<template>
  <Head title="Admin • Cameras" />
  <div class="p-6">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-bold">Cameras</h1>
      <div class="flex gap-2">
        <a href="/admin/cameras/export" class="px-3 py-1.5 rounded bg-slate-700 text-white">Export XLSX</a>
        <Link href="/admin/cameras/create" class="px-3 py-1.5 rounded bg-blue-600 text-white">Add</Link>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left border-b">
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">IP</th>
            <th class="p-2">Status</th>
            <th class="p-2">Building</th>
            <th class="p-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in cameras.data" :key="c.id" class="border-b">
            <td class="p-2">{{ c.id }}</td>
            <td class="p-2">{{ c.name }}</td>
            <td class="p-2">{{ c.ip_address }}</td>
            <td class="p-2">{{ c.status }}</td>
            <td class="p-2">{{ c.building?.name }}</td>
            <td class="p-2">
              <Link :href="`/admin/cameras/${c.id}/edit`" class="text-blue-600">Edit</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mt-4 flex gap-2">
      <Link v-if="cameras.prev_page_url" :href="cameras.prev_page_url" class="px-3 py-1 rounded border">Prev</Link>
      <Link v-if="cameras.next_page_url" :href="cameras.next_page_url" class="px-3 py-1 rounded border">Next</Link>
    </div>
  </div>
</template>