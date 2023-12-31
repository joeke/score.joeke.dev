<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Dashboard</h1>
        </template>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <a class="btn btn-primary btn-lg" :href="route('game.new')">
                        <i class="bi bi-plus-lg"></i> New game
                    </a>
                </div>
                <div class="col-md-4">

                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
