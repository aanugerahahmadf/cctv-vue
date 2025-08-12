<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Verifikasi Email - Kilang Pertamina Internasional" />

        <div class="mb-6 text-sm text-gray-600 leading-relaxed">
            Terima kasih telah mendaftar! Sebelum memulai, mohon verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirim ke email Anda. Jika Anda tidak menerima email, kami akan dengan senang hati mengirimkan yang lain.
        </div>

        <div
            class="mb-6 p-4 text-sm font-medium text-green-700 bg-green-100 rounded-lg border border-green-200"
            v-if="verificationLinkSent"
        >
            Link verifikasi baru telah dikirim ke alamat email yang Anda berikan saat pendaftaran.
        </div>

        <form @submit.prevent="submit">
            <div class="mt-6 flex items-center justify-between">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-sm text-blue-600 hover:text-blue-800 underline transition-colors"
                >
                    Keluar
                </Link>

                <PrimaryButton
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing">Mengirim...</span>
                    <span v-else>Kirim Ulang Email Verifikasi</span>
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
