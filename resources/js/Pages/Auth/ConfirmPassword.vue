<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Konfirmasi Password - Kilang Pertamina Internasional" />

        <div class="mb-6 text-sm text-gray-600 leading-relaxed">
            Ini adalah area aman dari aplikasi. Mohon konfirmasi password Anda sebelum melanjutkan.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="password" value="Password" class="text-gray-700 font-medium" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-lg"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    placeholder="Masukkan password Anda"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-6 flex justify-end">
                <PrimaryButton
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Memproses...</span>
                    <span v-else>Konfirmasi</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
