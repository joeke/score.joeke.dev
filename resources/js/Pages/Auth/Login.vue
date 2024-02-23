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
        <Head title="Login" />

        <h1 class="my-4 text-center">Pool Score</h1>

        <div class="col-12 col-sm-10 col-md-8 col-lg-6 m-auto mt-4 mt-md-5 p-4 p-md-5 bg-body-tertiary">
            <h2 class="mb-4">Login</h2>

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

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" v-model="form.remember" id="flexCheckDefault" />
                    <label class="form-check-label" for="flexCheckDefault">Remember me</label>
                </div>

                <div class="mt-4 d-flex justify-content-center">
                    <PrimaryButton class="btn-lg w-100" :disabled="form.processing">Login</PrimaryButton>
                </div>

                <div class="d-flex align-items-center justify-content-between mt-4">
                    <Link v-if="canResetPassword" :href="route('password.request')">Forgot password?</Link>
                </div>

                <hr />

                <div class="mt-4 text-center">
                    <div class="text-muted">
                        <p>Don't have an account yet?</p>
                    </div>
                    <Link :href="route('register')" class="btn btn-gray-600 bg-gradient">Register</Link>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>
