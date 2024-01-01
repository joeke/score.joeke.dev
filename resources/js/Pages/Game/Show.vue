<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, usePage } from '@inertiajs/vue3';

const game = usePage().props.game;
const scores = usePage().props.scores;

const submit = () => {
    form.post(route('game.update'));
};

</script>

<template>
    <Head title="Game" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Game #{{ game.id }}</h2>
        </template>

        <div class="game">
            <div class="player">
                <h4>{{ game.player_name }}</h4>
                <div class="score">{{ scores.player.total }}</div>
            </div>

            <div v-if="game.opponent_id" class="player">
                <h4>{{ game.opponent_name }}</h4>
                <div class="score">{{ scores.opponent.total }}</div>
            </div>
        </div>

        {{ scores }}

        {{ game }}
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
    .game {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;

        .player {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .score {
            font-size: 5rem;
            line-height: 1;
        }
    }
</style>