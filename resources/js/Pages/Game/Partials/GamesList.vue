<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    games: {
        type: Array,
        default: () => [],
    },
    showMoreButton: {
        type: Boolean,
        default: false,
    },
});
</script>

<template>
    <div class="table-list" v-if="games.length > 0">
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

        <Link :href="route('games')" class="btn btn-gray-600 mt-3" v-if="showMoreButton">View all games</Link>
    </div>

    <div v-else class="py-2">
        <em>No games found. <a :href="route('game.new')">Create a new game</a></em>
    </div>
</template>