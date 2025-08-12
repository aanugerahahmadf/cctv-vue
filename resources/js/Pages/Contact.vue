<script setup>
import { Head } from '@inertiajs/vue3';
import UserLayout from '@/Layouts/UserLayout.vue';

defineProps({
    contacts: {
        type: Array,
        required: true,
    },
});

const buildLink = (contact) => {
    if (contact.type === 'email' || contact.email) {
        return `mailto:${contact.email}`;
    }
    if (contact.type === 'phone' || contact.phone) {
        return `tel:${contact.phone}`;
    }
    if (contact.type === 'whatsapp' || contact.whatsapp) {
        const number = (contact.whatsapp || '').replace(/[^0-9]/g, '');
        return `https://wa.me/${number}`;
    }
    if (contact.type === 'address' || contact.address) {
        return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(contact.address)}`;
    }
    return '#';
};
</script>

<template>
    <UserLayout :user="$page.props.auth.user">
        <Head title="Kontak - Kilang Pertamina Internasional" />

        <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kontak</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Hubungi kami melalui Email, Telepon, Alamat, atau WhatsApp.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <a
                    v-for="contact in contacts"
                    :key="contact.id"
                    :href="buildLink(contact)"
                    target="_blank"
                    rel="noopener"
                    class="block bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-md transition-shadow"
                >
                    <div class="flex items-start">
                        <div class="w-10 h-10 rounded-lg bg-blue-500 flex items-center justify-center text-white mr-4">
                            <svg v-if="contact.type === 'email' || contact.email" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8m-2 8H5a2 2 0 01-2-2V8"></path>
                            </svg>
                            <svg v-else-if="contact.type === 'phone' || contact.phone" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3l2 5-2 2a16 16 0 006 6l2-2 5 2v3a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <svg v-else-if="contact.type === 'whatsapp' || contact.whatsapp" class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20 3.5A10.5 10.5 0 005.2 18.8L3 21l2.3-2.2A10.5 10.5 0 1020 3.5zM7.7 9.3c.2-.5.4-.6.8-.6h.6c.2 0 .4 0 .5.4s.7 1.6.7 1.7c.1.2.1.3 0 .5-.1.2-.2.3-.4.5-.2.2-.3.3-.1.6.2.2 1 .9 2 1.4.7.4 1.3.6 1.5.7.2.1.4.1.5-.1.2-.2.6-.7.7-.9.2-.2.3-.2.5-.1.2.1 1.3.6 1.5.7.2.1.4.1.5.2 0 .2 0 1 0 1.2 0 .2-.1.5-.3.7-.2.2-.7.7-1.4.7-.7.1-1.3 0-2.2-.4-1.2-.5-2.5-1.2-3.6-2.2A12.2 12.2 0 017.5 12c-.3-.6-.6-1-.6-1.6 0-.6.3-1 .4-1.2z"/>
                            </svg>
                            <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ contact.name || contact.type }}</h3>
                            <p v-if="contact.email" class="text-sm text-gray-600 dark:text-gray-400">{{ contact.email }}</p>
                            <p v-if="contact.phone" class="text-sm text-gray-600 dark:text-gray-400">{{ contact.phone }}</p>
                            <p v-if="contact.whatsapp" class="text-sm text-gray-600 dark:text-gray-400">{{ contact.whatsapp }}</p>
                            <p v-if="contact.address" class="text-sm text-gray-600 dark:text-gray-400">{{ contact.address }}</p>
                            <p class="mt-2 text-sm text-blue-600 dark:text-blue-400 underline">Klik untuk membuka</p>
                        </div>
                    </div>
                </a>
            </div>

            <div v-if="contacts.length === 0" class="text-center py-12">
                <p class="text-gray-600 dark:text-gray-400">Belum ada data kontak.</p>
            </div>
        </div>
    </UserLayout>
</template>
