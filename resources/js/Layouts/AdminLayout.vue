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
      <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between px-4 relative">
        <div class="flex items-center gap-3">
          <button class="md:hidden p-2 text-gray-600 dark:text-gray-300" @click="sidebarOpen = !sidebarOpen">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>
          <div class="relative w-72">
            <input v-model="q" @input="onSearchInput" @focus="openSuggestions" @keydown.enter="goFirstResult" placeholder="Cari kamera, user, gedung..." class="w-72 pl-10 pr-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-violet-500 focus:border-transparent" />
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <!-- Suggestions -->
            <div v-if="showSuggestions && suggestions.length" class="absolute z-50 mt-1 w-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-xl">
              <div class="py-2 max-h-72 overflow-auto">
                <div v-for="s in suggestions" :key="s.href + s.label" @mousedown.prevent="goTo(s.href)" class="px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer text-sm text-gray-800 dark:text-gray-100 flex items-center gap-2">
                  <Icon :icon="s.icon" class="w-4 h-4" />
                  <span v-html="s.label"></span>
                </div>
              </div>
            </div>
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

      <!-- Flash Toast -->
      <Toast ref="flashToast" />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import { Icon } from '@iconify/vue';
import Toast from '@/Components/Toast.vue';

const theme = ref(localStorage.getItem('theme') || 'system');
const applyTheme = () => {
  const dark = theme.value === 'dark' || (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches);
  document.documentElement.classList.toggle('dark', dark);
  localStorage.setItem('theme', theme.value);
};
watch(theme, applyTheme);
onMounted(applyTheme);

const flashToast = ref();
const page = usePage();
onMounted(() => {
  const flash = page.props.flash || {};
  if (flash?.success) flashToast.value?.open(flash.success, 'success');
  if (flash?.status) flashToast.value?.open(flash.status, 'info');
  if (flash?.error) flashToast.value?.open(flash.error, 'error');
});

const q = ref('');
const suggestions = ref([]);
const showSuggestions = ref(false);
const openSuggestions = () => { showSuggestions.value = suggestions.value.length > 0; };

const onSearchInput = async () => {
  const query = q.value.trim();
  if (query.length < 2) { suggestions.value = []; showSuggestions.value = false; return; }
  const results = [];
  try {
    const [buildingsRes] = await Promise.all([
      fetch(`/api/buildings/search?q=${encodeURIComponent(query)}`),
    ]);
    const buildingsData = await buildingsRes.json();
    buildingsData.buildings.forEach(b => results.push({
      label: `Gedung: <strong>${b.name}</strong>`,
      href: route('location.building', b.id),
      icon: 'mdi:office-building',
    }));
  } catch (e) { /* noop */ }
  suggestions.value = results;
  showSuggestions.value = results.length > 0;
};

const goTo = (href) => router.visit(href);
const goFirstResult = () => { if (suggestions.value[0]) goTo(suggestions.value[0].href); };
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
