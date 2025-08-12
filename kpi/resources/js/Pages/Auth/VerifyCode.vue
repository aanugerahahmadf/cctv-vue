<script setup>
import { Head, useForm } from '@inertiajs/vue3'

const form = useForm({ email: '', purpose: 'reset_password', code: '' })

function submit() {
  form.post('/auth/code/verify')
}
</script>

<template>
  <Head title="Verifikasi Kode" />
  <div class="p-6 max-w-md mx-auto">
    <h1 class="text-xl font-bold mb-4">Verifikasi Kode</h1>
    <div class="space-y-3">
      <input v-model="form.email" type="email" placeholder="Email" class="w-full border p-2 rounded" />
      <select v-model="form.purpose" class="w-full border p-2 rounded">
        <option value="reset_password">Reset Password</option>
        <option value="verify_email">Verify Email</option>
      </select>
      <input v-model="form.code" placeholder="Kode 6 digit" class="w-full border p-2 rounded tracking-widest" />
      <button @click="submit" class="px-4 py-2 rounded bg-blue-600 text-white">Verifikasi</button>
      <div v-if="$page.props.errors?.code" class="text-red-600">{{ $page.props.errors.code }}</div>
      <div v-if="$page.props.flash?.status" class="text-green-600">{{ $page.props.flash.status }}</div>
    </div>
  </div>
</template>