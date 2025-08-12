<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/kilang-background.jpg');"
    >
        <!-- Background overlay for better readability -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Main content container -->
        <div class="relative z-10 flex flex-col items-center w-full max-w-md px-6">
            <!-- Logo and Title Section -->
            <div class="text-center mb-8">
                <div class="flex justify-center mb-4">
                    <img 
                        src="/images/pertamina-logo.png" 
                        alt="Pertamina Logo" 
                        class="h-16 w-auto"
                    />
                </div>
                <div class="text-white">
                    <h1 class="text-2xl font-bold tracking-wide mb-1">
                        KILANG PERTAMINA INTERNASIONAL
                    </h1>
                    <p class="text-sm font-medium opacity-90">
                        REFINERY UNIT VI BALONGAN
                    </p>
                </div>
            </div>

            <!-- Authentication Form Container -->
            <div
                class="w-full overflow-hidden bg-white/95 backdrop-blur-sm px-8 py-8 shadow-2xl rounded-xl border border-white/20"
            >
                <slot />
            </div>
            
            <!-- Footer -->
            <div class="mt-6 text-center text-white/80 text-sm">
                <p>&copy; 2024 Kilang Pertamina Internasional. All rights reserved.</p>
            </div>
        </div>
    </div>
</template>
