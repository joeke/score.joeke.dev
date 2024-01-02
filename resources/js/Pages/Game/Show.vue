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
                <div class="name">{{ game.player_name }}</div>
                <div class="score">{{ scores.player.total }}</div>
            </div>

            <div v-if="game.opponent_id" class="player">
                <div class="name">{{ game.opponent_name }}</div>
                <div class="score">{{ scores.opponent.total }}</div>
            </div>
        </div>

        {{ scores }}

        {{ game }}
    </AuthenticatedLayout>
</template>

<style lang="scss" scoped>
    .game {
        display: grid;
        grid-auto-columns: minmax(0, 1fr);
        grid-auto-rows: 1fr;
        grid-auto-flow: column;

        .player {
            display: grid;
            align-items: center;
            justify-content: center;
        }

        .name {
            font-size: 1.25rem;
            line-height: 1;
            text-align: center;
            align-self: flex-start;
        }

        .score {
            font-size: 5rem;
            line-height: 1;
            text-align: center;
            align-self: flex-end;
        }
    }
</style>