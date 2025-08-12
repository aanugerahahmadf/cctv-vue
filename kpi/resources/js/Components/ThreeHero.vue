<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue';
import * as THREE from 'three';

const canvasRef = ref(null);
let renderer, scene, camera, animationFrameId, mesh;

onMounted(() => {
  const canvas = canvasRef.value;
  const width = canvas.clientWidth;
  const height = canvas.clientHeight;

  scene = new THREE.Scene();
  scene.background = null;

  camera = new THREE.PerspectiveCamera(45, width / height, 0.1, 100);
  camera.position.set(0, 0, 6);

  renderer = new THREE.WebGLRenderer({ canvas, antialias: true, alpha: true });
  renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
  renderer.setSize(width, height, false);

  const geometry = new THREE.IcosahedronGeometry(1.2, 1);
  const material = new THREE.MeshStandardMaterial({
    color: 0xff2d20,
    metalness: 0.35,
    roughness: 0.25,
  });
  mesh = new THREE.Mesh(geometry, material);
  scene.add(mesh);

  const ambient = new THREE.AmbientLight(0xffffff, 0.6);
  scene.add(ambient);

  const point1 = new THREE.PointLight(0xff6b6b, 10, 20);
  point1.position.set(3, 3, 3);
  scene.add(point1);

  const point2 = new THREE.PointLight(0x60a5fa, 8, 20);
  point2.position.set(-3, -2, 2);
  scene.add(point2);

  const onResize = () => {
    const w = canvas.clientWidth;
    const h = canvas.clientHeight;
    renderer.setSize(w, h, false);
    camera.aspect = w / h;
    camera.updateProjectionMatrix();
  };
  window.addEventListener('resize', onResize);

  const tick = () => {
    animationFrameId = requestAnimationFrame(tick);
    mesh.rotation.x += 0.005;
    mesh.rotation.y += 0.01;
    renderer.render(scene, camera);
  };
  tick();

  onBeforeUnmount(() => {
    cancelAnimationFrame(animationFrameId);
    window.removeEventListener('resize', onResize);
    geometry.dispose();
    material.dispose();
    renderer.dispose();
    scene.clear();
  });
});
</script>

<template>
  <div class="relative w-full">
    <div class="pointer-events-none absolute inset-0 -z-10 bg-gradient-to-b from-transparent via-[#ff2d2015] to-transparent blur-3xl" />
    <canvas ref="canvasRef" class="block w-full h-48 sm:h-56 md:h-64 lg:h-80 3xl:h-96 4k:h-[28rem] rounded-xl ring-1 ring-white/5 shadow-[0_20px_60px_rgba(0,0,0,0.25)]"></canvas>
  </div>
</template>

<style scoped>
/**** Ensure the canvas scales crisply ****/
:host, canvas { image-rendering: auto; }
</style>