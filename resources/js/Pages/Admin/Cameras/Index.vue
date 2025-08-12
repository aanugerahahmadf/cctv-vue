<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, reactive, ref, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import Toast from '@/Components/Toast.vue';

const props = defineProps({
  cameras: Object,
  filters: Object,
  buildings: Array,
});

const toast = ref();

const state = reactive({
  q: props.filters?.q || '',
  status: props.filters?.status || '',
  building_id: props.filters?.building_id || '',
  sort: props.filters?.sort || 'name',
  direction: props.filters?.direction || 'asc',
  perPage: props.filters?.perPage || 50,
});

const visible = reactive({ name: true, ip: true, building: true, room: true, status: true });
const selected = reactive(new Set());
const allSelected = computed({
  get() {
    return props.cameras?.data?.length && props.cameras.data.every(c => selected.has(c.id));
  },
  set(val) {
    selected.clear();
    if (val) props.cameras?.data?.forEach(c => selected.add(c.id));
  }
});

const applyFilters = () => {
  router.visit(route('admin.cameras.index'), {
    method: 'get',
    data: { ...state },
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

watch(() => [state.status, state.building_id, state.perPage], applyFilters);

const sortBy = (key) => {
  if (state.sort === key) {
    state.direction = state.direction === 'asc' ? 'desc' : 'asc';
  } else {
    state.sort = key;
    state.direction = 'asc';
  }
  applyFilters();
};

const start = async (id) => {
  await router.post(route('admin.cameras.start', id), {}, { preserveScroll: true });
  toast.value?.open('Stream starting', 'success');
};
const stop = async (id) => {
  await router.post(route('admin.cameras.stop', id), {}, { preserveScroll: true });
  toast.value?.open('Stream stopped', 'info');
};
const snap = async (id) => {
  await router.post(route('admin.cameras.snapshot', id), {}, { preserveScroll: true });
  toast.value?.open('Snapshot saved', 'success');
};
const record = async (id) => {
  await router.post(route('admin.cameras.record', id), { seconds: 10 }, { preserveScroll: true });
  toast.value?.open('Recording saved', 'success');
};

const bulk = async (action) => {
  if (!selected.size) return;
  const ids = Array.from(selected);
  for (const id of ids) {
    if (action === 'start') await start(id);
    if (action === 'stop') await stop(id);
    if (action === 'snapshot') await snap(id);
    if (action === 'record') await record(id);
  }
  selected.clear();
};
</script>

<template>
  <AdminLayout>
    <Head title="Admin - Cameras" />
    <Toast ref="toast" />

    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Cameras</h1>
      <div class="flex gap-2">
        <div class="hidden md:flex items-center gap-2 mr-2">
          <label class="text-xs text-gray-600 dark:text-gray-400">Columns:</label>
          <label class="flex items-center gap-1 text-xs"><input type="checkbox" v-model="visible.name"> Name</label>
          <label class="flex items-center gap-1 text-xs"><input type="checkbox" v-model="visible.ip"> IP</label>
          <label class="flex items-center gap-1 text-xs"><input type="checkbox" v-model="visible.building"> Building</label>
          <label class="flex items-center gap-1 text-xs"><input type="checkbox" v-model="visible.room"> Room</label>
          <label class="flex items-center gap-1 text-xs"><input type="checkbox" v-model="visible.status"> Status</label>
        </div>
        <Link :href="route('admin.cameras.create')" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-indigo-600 via-violet-600 to-fuchsia-600 shadow-[0_8px_24px_rgba(109,40,217,0.35)] hover:shadow-[0_10px_28px_rgba(109,40,217,0.5)] hover:scale-[1.015] focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">Add Camera</Link>
        <a href="/export/cameras.csv" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-gray-700 to-gray-900 shadow-[0_8px_24px_rgba(31,41,55,0.35)] hover:shadow-[0_10px_28px_rgba(31,41,55,0.5)] hover:scale-[1.015] focus:outline-none focus:ring-2 focus:ring-gray-600 focus:ring-offset-2 focus:ring-offset-white dark:focus:ring-offset-gray-900">Export CSV</a>
      </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="mb-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
      <div>
        <label class="text-xs text-gray-500">Search</label>
        <input v-model="state.q" @keyup.enter="applyFilters" placeholder="Cari nama/IP/gedung/ruang" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm" />
      </div>
      <div>
        <label class="text-xs text-gray-500">Status</label>
        <select v-model="state.status" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
          <option value="">Semua</option>
          <option value="online">Online</option>
          <option value="offline">Offline</option>
          <option value="maintenance">Maintenance</option>
        </select>
      </div>
      <div>
        <label class="text-xs text-gray-500">Gedung</label>
        <select v-model="state.building_id" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
          <option value="">Semua</option>
          <option v-for="b in buildings" :key="b.id" :value="b.id">{{ b.name }}</option>
        </select>
      </div>
      <div>
        <label class="text-xs text-gray-500">Per Page</label>
        <select v-model.number="state.perPage" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
          <option :value="25">25</option>
          <option :value="50">50</option>
          <option :value="100">100</option>
          <option :value="200">200</option>
        </select>
      </div>
    </div>

    <!-- Bulk actions -->
    <div class="mb-3 flex items-center gap-2">
      <label class="flex items-center gap-2 text-sm"><input type="checkbox" v-model="allSelected"> Select all</label>
      <button @click="bulk('start')" class="inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-emerald-600 hover:bg-emerald-700 transition">Start</button>
      <button @click="bulk('stop')" class="inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 transition">Stop</button>
      <button @click="bulk('snapshot')" class="inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-gray-800 hover:bg-gray-900 transition">Snapshot</button>
      <button @click="bulk('record')" class="inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-semibold text-white bg-fuchsia-600 hover:bg-fuchsia-700 transition">Record</button>
    </div>

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left"><input type="checkbox" v-model="allSelected" /></th>
            <th v-if="visible.name" class="px-3 py-2 text-left cursor-pointer" @click="sortBy('name')">Name</th>
            <th v-if="visible.ip" class="px-3 py-2 text-left cursor-pointer" @click="sortBy('ip_address')">IP</th>
            <th v-if="visible.building" class="px-3 py-2 text-left">Building</th>
            <th v-if="visible.room" class="px-3 py-2 text-left">Room</th>
            <th v-if="visible.status" class="px-3 py-2 text-left cursor-pointer" @click="sortBy('status')">Status</th>
            <th class="px-3 py-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="c in cameras.data" :key="c.id" class="border-t border-gray-100 dark:border-gray-700">
            <td class="px-3 py-2"><input type="checkbox" :checked="selected.has(c.id)" @change="(e) => e.target.checked ? selected.add(c.id) : selected.delete(c.id)" /></td>
            <td v-if="visible.name" class="px-3 py-2">
              <Link :href="route('cameras.show', c.id)" class="text-blue-600 dark:text-blue-400 hover:underline">{{ c.name }}</Link>
            </td>
            <td v-if="visible.ip" class="px-3 py-2">{{ c.ip_address }}</td>
            <td v-if="visible.building" class="px-3 py-2">{{ c.building || '-' }}</td>
            <td v-if="visible.room" class="px-3 py-2">{{ c.room || '-' }}</td>
            <td v-if="visible.status" class="px-3 py-2">
              <span :class="{
                'text-green-600': c.status === 'online',
                'text-red-600': c.status === 'offline',
                'text-yellow-600': c.status === 'maintenance',
              }">{{ c.status }}</span>
            </td>
            <td class="px-3 py-2 flex gap-2">
              <button @click="start(c.id)" class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-xs font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-green-600 to-emerald-700 shadow-[0_6px_18px_rgba(16,185,129,0.35)] hover:shadow-[0_8px_22px_rgba(16,185,129,0.5)] hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-emerald-500">Start</button>
              <button @click="stop(c.id)" class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-xs font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-red-600 to-rose-700 shadow-[0_6px_18px_rgba(239,68,68,0.35)] hover:shadow-[0_8px_22px_rgba(239,68,68,0.5)] hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-rose-500">Stop</button>
              <button @click="snap(c.id)" class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-xs font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-gray-700 to-gray-900 shadow-[0_6px_18px_rgba(31,41,55,0.35)] hover:shadow-[0_8px_22px_rgba(31,41,55,0.5)] hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-gray-600">Snapshot</button>
              <button @click="record(c.id)" class="inline-flex items-center justify-center rounded-lg px-3 py-1.5 text-xs font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-purple-600 to-fuchsia-700 shadow-[0_6px_18px_rgba(147,51,234,0.35)] hover:shadow-[0_8px_22px_rgba(147,51,234,0.5)] hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-fuchsia-500">Record</button>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs text-gray-500 dark:text-gray-400">
        <div>
          Showing {{ cameras.from }} - {{ cameras.to }} of {{ cameras.total }}
        </div>
        <div class="flex gap-1 overflow-x-auto">
          <Link v-for="link in cameras.links" :key="link.url || link.label" :href="link.url || '#'" v-html="link.label" :class="['px-2 py-1 rounded', link.active ? 'bg-blue-600 text-white' : '']" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
