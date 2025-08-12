<template>
  <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex">
    <!-- Sidebar -->
    <aside class="w-72 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 hidden md:flex md:flex-col">
      <div class="h-16 flex items-center px-4 border-b border-gray-200 dark:border-gray-700">
        <img src="/images/pertamina-logo.png" class="h-8 mr-3" alt="Pertamina" />
        <div>
          <div class="text-xs text-gray-500 dark:text-gray-400">KILANG PERTAMINA INTERNASIONAL</div>
          <div class="text-[10px] text-gray-400">REFINERY UNIT VI BALONGAN</div>
        </div>
      </div>
      <nav class="p-4 space-y-1">
        <Link :href="route('admin.dashboard')" class="nav-item flex items-center gap-2"><Icon icon="mdi:home" class="w-4 h-4"/> <span>Dashboard</span></Link>
        <Link :href="route('admin.cameras.index')" class="nav-item flex items-center gap-2"><Icon icon="mdi:table" class="w-4 h-4"/> <span>Table</span></Link>
        <Link :href="route('admin.users.index')" class="nav-item flex items-center gap-2"><Icon icon="mdi:account-group" class="w-4 h-4"/> <span>User</span></Link>
        <Link :href="route('maps')" class="nav-item flex items-center gap-2"><Icon icon="mdi:map" class="w-4 h-4"/> <span>Maps</span></Link>
        <Link :href="route('admin.buildings.index')" class="nav-item flex items-center gap-2"><Icon icon="mdi:office-building" class="w-4 h-4"/> <span>Location</span></Link>
        <Link :href="route('admin.contacts.index')" class="nav-item flex items-center gap-2"><Icon icon="mdi:email-outline" class="w-4 h-4"/> <span>Contact</span></Link>
        <Link :href="route('profile.edit')" class="nav-item flex items-center gap-2"><Icon icon="mdi:account-circle" class="w-4 h-4"/> <span>Profile</span></Link>
        <Link :href="route('settings')" class="nav-item flex items-center gap-2"><Icon icon="mdi:cog" class="w-4 h-4"/> <span>Settings</span></Link>
        <Link :href="route('admin.messages.index')" class="nav-item flex items-center gap-2"><Icon icon="mdi:chat-processing" class="w-4 h-4"/> <span>Chat</span></Link>
      </nav>
      <div class="mt-auto p-4 text-xs text-gray-500 dark:text-gray-400">© 2024 Kilang Pertamina Internasional</div>
    </aside>

    <!-- Main -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Header -->
      <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4">
        <div class="flex items-center gap-3">
          <button class="md:hidden p-2 text-gray-600 dark:text-gray-300" @click="sidebarOpen = !sidebarOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <div class="relative">
            <input v-model="q" @keyup.enter="search" placeholder="Search..." class="w-72 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm" />
            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400">⏎</span>
          </div>
        </div>
        <div class="flex items-center gap-3">
          <select v-model="theme" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
            <option value="light">Light</option>
            <option value="dark">Night</option>
            <option value="system">System</option>
          </select>
          <div class="w-8 h-8 bg-blue-600 rounded-full text-white flex items-center justify-center text-sm">
            {{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() || 'A' }}
          </div>
        </div>
      </header>

      <main class="flex-1 p-4">
        <slot />
      </main>

      <footer class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
        Copy Right Kilang Pertamina Internasional
      </footer>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';

const theme = ref(localStorage.getItem('theme') || 'system');
const applyTheme = () => {
  const dark = theme.value === 'dark' || (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
  document.documentElement.classList.toggle('dark', dark);
  localStorage.setItem('theme', theme.value);
};
watch(theme, applyTheme);
onMounted(applyTheme);

const q = ref('');
const search = () => {
  if (!q.value) return;
  router.visit(route('admin.cameras.index'), { data: { q: q.value } });
};
</script>

<style scoped>
.nav-item {
  display: block;
  padding: 0.5rem 0.75rem;
  border-radius: 0.5rem;
  color: rgb(17 24 39);
}
.dark .nav-item { color: #e5e7eb; }
.nav-item:hover { background: #f3f4f6; }
.dark .nav-item:hover { background: #1f2937; }
</style>
