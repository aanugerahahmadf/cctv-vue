<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center rounded-lg border border-gray-300/70 dark:border-gray-700 bg-white/90 dark:bg-gray-800/90 px-4 py-2 text-sm font-medium text-gray-800 dark:text-gray-100 shadow-sm
               hover:bg-white dark:hover:bg-gray-800 hover:shadow-md transition-all duration-200 ease-out
               focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900
               disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <slot />
    </button>
</template>
