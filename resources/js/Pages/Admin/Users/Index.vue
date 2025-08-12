<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import { reactive, watch } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  users: Object,
  filters: Object,
});

const state = reactive({
  q: props.filters?.q || '',
  verified: props.filters?.verified ?? '',
  sort: props.filters?.sort || 'created_at',
  direction: props.filters?.direction || 'desc',
  perPage: props.filters?.perPage || 50,
});

const apply = () => {
  router.visit(route('admin.users.index'), {
    method: 'get',
    data: { ...state },
    preserveState: true,
    preserveScroll: true,
    replace: true,
  });
};

watch(() => [state.verified, state.perPage], apply);

const sortBy = (key) => {
  if (state.sort === key) {
    state.direction = state.direction === 'asc' ? 'desc' : 'asc';
  } else {
    state.sort = key;
    state.direction = 'asc';
  }
  apply();
};
</script>

<template>
  <AdminLayout>
    <Head title="Admin - Users" />

    <div class="flex items-center justify-between mb-4">
      <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
      <div class="flex gap-2">
        <a href="/admin/export/users.csv" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-gray-700 to-gray-900 shadow-[0_8px_24px_rgba(31,41,55,0.35)] hover:shadow-[0_10px_28px_rgba(31,41,55,0.5)] hover:scale-[1.015]">Export CSV</a>
        <a href="/admin/export/users.xlsx" class="inline-flex items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold tracking-wide text-white transition-all duration-200 ease-out bg-gradient-to-r from-emerald-600 to-teal-700 shadow-[0_8px_24px_rgba(13,148,136,0.35)] hover:shadow-[0_10px_28px_rgba(13,148,136,0.5)] hover:scale-[1.015]">Export Excel</a>
      </div>
    </div>

    <!-- Filters Toolbar -->
    <div class="mb-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
      <div>
        <label class="text-xs text-gray-500">Search</label>
        <input v-model="state.q" @keyup.enter="apply" placeholder="Cari nama/email" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm" />
      </div>
      <div>
        <label class="text-xs text-gray-500">Verified</label>
        <select v-model="state.verified" class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-sm">
          <option value="">Semua</option>
          <option value="true">Verified</option>
          <option value="false">Unverified</option>
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

    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-x-auto">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
          <tr>
            <th class="px-3 py-2 text-left cursor-pointer" @click="sortBy('id')">ID</th>
            <th class="px-3 py-2 text-left cursor-pointer" @click="sortBy('name')">Name</th>
            <th class="px-3 py-2 text-left cursor-pointer" @click="sortBy('email')">Email</th>
            <th class="px-3 py-2 text-left cursor-pointer" @click="sortBy('email_verified_at')">Verified</th>
            <th class="px-3 py-2 text-left cursor-pointer" @click="sortBy('created_at')">Created</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="u in users.data" :key="u.id" class="border-t border-gray-100 dark:border-gray-700">
            <td class="px-3 py-2">{{ u.id }}</td>
            <td class="px-3 py-2">{{ u.name }}</td>
            <td class="px-3 py-2">{{ u.email }}</td>
            <td class="px-3 py-2">
              <span :class="u.email_verified_at ? 'text-green-600' : 'text-red-600'">
                {{ u.email_verified_at ? 'Yes' : 'No' }}
              </span>
            </td>
            <td class="px-3 py-2">{{ new Date(u.created_at).toLocaleDateString() }}</td>
          </tr>
        </tbody>
      </table>

      <div class="p-3 flex flex-col md:flex-row md:items-center md:justify-between gap-2 text-xs text-gray-500 dark:text-gray-400">
        <div>
          Showing {{ users.from }} - {{ users.to }} of {{ users.total }}
        </div>
        <div class="flex gap-1 overflow-x-auto">
          <Link v-for="link in users.links" :key="link.url || link.label" :href="link.url || '#'" v-html="link.label" :class="['px-2 py-1 rounded', link.active ? 'bg-blue-600 text-white' : '']" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
