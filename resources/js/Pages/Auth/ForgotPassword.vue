<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Lupa Password - Kilang Pertamina Internasional" />

        <div class="mb-6 text-sm text-gray-600 leading-relaxed">
            Lupa password Anda? Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan link reset password yang memungkinkan Anda memilih password baru.
        </div>

        <div
            v-if="status"
            class="mb-6 p-4 text-sm font-medium text-green-700 bg-green-100 rounded-lg border border-green-200"
        >
            {{ status }}
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
                    <span v-if="form.processing">Mengirim...</span>
                    <span v-else>Kirim Link Reset Password</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
