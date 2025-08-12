<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const resendForm = useForm({});
const codeForm = useForm({ email: '', code: '' });

const submitResend = () => resendForm.post(route('verification.send'));
const submitCode = () => codeForm.post(route('verification.code')); 
</script>

<template>
    <GuestLayout>
        <Head title="Verifikasi Email" />

        <div class="mb-4 text-sm text-gray-600">
            Terima kasih telah mendaftar! Sebelum memulai, verifikasi alamat email Anda dengan mengklik link yang kami kirim atau masukkan kode verifikasi yang kami kirim ke Gmail Anda.
        </div>

        <div class="grid gap-6">
          <!-- Enter code -->
          <form @submit.prevent="submitCode" class="space-y-3">
            <div>
              <label class="text-sm text-gray-700">Email</label>
              <TextInput v-model="codeForm.email" type="email" required autocomplete="username" class="mt-1 w-full" />
            </div>
            <div>
              <label class="text-sm text-gray-700">Kode Verifikasi</label>
              <TextInput v-model="codeForm.code" type="text" required class="mt-1 w-full" />
            </div>
            <PrimaryButton :disabled="codeForm.processing">
              <span v-if="codeForm.processing">Memverifikasi...</span>
              <span v-else>Verifikasi dengan Kode</span>
            </PrimaryButton>
          </form>

          <!-- Or resend link -->
          <form @submit.prevent="submitResend">
              <PrimaryButton :disabled="resendForm.processing">
                  Kirim Ulang Link Verifikasi
              </PrimaryButton>
          </form>

          <div>
              <Link :href="route('logout')" method="post" as="button" class="mt-4 underline text-sm text-gray-600 hover:text-gray-900">
                  Logout
              </Link>
          </div>
        </div>
    </GuestLayout>
</template>
