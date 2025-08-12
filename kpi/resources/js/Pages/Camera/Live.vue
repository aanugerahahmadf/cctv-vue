<script setup>
import { Head } from '@inertiajs/vue3'
import { onMounted, ref } from 'vue'
import videojs from 'video.js'
import 'video.js/dist/video-js.css'

const props = defineProps({
  camera: Object,
})

const playerEl = ref(null)
let player

onMounted(() => {
  player = videojs(playerEl.value, {
    autoplay: true,
    controls: true,
    preload: 'auto',
    sources: props.camera.hls ? [{ src: `/${props.camera.hls}`, type: 'application/x-mpegURL' }] : [],
  })
})
</script>

<template>
  <Head :title="`Live - ${camera.name}`" />
  <div class="min-h-screen p-6 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <h1 class="text-xl font-bold mb-4">Live CCTV — {{ camera.name }}</h1>
    <div v-if="camera.hls" class="max-w-5xl">
      <video ref="playerEl" class="video-js vjs-default-skin vjs-big-play-centered w-full h-[60vh]"></video>
    </div>
    <div v-else class="text-red-600">Stream belum aktif. Minta admin menjalankan: <code>php artisan cctv:stream {{ camera.id }}</code></div>
  </div>
</template>