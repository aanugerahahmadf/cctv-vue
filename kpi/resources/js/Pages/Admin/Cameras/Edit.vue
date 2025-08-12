<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({ camera: Object, buildings: Array })

const form = useForm({
  building_id: props.camera?.building_id ?? (props.buildings[0]?.id ?? null),
  name: props.camera?.name ?? '',
  ip_address: props.camera?.ip_address ?? '',
  rtsp_url: props.camera?.rtsp_url ?? '',
  status: props.camera?.status ?? 'offline',
  latitude: props.camera?.latitude ?? '',
  longitude: props.camera?.longitude ?? '',
  is_public: props.camera?.is_public ?? true,
})

function submit() {
  if (props.camera) {
    form.put(`/admin/cameras/${props.camera.id}`)
  } else {
    form.post('/admin/cameras')
  }
}

const mapEl = ref(null)
let map, marker
onMounted(() => {
  map = L.map(mapEl.value).setView([form.latitude || -6.3640, form.longitude || 108.3840], 15)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 20 }).addTo(map)
  marker = L.marker([form.latitude || -6.3640, form.longitude || 108.3840], { draggable: true }).addTo(map)
  marker.on('dragend', () => {
    const { lat, lng } = marker.getLatLng()
    form.latitude = lat.toFixed(6)
    form.longitude = lng.toFixed(6)
  })
  map.on('click', (e) => {
    marker.setLatLng(e.latlng)
    form.latitude = e.latlng.lat.toFixed(6)
    form.longitude = e.latlng.lng.toFixed(6)
  })
})
</script>

<template>
  <Head :title="props.camera ? 'Edit Camera' : 'Add Camera'" />
  <div class="p-6 max-w-2xl">
    <div class="mb-4"><Link href="/admin/cameras" class="text-blue-600">← Back</Link></div>
    <div class="space-y-3">
      <select v-model="form.building_id" class="w-full border p-2 rounded">
        <option v-for="b in buildings" :key="b.id" :value="b.id">{{ b.name }}</option>
      </select>
      <input v-model="form.name" placeholder="Name" class="w-full border p-2 rounded" />
      <input v-model="form.ip_address" placeholder="IP Address" class="w-full border p-2 rounded" />
      <input v-model="form.rtsp_url" placeholder="RTSP URL" class="w-full border p-2 rounded" />
      <select v-model="form.status" class="w-full border p-2 rounded">
        <option value="online">online</option>
        <option value="offline">offline</option>
        <option value="maintenance">maintenance</option>
      </select>
      <div class="grid grid-cols-2 gap-3">
        <input v-model="form.latitude" placeholder="Latitude" class="w-full border p-2 rounded" />
        <input v-model="form.longitude" placeholder="Longitude" class="w-full border p-2 rounded" />
      </div>
      <div ref="mapEl" class="w-full h-60 rounded border"></div>
      <label class="flex items-center gap-2"><input type="checkbox" v-model="form.is_public" /> Public</label>
      <button @click="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Save</button>
    </div>
  </div>
</template>