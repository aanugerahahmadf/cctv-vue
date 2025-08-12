<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ building: Object })

const form = useForm({
  name: props.building?.name ?? '',
  slug: props.building?.slug ?? '',
  latitude: props.building?.latitude ?? '',
  longitude: props.building?.longitude ?? '',
  description: props.building?.description ?? '',
})

function submit() {
  if (props.building) {
    form.put(`/admin/buildings/${props.building.id}`)
  } else {
    form.post('/admin/buildings')
  }
}
</script>

<template>
  <Head :title="props.building ? 'Edit Building' : 'Add Building'" />
  <div class="p-6 max-w-2xl">
    <div class="mb-4"><Link href="/admin/buildings" class="text-blue-600">← Back</Link></div>
    <div class="space-y-3">
      <input v-model="form.name" placeholder="Name" class="w-full border p-2 rounded" />
      <input v-model="form.slug" placeholder="Slug (optional)" class="w-full border p-2 rounded" />
      <div class="grid grid-cols-2 gap-3">
        <input v-model="form.latitude" placeholder="Latitude" class="w-full border p-2 rounded" />
        <input v-model="form.longitude" placeholder="Longitude" class="w-full border p-2 rounded" />
      </div>
      <textarea v-model="form.description" placeholder="Description" class="w-full border p-2 rounded"></textarea>
      <button @click="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save</button>
    </div>
  </div>
</template>