<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GamesList from '@/Pages/Game/Partials/GamesList.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const games = usePage().props.games;
</script>

<template>
    <Head title="Games" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Games</h1>
        </template>

        <Link :href="route('game.new')" class="btn btn-primary"><i class="bi bi-plus-lg"></i> New game</Link>

        <div class="mt-4">
            <GamesList :games="games" :showRowEditButton="true" :showRowDeleteButton="true" />
        </div>

    </AuthenticatedLayout>

</template>