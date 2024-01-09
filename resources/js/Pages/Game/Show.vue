<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm  } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const page = usePage();
const game = computed({
    get() {
        return page.props.game
    },
    set(newValue) {
        [page.props.game] = newValue
    }
})
const players = computed(() => page.props.players);
const scores = computed(() => page.props.scores);

let currentScore = ref(0);

const submitScore = (amount) => {
    const points = game.value.balls_left - amount;
    currentScore.value += points;

    if (amount === 1) {
        game.value.balls_left = 15;
        return;
    }

    const form = useForm({
        game: game.value.id,
        points: currentScore.value,
        player_id: game.value.active_player,
        balls_remaining: amount,
    });

    form.post(route('game.add-score'), {
        preserveScroll: true,
        onSuccess: () => {
            currentScore.value = 0;
            console.log('success');
        },
        onError: () => {
            console.log(form.errors);
        }
    });
};

const totalBalls = () => {
    return Array.from({length: 15}, (v, k) => k+1);
};

</script>

<template>
    <Head title="Game" />

    <AuthenticatedLayout>
        <template #header>
            <h2>Game #{{ game.id }}</h2>
        </template>

        <div class="game">
            <div class="player" :class="{active: game.active_player === player.id}" v-for="player in players" :key="player.id">
                <div class="name">{{ player.name }}</div>
                <div class="score">{{ player.total_points }}</div>
                <div class="current-run" :class="{'active' : game.active_player === player.id}">
                    Current run: <span>{{ currentScore }}</span>
                </div>
            </div>
        </div>

        <div class="actions">
            <div class="balls">
                <div class="row-label">Balls on table</div>
                <div v-for="ball in totalBalls()" :key="ball" class="item" :class="{ current: game.balls_left === ball, disabled: game.balls_left < ball}">
                    <input type="radio" class="btn-check" name="ball" :id="'ball_' + ball" autocomplete="off" :checked="game.balls_left === ball" :disabled="game.balls_left < ball" @click="submitScore(ball)">
                    <label class="btn" :for="'ball_' + ball">{{ ball }}</label>
                </div>
            </div>
        </div>

        <div class="stats" v-if="scores && Object.keys(scores).length">
            <div class="stats-row">
                <div class="row-label">High run</div>
                <div class="values">
                    <div class="value" v-for="score in scores" :key="score.id">
                        {{ score.high_run }}
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="row-label">Points per inning</div>
                <div class="values">
                    <div class="value" v-for="score in scores" :key="score.id">
                        {{ score.ppi }}
                    </div>
                </div>
            </div>
        </div>

        <pre>{{ game }}</pre>
        <hr>
        <pre>{{ scores }}</pre>
    </AuthenticatedLayout>
</template>
