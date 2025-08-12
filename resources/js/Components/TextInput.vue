<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="rounded-lg border border-gray-300/80 dark:border-gray-700 bg-white/95 dark:bg-gray-800/90 text-gray-900 dark:text-gray-100 shadow-sm
               focus:border-violet-500 focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900
               placeholder-gray-500 dark:placeholder-gray-400 px-3 py-2"
        v-model="model"
        ref="input"
    />
</template>
