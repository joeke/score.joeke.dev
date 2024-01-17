<script setup>
import { usePage, Link } from '@inertiajs/vue3';

const games = usePage().props.games;
</script>

<template>
    <div class="table-list">
        <div class="table-list-row table-list-header">
            <div>ID</div>
            <div>Date</div>
            <div>Type</div>
            <div>Player(s)</div>
        </div>
        <Link :href="route('game.show', game.id)" class="table-list-row" aria-current="true" v-for="game in games" :key="game.id">
            <div>#{{ game.id }}</div>
            <div>{{ game.start_date_formatted }}</div>
            <div>{{ game.type.name }}</div>
            <div>{{ game.player.name }}<span v-if="game.opponent.name"> vs {{ game.opponent.name }}</span></div>
        </Link>
    </div>
</template>