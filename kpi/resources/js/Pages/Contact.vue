<script setup>
import { Head } from '@inertiajs/vue3'

const props = defineProps({ email: String, phone: String, whatsapp: String, address: String })
</script>

<template>
  <Head title="Contact" />
  <div class="p-6 max-w-2xl">
    <h1 class="text-xl font-bold mb-4">Contact</h1>
    <div class="space-y-3">
      <div>
        <div class="text-sm opacity-70">Gmail</div>
        <a :href="`mailto:${email}`" class="text-blue-600">{{ email }}</a>
      </div>
      <div>
        <div class="text-sm opacity-70">Telepon</div>
        <a :href="`tel:${phone}`" class="text-blue-600">{{ phone }}</a>
      </div>
      <div>
        <div class="text-sm opacity-70">WhatsApp</div>
        <a :href="`https://wa.me/${whatsapp.replace(/\D/g,'')}`" target="_blank" class="text-blue-600">{{ whatsapp }}</a>
      </div>
      <div>
        <div class="text-sm opacity-70">Alamat</div>
        <a :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(address)}`" target="_blank" class="text-blue-600">{{ address }}</a>
      </div>
    </div>
  </div>
</template>