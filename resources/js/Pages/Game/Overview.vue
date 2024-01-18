<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GamesList from '@/Pages/Game/Partials/GamesList.vue';
import { Head, usePage } from '@inertiajs/vue3';

const games = usePage().props.games;
</script>

<template>
    <Head title="Games" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Games</h1>
        </template>

        <GamesList :games="games" />

    </AuthenticatedLayout>

</template>