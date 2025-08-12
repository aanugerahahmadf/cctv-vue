<script setup>
const props = defineProps({
  lines: { type: Number, default: 3 },
});
</script>

<template>
  <div class="animate-pulse">
    <div v-for="i in lines" :key="i" class="h-4 bg-gray-200 dark:bg-gray-700 rounded mb-2"></div>
  </div>
</template>