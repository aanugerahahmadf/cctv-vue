<script setup>
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({ buildings: Array })
</script>

<template>
  <Head title="Lokasi • Gedung" />
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Nama-Nama Gedung</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
      <Link v-for="b in buildings" :key="b.id" :href="`/locations/${b.id}/rooms`" class="p-4 rounded border bg-white dark:bg-gray-800">
        <div class="font-semibold">{{ b.name }}</div>
        <div class="text-xs opacity-60">{{ b.slug }}</div>
      </Link>
    </div>
  </div>
</template>