<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, usePage, useForm, useRemember } from '@inertiajs/vue3';
    import { computed, ref, onMounted, watch } from 'vue';
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
    const maxBalls = 15;

    let currentScore = ref(0);
    let ballsRemaining = ref(game.value.balls_left);
    let ballsRemainingPrevious = useRemember(ballsRemaining.value);
    let foulPoints = ref(0);
    let scoreModal = ref(null);
    let undoModal = ref(null);
    let lastScore = ref(null);

    onMounted(() => {
        scoreModal = new Modal('#scoreModal', {});
        undoModal = new Modal('#undoModal', {});
    })

    watch(() => ballsRemaining.value, (newValue, oldValue) => {
        if (newValue === maxBalls) {
            return;
        }
        countPoints(newValue - (oldValue - newValue));
    })

    const openScoreModal = (ballsAmount) => {
        ballsRemaining.value = ballsAmount;

        scoreModal.show();
    }

    const closeScoreModal = () => {
        scoreModal.hide();
    }

    const openUndoModal = () => {
        undoModal.show();
    }

    const countPoints = (amount) => {
        const points = ballsRemaining.value - amount;
        currentScore.value += points;
    }

    const countBallsRemaining = (amount) => {
        if (ballsRemaining.value + amount < 1) {
            ballsRemaining.value = 1;
            return;
        }
        ballsRemaining.value += amount;

        validateBallsRemaining();
    }

    const validateBallsRemaining = () => {
        if (ballsRemaining.value > ballsRemainingPrevious.value) {
            ballsRemaining.value = ballsRemainingPrevious.value;
        }
    }

    const countFoul = (amount) => {
        if (foulPoints.value + amount < 0) {
            foulPoints.value = 0;
            return;
        }
        foulPoints.value += amount;
    }

    const validateFoulPoints = () => {
        if (foulPoints.value < 0) {
            foulPoints.value = 0;
        }
    }

    const totalBalls = () => {
        return Array.from({length: 15}, (v, k) => k+1);
    };

    const submitScore = () => {
        // When only 1 ball is left, continue the run for this player
        // And set the remaining balls on table to 15 again
        if (ballsRemaining.value === 1) {
            ballsRemaining.value = maxBalls;
            ballsRemainingPrevious.value = maxBalls;
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
            onSuccess: () => {
                // Reset score/fouls/remaining balls
                currentScore.value = 0;
                foulPoints.value = 0;
                ballsRemainingPrevious.value = ballsRemaining.value;

                closeScoreModal();
            }
        });
    };

    const switchTurn = () => {
        game.value.active_player = game.value.active_player === game.value.player_id ? game.value.opponent_id : game.value.player_id;
    }

    const undoLastScore = () => {
        const form = useForm({
            game: game.value.id,
            score: game.value.scores.slice(-1)[0].id,
        });

        form.delete(route('game.undo-score'), {
            onSuccess: () => {
                undoModal.hide();
            }
        });
    }
</script>

<template>
    <Head title="Game" />

    <AuthenticatedLayout>
        <template #header>
            <div class="game-title">
                <h2>Game #{{ game.id }}</h2>
                <h6 class="text-gray-500">Race to: <b>{{ game.score_goal }}</b></h6>
                <h6 class="text-gray-500">Started on: <b>{{ game.start_date_formatted }}</b></h6>
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
            <div class="buttons">
                <button class="btn btn-outline-gray-500" @click="switchTurn()" v-if="Object.keys(players).length > 1"><i class="bi bi-arrow-left-right"></i> Switch player</button>
                <button class="btn btn-outline-gray-500" @click="openUndoModal()"><i class="bi bi-arrow-counterclockwise"></i> Undo last score</button>
            </div>

            <div class="balls">
                <div class="row-label">Balls on table</div>
                <div v-for="ball in totalBalls()" :key="ball" class="item" :class="{ current: ballsRemaining === ball, disabled: ballsRemaining < ball}">
                    <input type="radio" class="btn-check" :id="'ball_' + ball" v-model="ballsRemaining" :value="ball" :disabled="ballsRemaining < ball" @click="openScoreModal(ball)">
                    <label class="btn" :for="'ball_' + ball">{{ ball }}</label>
                </div>
            </div>
        </div>

        <div class="stats" v-if="scores && Object.keys(scores).length">
            <h3>Statistics</h3>
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

            <div class="stats-row">
                <div class="row-label">Innings</div>
                <div class="values">
                    <div class="list-group list-group-flush" v-for="score in scores" :key="score.id">
                        <div v-for="(inning, index) in score.innings" :key="inning.id" class="list-group-item">
                            <div class="index">#{{ index+1 }}</div>
                            <div>Points: {{ inning.points }}</div>
                        </div>
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
                                    <button class="btn btn-light" type="button" @click="countBallsRemaining(-1)">-</button>
                                    <input type="number" class="form-control" v-model="ballsRemaining" min="1" :max="ballsRemainingPrevious" @keyup="validateBallsRemaining">
                                    <button class="btn btn-primary" type="button" @click="countBallsRemaining(+1)">+</button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Foul points:</label>
                                <div class="input-group">
                                    <button class="btn btn-light" type="button" @click="countFoul(-1)">-</button>
                                    <input type="number" class="form-control" v-model="foulPoints" min="0" @keyup="validateFoulPoints">
                                    <button class="btn btn-primary" type="button" @click="countFoul(+1)">+</button>
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

        <div class="modal" id="undoModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Undo last score</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="score-form">
                            <p>Are you sure you want to delete the previous score?</p>
                            {{ game.scores.slice(-1) }}
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-primary ms-auto" @click="undoLastScore">Yes, undo this score</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <pre>{{ scores }}</pre>
    </AuthenticatedLayout>
</template>
