<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  buildings: Object,
})
</script>

<template>
  <Head title="Admin • Buildings" />
  <div class="p-6">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-bold">Buildings</h1>
      <div class="flex gap-2">
        <a href="/admin/buildings/export" class="px-3 py-1.5 rounded bg-slate-700 text-white">Export XLSX</a>
        <Link href="/admin/buildings/create" class="px-3 py-1.5 rounded bg-blue-600 text-white">Add</Link>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left border-b">
            <th class="p-2">ID</th>
            <th class="p-2">Name</th>
            <th class="p-2">Slug</th>
            <th class="p-2">Lat</th>
            <th class="p-2">Lng</th>
            <th class="p-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="b in buildings.data" :key="b.id" class="border-b">
            <td class="p-2">{{ b.id }}</td>
            <td class="p-2">{{ b.name }}</td>
            <td class="p-2">{{ b.slug }}</td>
            <td class="p-2">{{ b.latitude }}</td>
            <td class="p-2">{{ b.longitude }}</td>
            <td class="p-2">
              <Link :href="`/admin/buildings/${b.id}/edit`" class="text-blue-600">Edit</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mt-4 flex gap-2">
      <Link v-if="buildings.prev_page_url" :href="buildings.prev_page_url" class="px-3 py-1 rounded border">Prev</Link>
      <Link v-if="buildings.next_page_url" :href="buildings.next_page_url" class="px-3 py-1 rounded border">Next</Link>
    </div>
  </div>
</template>