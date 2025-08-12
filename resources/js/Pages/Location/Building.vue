<script setup>
import { Head, Link } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

defineProps({
    building: {
        type: Object,
        required: true,
    },
    rooms: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <UserLayout :user="$page.props.auth.user">
        <Head :title="`${building.name} - Lokasi`" />

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex mb-8" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <Link
                            :href="route('location.index')"
                            class="inline-flex items-center text-sm font-medium text-gray-700 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400"
                        >
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                            </svg>
                            Lokasi
                        </Link>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 dark:text-gray-400 md:ml-2">
                                {{ building.name }}
                            </span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Building Header -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ building.name }}
                            </h1>
                            <p class="text-gray-600 dark:text-gray-400">
                                {{ building.description }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Building Statistics -->
                    <div class="flex items-center space-x-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">
                                {{ building.online_cameras_count }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Online</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">
                                {{ building.offline_cameras_count }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Offline</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">
                                {{ building.maintenance_cameras_count }}
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">Maintenance</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms Section -->
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
                    Ruangan ({{ rooms.length }})
                </h2>
            </div>

            <!-- Rooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="room in rooms"
                    :key="room.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow"
                >
                    <!-- Room Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ room.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Lantai {{ room.floor }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Room Description -->
                        <p v-if="room.description" class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ room.description }}
                        </p>

                        <!-- Camera Statistics -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600">
                                    {{ room.online_cameras_count }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Online</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-red-600">
                                    {{ room.offline_cameras_count }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Offline</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-yellow-600">
                                    {{ room.maintenance_cameras_count }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Maintenance</div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <Link
                            :href="route('location.room', { building: building.id, room: room.id })"
                            class="w-full bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-600 transition-colors text-center block"
                        >
                            Lihat CCTV
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="rooms.length === 0" class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5a2 2 0 012-2h4a2 2 0 012 2v2H8V5z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Tidak ada ruangan tersedia
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Belum ada ruangan yang ditambahkan ke gedung ini.
                </p>
            </div>
        </div>
    </UserLayout>
</template>
