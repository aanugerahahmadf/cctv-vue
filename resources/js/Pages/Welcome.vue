<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Selamat Datang - Kilang Pertamina Internasional" />
    
    <div 
        class="min-h-screen bg-cover bg-center bg-no-repeat flex flex-col"
        style="background-image: url('/images/kilang-background.jpg');"
    >
        <!-- Background overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        
        <!-- Header with Login/Register -->
        <header class="relative z-10 flex justify-between items-center p-6">
            <div class="flex items-center space-x-4">
                <img 
                    src="/images/pertamina-logo.png" 
                    alt="Pertamina Logo" 
                    class="h-12 w-auto"
                />
                <div class="text-white">
                    <h1 class="text-xl font-bold tracking-wide">
                        KILANG PERTAMINA INTERNASIONAL
                    </h1>
                    <p class="text-sm font-medium opacity-90">
                        REFINERY UNIT VI BALONGAN
                    </p>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <Link
                    :href="route('login')"
                    class="inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 shadow-[0_8px_24px_rgba(109,40,217,0.35)] hover:shadow-[0_10px_28px_rgba(109,40,217,0.5)] hover:scale-[1.02]"
                >
                    Login
                </Link>
                <Link
                    :href="route('register')"
                    class="inline-flex items-center justify-center rounded-lg px-5 py-2.5 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-gray-700 to-gray-900 shadow-[0_8px_24px_rgba(31,41,55,0.35)] hover:shadow-[0_10px_28px_rgba(31,41,55,0.5)] hover:scale-[1.02]"
                >
                    Register
                </Link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="relative z-10 flex-1 flex items-center justify-center px-6">
            <div class="text-center text-white max-w-4xl">
                <h2 class="text-5xl font-bold mb-6 leading-tight">
                    Selamat Datang di
                    <br />
                    <span class="text-blue-400">Sistem Monitoring CCTV</span>
                </h2>
                <p class="text-xl mb-8 opacity-90 leading-relaxed">
                    Kilang Pertamina Internasional Refinery Unit VI Balongan
                    <br />
                    Monitoring real-time untuk keamanan dan efisiensi operasional
                </p>
                
                <div class="flex justify-center space-x-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-400 mb-2">700+</div>
                        <div class="text-sm opacity-80">CCTV Kamera</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-400 mb-2">18</div>
                        <div class="text-sm opacity-80">Gedung</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400 mb-2">24/7</div>
                        <div class="text-sm opacity-80">Monitoring</div>
                    </div>
                </div>
                
                <div class="mt-12">
                    <Link
                        :href="route('login')"
                        class="inline-flex items-center px-8 py-4 text-white font-bold rounded-lg text-lg transition-all duration-200 bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 shadow-[0_12px_32px_rgba(109,40,217,0.45)] hover:shadow-[0_16px_40px_rgba(109,40,217,0.6)] hover:scale-[1.03]"
                    >
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Mulai Monitoring
                    </Link>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 text-center py-6 text-white opacity-80">
            <p>&copy; 2024 Kilang Pertamina Internasional. All rights reserved.</p>
        </footer>
    </div>
</template>
