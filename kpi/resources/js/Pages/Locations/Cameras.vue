<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({ room: Object, building: Object, cameras: Array })
</script>

<template>
  <Head title="Lokasi • CCTV" />
  <div class="p-6">
    <div class="mb-3"><Link :href="`/locations/${building.id}/rooms`" class="text-blue-600">← Ruangan</Link></div>
    <h1 class="text-xl font-bold mb-4">{{ building.name }} • {{ room.name }} • CCTV</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
      <Link v-for="c in cameras" :key="c.id" :href="`/cameras/${c.id}/live`" class="p-4 rounded border bg-white dark:bg-gray-800">
        <div class="font-semibold">{{ c.name }}</div>
        <div class="text-xs opacity-60">Status: {{ c.status }}</div>
      </Link>
    </div>
  </div>
</template>