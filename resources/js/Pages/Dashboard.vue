<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GamesList from '@/Pages/Game/Partials/GamesList.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const games = usePage().props.games;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Home</h1>
        </template>

        <Link :href="route('game.new')" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i> New game</Link>

        <div class="mt-5">
            <h2>My games</h2>

            <GamesList :games="games" :showMoreButton="true"/>
        </div>

        <div class="mt-5">
            <h2>My stats</h2>


        </div>

    </AuthenticatedLayout>
</template>
