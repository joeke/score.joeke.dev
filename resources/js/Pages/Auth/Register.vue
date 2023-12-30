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
        <Head title="Register" />

        <div class="col-12 col-md-6 m-auto mt-5 p-5 bg-body-tertiary">
            <h1 class="mb-5">Register</h1>

            <form @submit.prevent="submit" class="w-100">
                <div class="form-floating mb-3">
                    <TextInput
                        id="name"
                        type="text"
                        :class="form.errors.name ? 'is-invalid' : ''"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="Name"
                    />
                    <InputLabel for="name" value="Name" />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div class="form-floating mb-3">
                    <TextInput
                        id="email"
                        type="email"
                        :class="form.errors.email ? 'is-invalid' : ''"
                        v-model="form.email"
                        required
                        placeholder="Email"
                    />
                    <InputLabel for="email" value="Email" />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="form-floating mb-3">
                    <TextInput
                        id="password"
                        type="password"
                        :class="form.errors.password ? 'is-invalid' : ''"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                    <InputLabel for="password" value="Password" />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="form-floating mb-3">
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        :class="form.errors.password_confirmation ? 'is-invalid' : ''"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm Password"
                    />
                    <InputLabel for="password_confirmation" value="Confirm Password" />

                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <div class="d-flex align-items-center justify-content-between mt-5">
                    <Link
                        :href="route('login')"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Already registered?
                    </Link>

                    <PrimaryButton class="btn-lg" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Register
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
