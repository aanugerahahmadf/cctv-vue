<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';

const sendEmailCodeForm = useForm({ new_email: '' });
const applyEmailForm = useForm({ new_email: '', code: '' });
const sendPasswordCodeForm = useForm({});
const applyPasswordForm = useForm({ code: '', password: '', password_confirmation: '' });
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdateProfileInformationForm
                        :must-verify-email="$page.props.auth.user?.mustVerifyEmail"
                        :status="$page.props.status"
                    />
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <UpdatePasswordForm />
                </div>

                <!-- Change Email with OTP -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg grid gap-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Change Email with OTP</h3>

                    <form @submit.prevent="sendEmailCodeForm.post(route('profile.security.send-email-code'))" class="grid md:grid-cols-3 gap-3 items-end">
                        <div class="md:col-span-2">
                            <InputLabel value="New Email" />
                            <TextInput v-model="sendEmailCodeForm.new_email" type="email" class="mt-1 w-full" required />
                            <InputError :message="sendEmailCodeForm.errors.new_email" class="mt-2" />
                        </div>
                        <PrimaryButton :disabled="sendEmailCodeForm.processing">Send Code</PrimaryButton>
                    </form>

                    <form @submit.prevent="applyEmailForm.post(route('profile.security.change-email'))" class="grid md:grid-cols-3 gap-3 items-end">
                        <div>
                            <InputLabel value="New Email" />
                            <TextInput v-model="applyEmailForm.new_email" type="email" class="mt-1 w-full" required />
                            <InputError :message="applyEmailForm.errors.new_email" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel value="Verification Code" />
                            <TextInput v-model="applyEmailForm.code" type="text" class="mt-1 w-full" required />
                            <InputError :message="applyEmailForm.errors.code" class="mt-2" />
                        </div>
                        <PrimaryButton :disabled="applyEmailForm.processing">Apply Email</PrimaryButton>
                    </form>
                </div>

                <!-- Change Password with OTP -->
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg grid gap-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password with OTP</h3>

                    <form @submit.prevent="sendPasswordCodeForm.post(route('profile.security.send-password-code'))" class="grid md:grid-cols-3 gap-3 items-end">
                        <div class="md:col-span-2 text-sm text-gray-600 dark:text-gray-300">We will send a verification code to your current email.</div>
                        <PrimaryButton :disabled="sendPasswordCodeForm.processing">Send Code</PrimaryButton>
                    </form>

                    <form @submit.prevent="applyPasswordForm.post(route('profile.security.change-password'))" class="grid md:grid-cols-4 gap-3 items-end">
                        <div>
                            <InputLabel value="Verification Code" />
                            <TextInput v-model="applyPasswordForm.code" type="text" class="mt-1 w-full" required />
                            <InputError :message="applyPasswordForm.errors.code" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel value="New Password" />
                            <TextInput v-model="applyPasswordForm.password" type="password" class="mt-1 w-full" required />
                            <InputError :message="applyPasswordForm.errors.password" class="mt-2" />
                        </div>
                        <div>
                            <InputLabel value="Confirm Password" />
                            <TextInput v-model="applyPasswordForm.password_confirmation" type="password" class="mt-1 w-full" required />
                            <InputError :message="applyPasswordForm.errors.password_confirmation" class="mt-2" />
                        </div>
                        <PrimaryButton :disabled="applyPasswordForm.processing">Apply Password</PrimaryButton>
                    </form>
                </div>

                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <DeleteUserForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
