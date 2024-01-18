<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    games: {
        type: Array,
        default: () => [],
    }
});
</script>

<template>
    <div class="table-list">
        <div class="table-list-row table-list-header">
            <div>ID</div>
            <div>Date</div>
            <div>Type</div>
            <div>Race to</div>
            <div>Player(s)</div>
        </div>
        <Link :href="route('game.show', game.id)" class="table-list-row" aria-current="true" v-for="game in games" :key="game.id">
            <div>#{{ game.id }}</div>
            <div>{{ game.start_date_formatted }}</div>
            <div>{{ game.type.name }}</div>
            <div>{{ game.score_goal }}</div>
            <div>{{ game.player.name }}<span v-if="game.opponent.name"> vs {{ game.opponent.name }}</span></div>
        </Link>
    </div>
</template>