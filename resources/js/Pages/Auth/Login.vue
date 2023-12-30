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
        onFinish: () => form.reset('password')
    });
};
</script>

<template>
    <GuestLayout>
        <div class="col-12 col-md-6 m-auto mt-5 p-5 bg-body-tertiary">
            <Head title="Login" />

            <h1 class="mb-5">Login</h1>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="w-100">
                <div class="form-floating mb-3">
                    <TextInput
                        id="email"
                        type="email"
                        :class="form.errors.email ? 'is-invalid' : ''"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
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
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                    <InputLabel for="password" value="Password" />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div>
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                    </label>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-5">
                    <Link :href="route('register')">Register</Link>
                    <Link v-if="canResetPassword" :href="route('password.request')">Forgot your password?</Link>
                    <PrimaryButton class="btn-lg" :disabled="form.processing">Login</PrimaryButton>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
