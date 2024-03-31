<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GamesList from '@/Pages/Game/Partials/GamesList.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const games = usePage().props.games;
const statistics = usePage().props.statistics;
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Home</h1>
        </template>

        <Link :href="route('game.new')" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i> New game</Link>

        <div class="mt-5">
            <h2 class="mb-3">My games</h2>

            <GamesList :games="games" :showMoreButton="true"/>
        </div>

        <div class="my-5">
            <h2 class="mb-3">My statistics</h2>

            <div class="table-list" v-if="games.length > 0">
                <div class="table-list-row">
                    <div class="w-min">Games played:</div>
                    <div>{{ statistics.games.total }}</div>
                </div>

                <div class="table-list-row">
                    <div class="w-min">Vs opponent:</div>
                    <div>{{ statistics.games.versus }}</div>
                </div>

                <div class="table-list-row">
                    <div class="w-min">Solo:</div>
                    <div>{{ statistics.games.solo }}</div>
                </div>

                <div class="table-list-row">
                    <div class="w-min">High runs:</div>
                    <div>
                        {{ statistics.highRuns.length ? statistics.highRuns.join(', ') : 'No high runs yet' }}
                    </div>
                </div>

                <div class="table-list-row">
                    <div class="w-min">Average run:</div>
                    <div>{{ statistics.averageRun }}</div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
