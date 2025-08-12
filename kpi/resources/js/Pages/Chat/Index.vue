<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps({ messages: Array, admins: Array })
const form = useForm({ recipient_id: props.admins[0]?.id ?? null, body: '' })

function send() {
  if (!form.recipient_id || !form.body) return
  form.post('/chat/send', { onSuccess: () => (form.body = '') })
}
</script>

<template>
  <Head title="Chat" />
  <div class="p-6 max-w-3xl">
    <h1 class="text-xl font-bold mb-4">Chat ke Admin</h1>
    <div class="mb-3">
      <select v-model="form.recipient_id" class="border p-2 rounded">
        <option v-for="a in admins" :key="a.id" :value="a.id">{{ a.name }} — {{ a.email }}</option>
      </select>
    </div>
    <div class="border rounded p-3 h-80 overflow-y-auto bg-white dark:bg-gray-800">
      <div v-for="m in messages" :key="m.id" class="mb-2">
        <div class="text-xs opacity-60">{{ new Date(m.created_at).toLocaleString() }}</div>
        <div class="p-2 rounded bg-gray-100 dark:bg-gray-700">{{ m.body }}</div>
      </div>
    </div>
    <div class="mt-3 flex gap-2">
      <input v-model="form.body" @keyup.enter="send" placeholder="Tulis pesan..." class="flex-1 border p-2 rounded" />
      <button @click="send" class="px-4 py-2 rounded bg-blue-600 text-white">Kirim</button>
    </div>
  </div>
</template>