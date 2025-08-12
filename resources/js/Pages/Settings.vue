<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import { ref, watch, onMounted } from 'vue';

const theme = ref(localStorage.getItem('theme') || 'system');
const applyTheme = () => {
  if (theme.value === 'dark' || (theme.value === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  localStorage.setItem('theme', theme.value);
};
watch(theme, applyTheme);
onMounted(applyTheme);
</script>

<template>
  <UserLayout :user="$page.props.auth.user">
    <Head title="Settings - Kilang Pertamina Internasional" />

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-8">
      <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Settings</h1>

      <!-- Appearance -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Tampilan</h2>
        <div class="flex items-center gap-4">
          <label class="text-sm text-gray-700 dark:text-gray-300">Mode Tema</label>
          <select v-model="theme" class="px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
            <option value="light">Light</option>
            <option value="dark">Night</option>
            <option value="system">System</option>
          </select>
        </div>
      </div>

      <!-- Profile Info -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Data Pribadi</h2>
        <UpdateProfileInformationForm :must-verify-email="$page.props.mustVerifyEmail" :status="$page.props.status" />
      </div>

      <!-- Password -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Keamanan</h2>
        <UpdatePasswordForm />
      </div>

      <!-- Danger Zone -->
      <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Akun</h2>
        <DeleteUserForm />
      </div>
    </div>
  </UserLayout>
</template>
