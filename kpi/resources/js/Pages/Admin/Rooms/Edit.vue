<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({ room: Object, buildings: Array })

const form = useForm({
  building_id: props.room?.building_id ?? (props.buildings[0]?.id ?? null),
  name: props.room?.name ?? '',
  floor: props.room?.floor ?? '',
  description: props.room?.description ?? '',
})

function submit() {
  if (props.room) form.put(`/admin/rooms/${props.room.id}`)
  else form.post('/admin/rooms')
}
</script>

<template>
  <Head :title="props.room ? 'Edit Room' : 'Add Room'" />
  <div class="p-6 max-w-2xl">
    <div class="mb-4"><Link href="/admin/rooms" class="text-blue-600">← Back</Link></div>
    <div class="space-y-3">
      <select v-model="form.building_id" class="w-full border p-2 rounded">
        <option v-for="b in buildings" :key="b.id" :value="b.id">{{ b.name }}</option>
      </select>
      <input v-model="form.name" placeholder="Name" class="w-full border p-2 rounded" />
      <input v-model="form.floor" placeholder="Floor" class="w-full border p-2 rounded" />
      <textarea v-model="form.description" placeholder="Description" class="w-full border p-2 rounded"></textarea>
      <button @click="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save</button>
    </div>
  </div>
</template>