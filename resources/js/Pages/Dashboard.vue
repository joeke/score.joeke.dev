<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GamesList from '@/Pages/Game/Partials/GamesList.vue';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Dashboard</h1>
        </template>

        <Link :href="route('game.new')" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i> New game</Link>

        <div class="mt-5">
            <h2>My games</h2>

            <GamesList />
        </div>

    </AuthenticatedLayout>
</template>
