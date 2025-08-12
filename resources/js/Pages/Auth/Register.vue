<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Daftar - Kilang Pertamina Internasional" />

        <!-- Gmail Register Button -->
        <div class="mb-6">
            <a
                :href="route('google.redirect')"
                class="flex items-center justify-center gap-3 w-full rounded-lg bg-red-600 text-white py-3 px-4 hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl font-medium"
            >
                <img alt="Gmail" src="https://www.gstatic.com/images/branding/product/1x/gmail_2020q4_48dp.png" class="w-5 h-5" />
                <span>Lanjutkan dengan Gmail</span>
            </a>
        </div>

        <!-- Divider -->
        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-2 bg-white text-gray-500">atau daftar dengan email</span>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Nama Lengkap" class="text-gray-700 font-medium" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap Anda"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" class="text-gray-700 font-medium" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="Masukkan email Anda"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" class="text-gray-700 font-medium" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Masukkan password Anda"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Konfirmasi Password"
                    class="text-gray-700 font-medium"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Konfirmasi password Anda"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    :href="route('login')"
                    class="text-sm text-blue-600 hover:text-blue-800 underline transition-colors"
                >
                    Sudah punya akun?
                </Link>

                <PrimaryButton
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Memproses...</span>
                    <span v-else>Daftar</span>
                </PrimaryButton>
            </div>
        </form>

        <!-- Terms and Conditions -->
        <div class="mt-6 text-center">
            <p class="text-xs text-gray-500">
                Dengan mendaftar, Anda menyetujui 
                <a href="#" class="text-blue-600 hover:text-blue-800 underline">Syarat dan Ketentuan</a> 
                serta 
                <a href="#" class="text-blue-600 hover:text-blue-800 underline">Kebijakan Privasi</a> 
                kami.
            </p>
        </div>
    </GuestLayout>
</template>
