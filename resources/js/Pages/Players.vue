<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';

const games = usePage().props.games;
</script>

<template>
    <Head title="Players" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Players</h1>
        </template>

        <Link :href="route('player.new')" class="btn btn-primary btn-lg"><i class="bi bi-plus-lg"></i> New player</Link>

    </AuthenticatedLayout>
</template>
