<script setup>
import { ref, onMounted, watch } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const buildings = page.props.buildingsSearch ?? [];
const search = ref('');
const searchResults = ref([]);

watch(search, (q) => {
  const term = (q || '').toLowerCase();
  searchResults.value = term ? buildings.filter(b => b.name.toLowerCase().includes(term)).slice(0, 8) : [];
});

const theme = ref(localStorage.getItem('theme') || 'system');

function applyTheme() {
  const root = document.documentElement;
  const sysDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const isDark = theme.value === 'dark' || (theme.value === 'system' && sysDark);
  root.classList.toggle('dark', isDark);
  localStorage.setItem('theme', theme.value);
}

onMounted(() => {
  applyTheme();
});
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-950">
            <nav class="border-b border-gray-200 bg-white dark:bg-gray-900">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <!-- Logo + Title -->
                            <Link :href="route('dashboard')" class="flex items-center gap-2">
                                <ApplicationLogo class="block h-9 w-auto fill-current text-red-600" />
                                <div class="leading-4">
                                  <div class="font-extrabold uppercase">KILANG PERTAMINA INTERNASIONAL</div>
                                  <div class="text-xs opacity-70">Refinery Unit VI Balongan</div>
                                </div>
                            </Link>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-4 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</NavLink>
                                <Link href="/maps" class="inline-flex items-center px-3 py-2 text-sm font-medium" :class="{ 'text-blue-600': route().current('maps') }">Maps</Link>
                                <Link href="/locations" class="inline-flex items-center px-3 py-2 text-sm font-medium">Lokasi</Link>
                                <Link href="/contact" class="inline-flex items-center px-3 py-2 text-sm font-medium">Contact</Link>
                                <Link href="/chat" class="inline-flex items-center px-3 py-2 text-sm font-medium">Chat</Link>
                            </div>
                        </div>

                        <!-- Search + Theme + Profile -->
                        <div class="hidden sm:flex items-center gap-3">
                            <div class="relative">
                                <input v-model="search" placeholder="Cari Gedung..." class="w-60 rounded-md border bg-white dark:bg-gray-800 p-2 text-sm" />
                                <div v-if="searchResults.length" class="absolute z-20 mt-1 w-60 rounded-md border bg-white dark:bg-gray-800 shadow">
                                    <Link v-for="b in searchResults" :key="b.id" :href="`/locations/${b.id}/rooms`" class="block px-3 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700">
                                      {{ b.name }}
                                    </Link>
                                </div>
                            </div>

                            <div class="flex items-center gap-1">
                                <select v-model="theme" @change="applyTheme" class="rounded-md border bg-white dark:bg-gray-800 p-2 text-sm">
                                    <option value="light">Light</option>
                                    <option value="dark">Night</option>
                                    <option value="system">System</option>
                                </select>
                            </div>

                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center rounded-md border border-transparent bg-white dark:bg-gray-800 px-3 py-2 text-sm font-medium leading-4 text-gray-500 dark:text-gray-200 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none">
                                                {{ $page.props.auth.user.name }}
                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">Log Out</DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Dashboard</ResponsiveNavLink>
                        <ResponsiveNavLink href="/maps">Maps</ResponsiveNavLink>
                        <ResponsiveNavLink href="/locations">Lokasi</ResponsiveNavLink>
                        <ResponsiveNavLink href="/contact">Contact</ResponsiveNavLink>
                        <ResponsiveNavLink href="/chat">Chat</ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-700">
                        <div class="px-4">
                            <div class="text-base font-medium text-gray-800 dark:text-gray-100">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">Log Out</ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-900 shadow" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <footer class="border-t bg-white dark:bg-gray-900 text-center py-3 text-sm text-gray-600 dark:text-gray-300">Copy Right Kilang Pertamina Internasional</footer>
        </div>
    </div>
</template>
