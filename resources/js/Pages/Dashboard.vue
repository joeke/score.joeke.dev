<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const games = usePage().props.games;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Dashboard</h1>
        </template>

        <Link :href="route('game.new')" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i> New game</Link>

        <div class="mt-5">
            <h2>Games</h2>

            <div class="list-group games-list">
                <Link :href="route('game.show', game.id)" class="list-group-item list-group-item-action" aria-current="true" v-for="game in games" :key="game.id">
                    <span>#{{ game.id }}</span>
                    <span>{{ game.start_date_formatted }}</span>
                    <span>{{ game.type }}</span>
                    <span>{{ game.player_name }}</span>
                    <span>{{ game.opponent_name }}</span>
                </Link>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
    .games-list {
        .list-group-item {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
    }
</style>