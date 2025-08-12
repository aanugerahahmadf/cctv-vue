<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password - Kilang Pertamina Internasional" />

        <div class="mb-6 text-sm text-gray-600 leading-relaxed">
            Masukkan password baru untuk akun Anda. Pastikan password baru Anda kuat dan mudah diingat.
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
                    readonly
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password Baru" class="text-gray-700 font-medium" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    placeholder="Masukkan password baru"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4">
                <InputLabel
                    for="password_confirmation"
                    value="Konfirmasi Password Baru"
                    class="text-gray-700 font-medium"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Konfirmasi password baru"
                />

                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-6 flex items-center justify-between">
                <a
                    :href="route('login')"
                    class="text-sm text-blue-600 hover:text-blue-800 underline transition-colors"
                >
                    Kembali ke login
                </a>

                <PrimaryButton
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Memproses...</span>
                    <span v-else>Reset Password</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
