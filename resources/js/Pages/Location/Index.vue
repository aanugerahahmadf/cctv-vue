<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

defineProps({
    buildings: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <UserLayout :user="$page.props.auth.user">
        <Head title="Lokasi - Kilang Pertamina Internasional" />

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Page Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    Lokasi Gedung
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Pilih gedung untuk melihat ruangan dan CCTV yang tersedia
                </p>
            </div>

            <!-- Buildings Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                    v-for="building in buildings"
                    :key="building.id"
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow"
                >
                    <!-- Building Header -->
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ building.name }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ building.description }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Camera Statistics -->
                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div class="text-center">
                                <div class="text-lg font-bold text-green-600">
                                    {{ building.online_cameras_count }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Online</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-red-600">
                                    {{ building.offline_cameras_count }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Offline</div>
                            </div>
                            <div class="text-center">
                                <div class="text-lg font-bold text-yellow-600">
                                    {{ building.maintenance_cameras_count }}
                                </div>
                                <div class="text-xs text-gray-600 dark:text-gray-400">Maintenance</div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <Link
                            :href="route('location.building', building.id)"
                            class="w-full bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-colors text-center block"
                        >
                            Lihat Ruangan
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="buildings.length === 0" class="text-center py-12">
                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">
                    Tidak ada gedung tersedia
                </h3>
                <p class="text-gray-600 dark:text-gray-400">
                    Belum ada data gedung yang ditambahkan ke sistem.
                </p>
            </div>
        </div>
    </UserLayout>
</template>
