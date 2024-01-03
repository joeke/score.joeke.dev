<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, usePage } from '@inertiajs/vue3';

const game = usePage().props.game;
const players = usePage().props.players;
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
            <div class="player" v-for="player in players" :key="player.id">
                <div class="name">{{ player.name }}</div>
                <div class="score">{{ player.total_points }}</div>
            </div>
        </div>

        <div class="stats" v-if="scores && scores.length">
            <div class="stats-row">
                <div class="label">High run</div>
                <div class="values">
                    <div class="value" v-for="score in scores" :key="score.id">
                        {{ score.high_run }}
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="label">Points per inning</div>
                <div class="values">
                    <div class="value" v-for="score in scores" :key="score.id">
                        {{ score.ppi }}
                    </div>
                </div>
            </div>
        </div>

        <pre>{{ players }}</pre>
        <pre>{{ scores }}</pre>
    </AuthenticatedLayout>
</template>

<style lang="scss">
    .game {
        display: grid;
        grid-auto-columns: minmax(0, 1fr);
        grid-auto-rows: 1fr;
        grid-auto-flow: column;

        .player {
            display: grid;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border-right: 1px solid rgba(255,255,255,0.1);
            padding: .75rem;

            &:last-child {
                border-right: none;
            }

            &.active {
                background-color: rgba(255,255,255,0.1);
            }
        }

        .name {
            font-size: 1.1rem;
            line-height: 1;
            text-align: center;
            align-self: flex-start;
        }

        .score {
            font-size: 4rem;
            line-height: 1;
            text-align: center;
            align-self: flex-end;
        }
    }

    .stats {
        display: grid;
        margin: 1rem 0;

        .stats-row {
            display: flex;
            flex-direction: column;
            padding: 0 0 0.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);

            &:last-child {
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
        }
        .label {
            font-size: 1rem;
            text-align: center;
            width: 100%;
            padding: 0.1rem 0;
            margin-bottom: 0.5rem;
            background-color: rgba(255,255,255,0.1);
        }
        .values {
            display: flex;
            justify-content: space-around;
            font-size: 1.25rem;
            text-align: center;

            > div {
                width: 50;
            }
        }


    }
</style>