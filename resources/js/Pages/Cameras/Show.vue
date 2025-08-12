<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

const props = defineProps({
  camera: {
    type: Object,
    required: true,
  },
});

const videoRef = ref(null);
let hlsInstance = null;

const canPlayNativeHls = () => {
  const video = document.createElement('video');
  return video.canPlayType('application/vnd.apple.mpegurl');
};

const setupPlayer = async () => {
  const video = videoRef.value;
  if (!video || !props.camera) return;

  const src = props.camera.hls_url || props.camera.hlsPlaylistPath || props.camera.hls_playlist_path;
  if (!src) return;

  // If native HLS supported
  if (canPlayNativeHls()) {
    video.src = src;
    await video.play().catch(() => {});
    return;
  }

  // Load Hls.js dynamically
  await new Promise((resolve) => {
    const existing = document.querySelector('script[data-hlsjs]');
    if (existing) return resolve();
    const s = document.createElement('script');
    s.src = 'https://cdn.jsdelivr.net/npm/hls.js@1/dist/hls.min.js';
    s.setAttribute('data-hlsjs', 'true');
    s.onload = resolve;
    document.head.appendChild(s);
  });

  // eslint-disable-next-line no-undef
  if (window.Hls && window.Hls.isSupported()) {
    // eslint-disable-next-line no-undef
    hlsInstance = new window.Hls({
      lowLatencyMode: true,
      backBufferLength: 30,
      maxBufferLength: 10,
      maxLiveSyncPlaybackRate: 1.2,
    });
    hlsInstance.loadSource(src);
    hlsInstance.attachMedia(video);
    hlsInstance.on(window.Hls.Events.MANIFEST_PARSED, () => {
      video.play().catch(() => {});
    });
  }
};

const downloading = ref(false);
const takeScreenshot = async () => {
  const video = videoRef.value;
  if (!video) return;
  const canvas = document.createElement('canvas');
  canvas.width = video.videoWidth || 1280;
  canvas.height = video.videoHeight || 720;
  const ctx = canvas.getContext('2d');
  ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
  downloading.value = true;
  canvas.toBlob((blob) => {
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
    a.href = url;
    a.download = `screenshot_camera_${props.camera.id}_${timestamp}.png`;
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
    downloading.value = false;
  }, 'image/png');
};

onMounted(setupPlayer);

onBeforeUnmount(() => {
  if (hlsInstance) {
    try { hlsInstance.destroy(); } catch (e) {}
    hlsInstance = null;
  }
});
</script>

<template>
  <UserLayout :user="$page.props.auth.user">
    <Head :title="`Live CCTV - ${camera.name}`" />

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-6 flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ camera.name }}</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400">
            {{ camera.building?.name }}<span v-if="camera.room"> · {{ camera.room?.name }}</span>
          </p>
        </div>
        <div>
          <span :class="[
              'px-3 py-1 rounded-full text-xs font-medium',
              camera.status === 'online' && 'bg-green-100 text-green-800',
              camera.status === 'offline' && 'bg-red-100 text-red-800',
              camera.status === 'maintenance' && 'bg-yellow-100 text-yellow-800'
            ]">
            {{ camera.status_text }}
          </span>
        </div>
      </div>

      <!-- Player -->
      <div class="bg-black rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700">
        <video ref="videoRef" class="w-full aspect-video" controls playsinline></video>
      </div>

      <!-- Actions -->
      <div class="mt-4 flex items-center gap-3">
        <button
          @click="takeScreenshot"
          class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm disabled:opacity-50"
          :disabled="downloading"
        >
          {{ downloading ? 'Menyimpan...' : 'Screenshot' }}
        </button>
        <a
          v-if="camera.hls_url"
          :href="camera.hls_url"
          target="_blank"
          rel="noopener"
          class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg text-sm"
        >
          Buka Stream URL
        </a>
      </div>

      <!-- Info -->
      <div class="mt-6 grid sm:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Detail Kamera</h3>
          <dl class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
            <div class="flex justify-between"><dt>IP Address</dt><dd class="font-mono">{{ camera.ip_address }}</dd></div>
            <div class="flex justify-between" v-if="camera.latitude && camera.longitude"><dt>Koordinat</dt><dd class="font-mono">{{ camera.latitude }}, {{ camera.longitude }}</dd></div>
            <div class="flex justify-between"><dt>Status</dt><dd>{{ camera.status_text }}</dd></div>
          </dl>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
          <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">Lokasi</h3>
          <p class="text-sm text-gray-700 dark:text-gray-300">{{ camera.building?.name }}<span v-if="camera.room">, {{ camera.room?.name }}</span></p>
          <div class="mt-3 flex gap-2">
            <Link :href="route('location.building', camera.building_id)" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm">Lihat Gedung</Link>
            <Link v-if="camera.room_id" :href="route('location.room', { building: camera.building_id, room: camera.room_id })" class="px-3 py-1 bg-gray-100 dark:bg-gray-700 rounded text-sm">Lihat Ruangan</Link>
          </div>
        </div>
      </div>
    </div>
  </UserLayout>
</template>
