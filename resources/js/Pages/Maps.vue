<template>
  <UserLayout :user="$page.props.auth.user">
    <Head title="Peta CCTV - Kilang Pertamina Internasional" />

    <div class="h-screen flex flex-col">
      <!-- Map Controls -->
      <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">Peta CCTV</h1>
            
            <!-- Map Type Toggle -->
            <div class="flex items-center space-x-2">
              <button
                @click="toggleMapType"
                class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
              >
                {{ mapType === 'osm' ? 'Satellite' : 'OpenStreetMap' }}
              </button>
            </div>

            <!-- Status Filters -->
            <div class="flex items-center space-x-2">
              <span class="text-sm text-gray-600 dark:text-gray-400">Filter:</span>
              
              <!-- Online Filter -->
              <button
                @click="toggleFilter('online')"
                :class="[
                  'w-4 h-4 rounded-full border-2 transition-colors',
                  activeFilters.includes('online') 
                    ? 'bg-green-500 border-green-500' 
                    : 'bg-white border-gray-300 dark:bg-gray-700 dark:border-gray-600'
                ]"
                title="CCTV Online"
              ></button>
              
              <!-- Offline Filter -->
              <button
                @click="toggleFilter('offline')"
                :class="[
                  'w-4 h-4 rounded-full border-2 transition-colors',
                  activeFilters.includes('offline') 
                    ? 'bg-red-500 border-red-500' 
                    : 'bg-white border-gray-300 dark:bg-gray-700 dark:border-gray-600'
                ]"
                title="CCTV Offline"
              ></button>
              
              <!-- Maintenance Filter -->
              <button
                @click="toggleFilter('maintenance')"
                :class="[
                  'w-4 h-4 rounded-full border-2 transition-colors',
                  activeFilters.includes('maintenance') 
                    ? 'bg-yellow-500 border-yellow-500' 
                    : 'bg-white border-gray-300 dark:bg-gray-700 dark:border-gray-600'
                ]"
                title="CCTV Maintenance"
              ></button>
            </div>
          </div>

          <!-- Statistics -->
          <div class="flex items-center space-x-4 text-sm">
            <span class="text-green-600">Online: {{ stats.onlineCameras }}</span>
            <span class="text-red-600">Offline: {{ stats.offlineCameras }}</span>
            <span class="text-yellow-600">Maintenance: {{ stats.maintenanceCameras }}</span>
          </div>
        </div>
      </div>

      <!-- Map Container -->
      <div id="map" class="flex-1"></div>

      <!-- Camera Details Panel -->
      <div v-if="showCameraDetails && selectedCamera" class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-4">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
              {{ selectedCamera.name }}
            </h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">
              {{ selectedCamera.room?.name }} - {{ selectedCamera.building?.name }}
            </p>
          </div>
          <div class="flex items-center space-x-2">
            <span :class="[
              'px-2 py-1 rounded text-xs font-medium',
              selectedCamera.status === 'online' ? 'bg-green-100 text-green-800' : '',
              selectedCamera.status === 'offline' ? 'bg-red-100 text-red-800' : '',
              selectedCamera.status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : ''
            ]">
              {{ selectedCamera.status_text }}
            </span>
            <button
              @click="viewCamera(selectedCamera.id)"
              class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
            >
              Lihat Live CCTV
            </button>
            <button
              @click="showCameraDetails = false"
              class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
    buildings: {
        type: Array,
        required: true,
    },
    stats: {
        type: Object,
        required: true,
    },
});

// Map state
const map = ref(null);
const mapType = ref('osm'); // 'osm' or 'satellite'
const selectedBuilding = ref(null);
const selectedCamera = ref(null);
const showCameraDetails = ref(false);

// Filter state
const activeFilters = ref(['online', 'offline', 'maintenance']);

// Balongan coordinates (approximate)
const BALONGAN_COORDS = [-6.2088, 108.0000];

// Initialize map
onMounted(() => {
    // Load Leaflet CSS
    const link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
    document.head.appendChild(link);

    // Load Leaflet JS
    const script = document.createElement('script');
    script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
    script.onload = initializeMap;
    document.head.appendChild(script);
});

const initializeMap = () => {
    // Create map
    map.value = L.map('map').setView(BALONGAN_COORDS, 15);

    // Add tile layers
    const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    });

    const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '© Esri'
    });

    // Add default layer
    osmLayer.addTo(map.value);

    // Store layers for switching
    map.value.osmLayer = osmLayer;
    map.value.satelliteLayer = satelliteLayer;

    // Add building markers
    addBuildingMarkers();
};

