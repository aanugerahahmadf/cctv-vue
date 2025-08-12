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
let buildingLayer

const statusFilter = ref({ online: true, offline: true, maintenance: true })

function colorFor(status) {
  return status === 'online' ? 'green' : status === 'offline' ? 'red' : 'orange'
}

function markerIcon(color) {
  return L.divIcon({ className: 'marker-dot', html: `<span style="display:inline-block;width:14px;height:14px;border-radius:9999px;background:${color};border:2px solid white;box-shadow:0 0 0 2px ${color};"></span>` })
}

function renderCameras() {
  if (cameraLayer) cameraLayer.clearLayers();
  cameraLayer = L.layerGroup();
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

function renderBuildings() {
  buildingLayer = L.layerGroup();
  props.buildings.forEach(b => {
    if (b.latitude && b.longitude) {
      const m = L.marker([b.latitude, b.longitude]);
      m.bindPopup(`<div><strong>${b.name}</strong><div class='mt-1'><a href='/locations/${b.id}/rooms' class='text-blue-600 underline'>Lihat Ruangan</a></div></div>`);
      m.on('click', () => {
        map.setView([b.latitude, b.longitude], 16);
      })
      buildingLayer.addLayer(m)
    }
  })
  buildingLayer.addTo(map)
}

onMounted(() => {
  map = L.map(mapEl.value, { center: [-6.3640, 108.3840], zoom: 14 });

  const osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 20, attribution: '&copy; OpenStreetMap' });
  const satellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
    subdomains: ['mt0','mt1','mt2','mt3'],
    maxZoom: 20,
    attribution: '&copy; Satellite',
  });
  osm.addTo(map)

  const baseLayers = { 'OpenStreetMap': osm, 'Satellite': satellite };
  const overlays = {};
  L.control.layers(baseLayers, overlays).addTo(map);

  renderBuildings();
  renderCameras();
})

watch(statusFilter, renderCameras, { deep: true })
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