<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <!-- Logo and Title -->
          <div class="flex items-center space-x-4">
            <img 
              src="/images/pertamina-logo.png" 
              alt="Pertamina Logo" 
              class="h-10 w-auto"
            />
            <div class="hidden md:block">
              <h1 class="text-lg font-bold text-gray-900 dark:text-white">
                KILANG PERTAMINA INTERNASIONAL
              </h1>
              <p class="text-sm text-gray-600 dark:text-gray-400">
                REFINERY UNIT VI BALONGAN
              </p>
            </div>
          </div>

          <!-- Navigation -->
          <nav class="hidden md:flex items-center space-x-8 text-sm">
            <Link
              :href="route('dashboard')"
              class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2"
              :class="{ 'text-blue-600 dark:text-blue-400': $page.url === route('dashboard') }"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7"/><path d="M9 22V12h6v10"/></svg>
              <span>DASHBOARD</span>
            </Link>
            <Link
              :href="route('maps')"
              class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2"
              :class="{ 'text-blue-600 dark:text-blue-400': $page.url === route('maps') }"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6"/></svg>
              <span>MAPS</span>
            </Link>
            <Link
              :href="route('location.index')"
              class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-2"
              :class="{ 'text-blue-600 dark:text-blue-400': $page.url.startsWith('/location') }"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="10" r="3"/><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7z"/></svg>
              <span>LOKASI</span>
            </Link>
            <Link
              :href="route('contacts.public')"
              class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors mr-12 flex items-center gap-2"
              :class="{ 'text-blue-600 dark:text-blue-400': $page.url === route('contacts.public') }"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="M22 6l-10 7L2 6"/></svg>
              <span>CONTACT</span>
            </Link>
            <Link
              :href="route('messages.index')"
              class="font-bold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors mr-12 flex items-center gap-2"
              :class="{ 'text-blue-600 dark:text-blue-400': $page.url === route('messages.index') }"
            >
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
              <span>CHAT</span>
            </Link>
          </nav>

          <!-- Search Box -->
          <div class="flex-1 max-w-md mx-8 relative">
            <div class="relative">
              <input
                v-model="searchQuery"
                @input="searchBuildings"
                @focus="showSearchResults = searchResults.length > 0"
                type="text"
                placeholder="Cari gedung..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              />
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>

            <!-- Search Results Dropdown -->
            <div v-if="showSearchResults && searchResults.length > 0" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg">
              <div class="py-2">
                <div
                  v-for="building in searchResults"
                  :key="building.id"
                  @click="selectBuilding(building)"
                  class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer text-gray-900 dark:text-white"
                >
                  <div class="font-medium">{{ building.name }}</div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">{{ building.description }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Right side controls -->
          <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <div class="relative">
              <select
                v-model="theme"
                class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 pr-8 text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="light">Light</option>
                <option value="dark">Night</option>
                <option value="system">System</option>
              </select>
              <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </div>
            </div>

            <!-- Profile Dropdown -->
            <div class="relative">
              <button
                @click="toggleProfileDropdown"
                class="flex items-center space-x-2 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none"
              >
                <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                  <span class="text-white text-sm font-medium">
                    {{ user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                  </span>
                </div>
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
              </button>

              <!-- Profile Dropdown Menu -->
              <div v-if="showProfileDropdown" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-700 rounded-lg shadow-lg border border-gray-200 dark:border-gray-600 z-50">
                <div class="py-1">
                  <Link
                    :href="route('profile.edit')"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"
                  >
                    Profile Settings
                  </Link>
                  <Link
                    :href="route('settings')"
                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"
                  >
                    Settings
                  </Link>
                  <hr class="my-1 border-gray-200 dark:border-gray-600">
                  <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600"
                  >
                    Logout
                  </Link>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-gray-600 dark:text-gray-400">
          &copy; 2024 Kilang Pertamina Internasional. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    user: Object,
});

const page = usePage();

// Theme management
const theme = ref(localStorage.getItem('theme') || 'system');
const isDark = ref(false);

const updateTheme = () => {
    if (theme.value === 'dark' || (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
        isDark.value = true;
    } else {
        document.documentElement.classList.remove('dark');
        isDark.value = false;
    }
    localStorage.setItem('theme', theme.value);
};

watch(theme, updateTheme);
onMounted(updateTheme);

// Search functionality
const searchQuery = ref('');
const searchResults = ref([]);
const showSearchResults = ref(false);

const searchBuildings = async () => {
    if (searchQuery.value.length < 2) {
        searchResults.value = [];
        showSearchResults.value = false;
        return;
    }

    try {
        const response = await fetch(`/api/buildings/search?q=${encodeURIComponent(searchQuery.value)}`);
        const data = await response.json();
        searchResults.value = data.buildings;
        showSearchResults.value = true;
    } catch (error) {
        console.error('Search error:', error);
    }
};

const selectBuilding = (building) => {
    searchQuery.value = building.name;
    showSearchResults.value = false;
    router.visit(route('location.building', building.id));
};

// Profile dropdown
const showProfileDropdown = ref(false);

const toggleProfileDropdown = () => {
    showProfileDropdown.value = !showProfileDropdown.value;
};

// Close dropdowns when clicking outside
const closeDropdowns = () => {
    showSearchResults.value = false;
    showProfileDropdown.value = false;
};

onMounted(() => {
    document.addEventListener('click', closeDropdowns);
});
</script>