const addBuildingMarkers = () => {
    if (!map.value) return;

    props.buildings.forEach(building => {
        if (!building.latitude || !building.longitude) return;

        // Create building marker
        const buildingMarker = L.marker([building.latitude, building.longitude], {
            icon: L.divIcon({
                className: 'building-marker',
                html: `<div class="w-6 h-6 bg-blue-600 rounded-full border-2 border-white shadow-lg flex items-center justify-center">
                         <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                           <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                         </svg>
                       </div>`,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            })
        });

        // Add popup for building
        buildingMarker.bindPopup(`
            <div class="p-2">
                <h3 class="font-bold text-lg">${building.name}</h3>
                <p class="text-sm text-gray-600">${building.description}</p>
                <div class="mt-2 text-xs">
                    <span class="inline-block bg-green-100 text-green-800 px-2 py-1 rounded mr-1">
                        Online: ${building.online_cameras_count}
                    </span>
                    <span class="inline-block bg-red-100 text-red-800 px-2 py-1 rounded mr-1">
                        Offline: ${building.offline_cameras_count}
                    </span>
                    <span class="inline-block bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                        Maintenance: ${building.maintenance_cameras_count}
                    </span>
                </div>
                <button onclick="window.selectBuilding(${building.id})" class="mt-2 w-full bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    Lihat CCTV
                </button>
            </div>
        `);

        // Add click handler
        buildingMarker.on('click', () => {
            selectedBuilding.value = building;
            addCameraMarkers(building);
            map.value.setView([building.latitude, building.longitude], 18);
        });

        buildingMarker.addTo(map.value);
    });
};

const addCameraMarkers = (building) => {
    if (!map.value || !building.cameras) return;

    // Clear existing camera markers
    if (map.value.cameraMarkers) {
        map.value.cameraMarkers.forEach(marker => map.value.removeLayer(marker));
    }
    map.value.cameraMarkers = [];

    building.cameras.forEach(camera => {
        if (!camera.latitude || !camera.longitude) return;

        // Check if camera should be shown based on filters
        if (!activeFilters.value.includes(camera.status)) return;

        // Get color based on status
        const colors = {
            online: 'green',
            offline: 'red',
            maintenance: 'yellow'
        };
        const color = colors[camera.status] || 'gray';

        // Create camera marker
        const cameraMarker = L.marker([camera.latitude, camera.longitude], {
            icon: L.divIcon({
                className: 'camera-marker',
                html: `<div class="w-4 h-4 bg-${color}-500 rounded-full border-2 border-white shadow-lg cursor-pointer"></div>`,
                iconSize: [16, 16],
                iconAnchor: [8, 8]
            })
        });

        // Add popup for camera
        cameraMarker.bindPopup(`
            <div class="p-2">
                <h4 class="font-bold">${camera.name}</h4>
                <p class="text-sm text-gray-600">${camera.room?.name || 'Unknown Room'}</p>
                <div class="mt-2">
                    <span class="inline-block bg-${color}-100 text-${color}-800 px-2 py-1 rounded text-xs">
                        ${camera.status_text}
                    </span>
                </div>
                <button onclick="window.viewCamera(${camera.id})" class="mt-2 w-full bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    Lihat Live CCTV
                </button>
            </div>
        `);

        // Add click handler
        cameraMarker.on('click', () => {
            selectedCamera.value = camera;
            showCameraDetails.value = true;
        });

        cameraMarker.addTo(map.value);
        map.value.cameraMarkers.push(cameraMarker);
    });
};

const toggleMapType = () => {
    if (!map.value) return;

    if (mapType.value === 'osm') {
        map.value.removeLayer(map.value.osmLayer);
        map.value.satelliteLayer.addTo(map.value);
        mapType.value = 'satellite';
    } else {
        map.value.removeLayer(map.value.satelliteLayer);
        map.value.osmLayer.addTo(map.value);
        mapType.value = 'osm';
    }
};

const toggleFilter = (status) => {
    const index = activeFilters.value.indexOf(status);
    if (index > -1) {
        activeFilters.value.splice(index, 1);
    } else {
        activeFilters.value.push(status);
    }

    // Refresh camera markers if a building is selected
    if (selectedBuilding.value) {
        addCameraMarkers(selectedBuilding.value);
    }
};

const viewCamera = (cameraId) => {
    // Navigate to camera view page
    window.location.href = `/cameras/${cameraId}`;
};

const selectBuilding = (buildingId) => {
    const building = props.buildings.find(b => b.id === buildingId);
    if (building) {
        selectedBuilding.value = building;
        addCameraMarkers(building);
        map.value.setView([building.latitude, building.longitude], 18);
    }
};

// Expose functions globally for popup buttons
onMounted(() => {
    window.viewCamera = viewCamera;
    window.selectBuilding = selectBuilding;
});

onUnmounted(() => {
    if (map.value) {
        map.value.remove();
    }
});
</script>

<style scoped>
.building-marker {
    background: transparent;
    border: none;
}

.camera-marker {
    background: transparent;
    border: none;
}

/* Custom map styles */
:deep(.leaflet-popup-content-wrapper) {
    border-radius: 8px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.leaflet-popup-content) {
    margin: 0;
    padding: 0;
}

:deep(.leaflet-popup-tip) {
    background: white;
}

.dark :deep(.leaflet-popup-content-wrapper) {
    background: #374151;
    color: white;
}

.dark :deep(.leaflet-popup-tip) {
    background: #374151;
}
</style>
