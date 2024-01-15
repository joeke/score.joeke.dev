<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const players = usePage().props.players;
</script>

<template>
    <Head title="Players" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Players</h1>
        </template>

        <Link :href="route('player.new')" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i> New player</Link>

        <div class="mt-5">
            <h2>Players</h2>

            <div class="table-list">
                <div class="table-list-row table-list-header">
                    <div>ID</div>
                    <div>Name</div>
                    <div>Games</div>
                </div>
                <Link :href="route('player.show', player.id)" class="table-list-row" aria-current="true" v-for="player in players" :key="player.id">
                    <div>#{{ player.id }}</div>
                    <div>{{ player.name }}</div>
                    <div>{{ player.games_count }}</div>
                </Link>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
