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

            <div class="games-list">
                <div class="games-list-row games-list-header">
                    <div>ID</div>
                    <div>Date</div>
                    <div>Type</div>
                    <div>Player(s)</div>
                </div>
                <Link :href="route('game.show', game.id)" class="games-list-row" aria-current="true" v-for="game in games" :key="game.id">
                    <div>#{{ game.id }}</div>
                    <div>{{ game.start_date_formatted }}</div>
                    <div>{{ game.type.name }}</div>
                    <div>{{ game.player.name }}<span v-if="game.opponent.name"> vs {{ game.opponent.name }}</span></div>
                </Link>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
