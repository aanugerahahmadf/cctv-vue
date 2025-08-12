<template>
    <button
        class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out
               bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 shadow-[0_8px_24px_rgba(109,40,217,0.35)]
               hover:shadow-[0_10px_28px_rgba(109,40,217,0.5)] hover:scale-[1.015]
               focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900
               disabled:opacity-50 disabled:cursor-not-allowed"
    >
        <slot />
    </button>
</template>
