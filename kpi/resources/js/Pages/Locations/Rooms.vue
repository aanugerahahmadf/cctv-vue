<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({ building: Object, rooms: Array })
</script>

<template>
  <Head title="Lokasi • Ruangan" />
  <div class="p-6">
    <div class="mb-3"><Link href="/locations" class="text-blue-600">← Gedung</Link></div>
    <h1 class="text-xl font-bold mb-4">{{ building.name }} • Ruangan</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
      <Link v-for="r in rooms" :key="r.id" :href="`/locations/rooms/${r.id}/cameras`" class="p-4 rounded border bg-white dark:bg-gray-800">
        <div class="font-semibold">{{ r.name }}</div>
      </Link>
    </div>
  </div>
</template>