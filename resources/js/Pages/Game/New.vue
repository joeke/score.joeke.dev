<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    type: 0,
    score_goal: 0,
});
</script>

<template>
    <Head title="New game" />

    <AuthenticatedLayout>
        <template #header>
            <h1>New game</h1>
        </template>

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
        </form>
    </AuthenticatedLayout>
</template>
