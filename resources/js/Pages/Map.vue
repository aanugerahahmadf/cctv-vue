<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted, ref, watch } from 'vue'
import L from 'leaflet'
import 'leaflet/dist/leaflet.css'

const props = defineProps({
  buildings: Array,
  cameras: Array,
})

const mapEl = ref(null)
let map
let cameraLayer

const statusFilter = ref({ online: true, offline: true, maintenance: true })

function colorFor(status) {
  return status === 'online' ? 'green' : status === 'offline' ? 'red' : 'orange'
}

function markerIcon(color) {
  return L.divIcon({ className: 'marker-dot', html: `<span style="display:inline-block;width:14px;height:14px;border-radius:9999px;background:${color};border:2px solid white;box-shadow:0 0 0 2px ${color};"></span>` })
}

function renderMarkers() {
  if (cameraLayer) cameraLayer.clearLayers()
  cameraLayer = L.layerGroup()

  props.cameras.forEach(c => {
    if (!statusFilter.value[c.status]) return
    if (c.latitude && c.longitude) {
      const m = L.marker([c.latitude, c.longitude], { icon: markerIcon(colorFor(c.status)) })
      m.bindPopup(`<div style='min-width:200px'>
        <div><strong>${c.name}</strong></div>
        <div>Status: <span style='color:${colorFor(c.status)}'>${c.status.toUpperCase()}</span></div>
        <div class='mt-2'><a href='/cameras/${c.id}/live' class='text-blue-600 underline'>Live CCTV</a></div>
      </div>`)
      cameraLayer.addLayer(m)
    }
  })

  cameraLayer.addTo(map)
}

onMounted(() => {
  map = L.map(mapEl.value).setView([-6.3640, 108.3840], 14)
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 20, attribution: '&copy; OpenStreetMap' }).addTo(map)
  renderMarkers()
})

watch(statusFilter, renderMarkers, { deep: true })
</script>

<template>
  <Head title="Maps" />
  <div class="min-h-screen grid grid-rows-[auto_1fr]">
    <div class="p-3 bg-white dark:bg-gray-900 border-b flex items-center gap-4">
      <div class="font-bold">Legend:</div>
      <label class="flex items-center gap-2"><input v-model="statusFilter.online" type="checkbox"/> <span class="inline-block w-3 h-3 rounded-full bg-green-500"></span> Online</label>
      <label class="flex items-center gap-2"><input v-model="statusFilter.offline" type="checkbox"/> <span class="inline-block w-3 h-3 rounded-full bg-red-500"></span> Offline</label>
      <label class="flex items-center gap-2"><input v-model="statusFilter.maintenance" type="checkbox"/> <span class="inline-block w-3 h-3 rounded-full bg-yellow-400"></span> Maintenance</label>
    </div>
    <div ref="mapEl" class="w-full h-full"></div>
  </div>
</template>

<style>
.leaflet-container { height: 100%; }
</style>