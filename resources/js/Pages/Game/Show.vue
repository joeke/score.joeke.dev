<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, useForm, useRemember } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import {Modal} from 'bootstrap';

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
let ballsRemaining = ref(game.value.balls_left);
let ballsRemainingPrevious = useRemember(ballsRemaining.value);
let foulPoints = ref(0);
let scoreModal = ref(null)

onMounted(() => {
    scoreModal = new Modal('#scoreModal', {})
})

function openScoreModal(ballsAmount) {
    const points = ballsRemaining.value - ballsAmount;
    ballsRemaining.value = ballsAmount;
    currentScore.value += points;

    scoreModal.show();
}

function closeScoreModal() {
    scoreModal.hide();
}

function validateBallsRemaining() {
    if (ballsRemaining.value > ballsRemainingPrevious.value) {
        ballsRemaining.value = ballsRemainingPrevious.value;
    }
}

const submitScore = () => {
    // When only 1 ball is left, continue the run for this player
    // And set the remaining balls on table to 15 again
    if (ballsRemaining.value === 1) {
        ballsRemaining.value = 15;
        ballsRemainingPrevious.value = 15;
        closeScoreModal();
        return;
    }

    const form = useForm({
        game: game.value.id,
        points: currentScore.value,
        foul_points: foulPoints.value,
        player_id: game.value.active_player,
        balls_remaining: ballsRemaining.value,
    });

    form.post(route('game.add-score'), {
        preserveScroll: true,
        onSuccess: () => {
            currentScore.value = 0;
            foulPoints.value = 0;
            ballsRemainingPrevious.value = ballsRemaining.value;
            closeScoreModal();
        },
        onError: () => {
            // console.log(form.errors);
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
            <div class="d-flex align-items-center gap-4">
                <h2>Game #{{ game.id }}</h2>
                <h6 class="ms-auto date text-gray-200">Started on: {{ game.start_date_formatted }}</h6>
            </div>
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
                <div v-for="ball in totalBalls()" :key="ball" class="item" :class="{ current: ballsRemaining === ball, disabled: ballsRemaining < ball}">
                    <input type="radio" class="btn-check" :id="'ball_' + ball" v-model="ballsRemaining" :value="ball" :disabled="ballsRemaining < ball" @click="openScoreModal(ball)">
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

        <div class="modal" id="scoreModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add score</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="score-form">
                            <div class="mb-3" v-if="game.active_player && players[game.active_player]">
                                <label>For player:</label>
                                <div>
                                    <span>{{ players[game.active_player]['name'] }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Balls on table:</label>
                                <div class="input-group">
                                    <button class="btn btn-light" type="button">-</button>
                                    <input type="number" class="form-control" v-model="ballsRemaining" min="1" :max="ballsRemainingPrevious" @keyup="validateBallsRemaining">
                                    <button class="btn btn-primary" type="button">+</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Foul points:</label>
                                <div class="input-group">
                                    <button class="btn btn-light" type="button">-</button>
                                    <input type="number" class="form-control" v-model="foulPoints">
                                    <button class="btn btn-primary" type="button">+</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Current run:</label>
                                <div>{{ currentScore }}</div>
                            </div>
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-light" @click="closeScoreModal"> Cancel </button>
                            <button class="btn btn-primary ms-auto" @click="submitScore">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <pre>{{ game }}</pre>
        <hr>
        <pre>{{ scores }}</pre>
        <hr>
        <pre>{{ players }}</pre>
    </AuthenticatedLayout>
</template>
