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

        <div class="mt-5">
            <h2 class="mb-3">My statistics</h2>

            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            High runs
                        </div>

                        <div class="card-body" v-if="statistics['highRuns'] && statistics['highRuns'].length">
                            <h3 v-for="highRun in statistics['highRuns']" :key="highRun">{{ highRun }}</h3>
                        </div>

                        <div class="card-body" v-else>
                            <em class="text-muted">No high runs yet</em>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            Average run
                        </div>

                        <div class="card-body">
                            <h3>{{ statistics.averageRun }}</h3>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="card text-center mb-4">
                        <div class="card-header">
                            Games played
                        </div>

                        <div class="card-body">
                            <h3>{{ statistics.gamesCount }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
