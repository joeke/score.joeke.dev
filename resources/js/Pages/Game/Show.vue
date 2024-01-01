<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, usePage } from '@inertiajs/vue3';

const game = usePage().props.game;

const submit = () => {
    form.post(route('game.update'));
};

</script>

<template>
    <Head title="Game" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Game #{{ game.id }}</h1>
        </template>

        {{ game }}
    </AuthenticatedLayout>
</template>
