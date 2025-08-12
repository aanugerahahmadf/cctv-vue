<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Masuk - Kilang Pertamina Internasional" />

        <div v-if="status" class="mb-6 p-4 text-sm font-medium text-green-700 bg-green-100 rounded-lg border border-green-200">
            {{ status }}
        </div>

        <!-- Gmail Login Button -->
        <div class="mb-6">
            <a
                :href="route('google.redirect')"
                class="flex items-center justify-center gap-3 w-full rounded-lg bg-red-600 text-white py-3 px-4 hover:bg-red-700 transition-all duration-200 shadow-lg hover:shadow-xl hover:scale-[1.01] font-medium"
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
                <span class="px-2 bg-white text-gray-500">atau masuk dengan email</span>
            </div>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" class="text-gray-700 font-medium" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.email"
                    required
                    autofocus
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
                    autocomplete="current-password"
                    placeholder="Masukkan password Anda"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-blue-600 hover:text-blue-800 underline transition-colors"
                >
                    Lupa password?
                </Link>

                <PrimaryButton
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Memproses...</span>
                    <span v-else>Masuk</span>
                </PrimaryButton>
            </div>
        </form>

        <!-- Register Link -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Belum punya akun? 
                <Link
                    :href="route('register')"
                    class="text-blue-600 hover:text-blue-800 font-medium underline transition-colors"
                >
                    Daftar di sini
                </Link>
            </p>
        </div>
    </GuestLayout>
</template>
