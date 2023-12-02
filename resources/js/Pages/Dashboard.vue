<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Dashboard</h2>
        </template>

        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <button class="btn btn-secondary btn-lg">
                        <i class="bi bi-plus-lg"></i> New match
                    </button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-lg">View matches</button>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-lg">New match</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
