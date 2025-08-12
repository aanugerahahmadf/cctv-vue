<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({ rooms: Object })
</script>

<template>
  <Head title="Admin • Rooms" />
  <div class="p-6">
    <div class="flex items-center justify-between mb-4">
      <h1 class="text-xl font-bold">Rooms</h1>
      <div class="flex gap-2">
        <a href="/admin/rooms/export" class="px-3 py-1.5 rounded bg-slate-700 text-white">Export XLSX</a>
        <Link href="/admin/rooms/create" class="px-3 py-1.5 rounded bg-blue-600 text-white">Add</Link>
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead>
          <tr class="text-left border-b">
            <th class="p-2">ID</th>
            <th class="p-2">Room</th>
            <th class="p-2">Building</th>
            <th class="p-2">Floor</th>
            <th class="p-2">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="r in rooms.data" :key="r.id" class="border-b">
            <td class="p-2">{{ r.id }}</td>
            <td class="p-2">{{ r.name }}</td>
            <td class="p-2">{{ r.building?.name }}</td>
            <td class="p-2">{{ r.floor }}</td>
            <td class="p-2">
              <Link :href="`/admin/rooms/${r.id}/edit`" class="text-blue-600">Edit</Link>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="mt-4 flex gap-2">
      <Link v-if="rooms.prev_page_url" :href="rooms.prev_page_url" class="px-3 py-1 rounded border">Prev</Link>
      <Link v-if="rooms.next_page_url" :href="rooms.next_page_url" class="px-3 py-1 rounded border">Next</Link>
    </div>
  </div>
</template>