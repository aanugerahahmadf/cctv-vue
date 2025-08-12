<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({ email: '', purpose: 'reset_password' })

function submit() {
  form.post('/auth/code/send')
}
</script>

<template>
  <Head title="Kirim Kode Verifikasi" />
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Kirim Kode Verifikasi</h1>
    <div class="space-y-3">
      <input v-model="form.email" type="email" placeholder="Email" class="w-full border p-2 rounded" />
      <select v-model="form.purpose" class="w-full border p-2 rounded">
        <option value="reset_password">Reset Password</option>
        <option value="verify_email">Verify Email</option>
      </select>
      <button @click="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Kirim</button>
      <div v-if="$page.props.flash?.status" class="text-green-600">{{ $page.props.flash.status }}</div>
    </div>
  </div>
</template>