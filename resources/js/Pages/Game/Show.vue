<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, usePage, useForm, useRemember } from '@inertiajs/vue3';
    import { computed, ref, nextTick, onMounted, watch } from 'vue';

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
    let currentScorePrevious = useRemember(currentScore.value);
    let ballsRemaining = ref(game.value.balls_remaining);
    let ballsRemainingPrevious = ref(ballsRemaining.value);
    let foulPoints = ref(0);
    let scoreModal = ref(null);
    let undoModal = ref(null);
    let resetBallsModal = ref(null);

    onMounted(() => {
        scoreModal = new Modal('#scoreModal', {backdrop: 'static', keyboard: false});
        undoModal = new Modal('#undoModal', {backdrop: 'static', keyboard: false});
        resetBallsModal = new Modal('#resetBallsModal', {backdrop: 'static', keyboard: false});

        setScreenWakeLock();

        console.log(players.value)
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
        return Array.from({length: maxBalls}, (v, k) => k+1);
    };

    const submitScore = () => {
        // When only 1 ball is left, continue the run for this player
        // And set the remaining balls on table to 15 again
        if (ballsRemaining.value === 1) {
            ballsRemaining.value = maxBalls;
            ballsRemainingPrevious.value = maxBalls;
            currentScorePrevious.value = currentScore.value;
            scoreModal.hide();
            return;
        }

        const form = useForm({
            game: game.value.id,
            points: currentScore.value,
            foul_points: foulPoints.value,
            player_id: game.value.active_player,
            balls_remaining: ballsRemaining.value,
        });

        form.post(route('game.score.store'), {
            onSuccess: () => {
                // Reset score/fouls/remaining balls
                currentScore.value = 0;
                currentScorePrevious.value = 0;
                foulPoints.value = 0;
                ballsRemainingPrevious.value = ballsRemaining.value;

                scoreModal.hide();
            }
        });
    };

    const cancelScore = () => {
        ballsRemaining.value = ballsRemainingPrevious.value;

        nextTick(() => {
            currentScore.value = currentScorePrevious.value;
        });

        scoreModal.hide();
    }

    const switchTurn = () => {
        game.value.active_player = game.value.active_player === game.value.player_id ? game.value.opponent_id : game.value.player_id;
    }

    const undoLastScore = () => {
        const form = useForm({
            id: game.value.scores.slice(-1)[0].id,
        });

        form.delete(route('game.score.delete'), {
            onSuccess: (response) => {
                if (response?.props?.game?.balls_remaining) {
                    ballsRemaining.value = response.props.game.balls_remaining;
                    ballsRemainingPrevious.value = response.props.game.balls_remaining;

                    nextTick(() => {
                        currentScore.value = 0;
                    });
                }
                undoModal.hide();
            }
        });
    }

    const lastScore = () => {
        return game.value.scores.slice(-1)[0];
    }

    const resetBallsOnTable = () => {
        currentScore.value = 0;
        currentScorePrevious.value = 0;
        foulPoints.value = 0;
        ballsRemaining.value = maxBalls;

        resetBallsModal.hide();
    }

    const currentFouls = () => {
        const lastScore = game.value.scores.slice(-1)[0];
        if ((!lastScore) || (lastScore && lastScore.foul_points === 0)) {
            return 0;
        }

        const secondLastScore = game.value.scores.slice(-2)[0];
        if ((!secondLastScore) || (secondLastScore && secondLastScore.foul_points === 0)) {
            return lastScore.foul_points;
        }

        const thirdLastScore = game.value.scores.slice(-3)[0];
        if ((!thirdLastScore) || (thirdLastScore && thirdLastScore.foul_points === 0)) {
            return lastScore.foul_points + secondLastScore.foul_points;
        }

        return lastScore.foul_points + secondLastScore.foul_points + thirdLastScore.foul_points;
    }

    const scoreGoalIsMet = () => {
        return Object.values(players.value).some(player => player.total_points >= game.value.score_goal);
    }

    const finishGame = () => {
        const form = useForm({
            id: game.value.id,
            is_finished: true,
            redirect: false
        });

        form.patch(route('game.update'));
    }

    const reOpenGame = () => {
        const form = useForm({
            id: game.value.id,
            is_finished: false,
            redirect: false
        });

        form.patch(route('game.update'));
    }

    const setScreenWakeLock = () => {
        if ('wakeLock' in navigator) {
            navigator.wakeLock.request('screen').then(() => {
                // Screen Wake Lock has been activated
                console.info('Screen Wake Lock has been activated');
            }).catch((err) => {
                console.error(`Error: ${err}`);
            });
        }
    }
</script>

<template>
    <Head :title="'Game #' + game.id" />

    <AuthenticatedLayout>
        <template #header>
            <div class="game-title">
                <span>{{ game.type.name }}</span>
                <span>Race to: <b>{{ game.score_goal }}</b></span>
                <span><b>{{ game.start_date_formatted }}</b></span>
            </div>
        </template>

        <div v-if="scoreGoalIsMet() && game.is_finished == false" class="game-finished-message">
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> The score goal for this game has been met (Race to {{ game.score_goal }}). Click "Finish game" to end the game.
            </div>

            <button class="btn btn-primary" @click="finishGame">
                <i class="bi bi-trophy"></i> Finish game
            </button>
        </div>

        <div v-if="game.is_finished" class="game-finished-message">
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill"></i> This game has been finished, no more scores can be added. If you want you can re-open the game.
            </div>

            <button class="btn btn-primary" @click="reOpenGame">
                <i class="bi bi-arrow-right-circle"></i> Re-open game
            </button>
        </div>

        <div class="game">
            <div class="player" :class="{active: game.active_player === player.id && !game.is_finished}" v-for="player in players" :key="player.id">
                <div class="name">{{ player.name }}</div>
                <div class="score">{{ player.total_points }}</div>
                <div class="current-run" v-if="!game.is_finished" :class="{'active' : game.active_player === player.id}">
                    <div>Current run: <span>{{ currentScore }}</span></div>
                </div>
                <div v-if="currentFouls() > 0" class="badge text-bg-warning">Fouls: <b>{{ currentFouls() }}</b></div>
            </div>
        </div>

        <div class="actions" v-if="!game.is_finished">
            <div class="buttons">
                <button class="btn btn-outline-gray-500" @click="switchTurn()" v-if="Object.keys(players).length > 1 && currentScore === 0">
                    <i class="bi bi-arrow-left-right"></i> Switch player
                </button>

                <button class="btn btn-outline-gray-500" @click="undoModal.show()" v-if="scores && Object.keys(scores).length && currentScore === 0">
                    <i class="bi bi bi-skip-backward-fill"></i> Undo last score
                </button>
            </div>

            <div class="balls">
                <div class="row-label">
                    <span>Balls on table</span>&nbsp;
                    <button class="btn btn-sm btn-outline-gray-600" @click="resetBallsModal.show()" v-if="Object.keys(players).length === 1 && scores && Object.keys(scores).length && currentScore === 0 && ballsRemaining !== maxBalls">
                        <i class="bi bi bi-arrow-counterclockwise"></i> Reset
                    </button>
                </div>
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
                <div class="row-label">Average run</div>
                <div class="values">
                    <div class="value" v-for="score in scores" :key="score.id">
                        {{ score.ppi }}
                    </div>
                </div>
            </div>

            <div class="stats-row">
                <div class="row-label">Innings</div>
                <div class="values">
                    <table class="table table-striped table-sm" v-for="score in scores" :key="score.id">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>P</th>
                                <th>F</th>
                                <th>T</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(inning, index) in score.innings" :key="inning.id">
                                <td class="index">{{ index+1 }}</td>
                                <td>{{ inning.points }}</td>
                                <td>{{ inning.foul_points }}</td>
                                <td>{{ inning.total_points }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal" id="scoreModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add score</h5>
                        <button type="button" class="btn-close" @click="cancelScore"></button>
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
                                <div>{{ currentScore }}<span v-if="foulPoints > 0"> - {{ foulPoints }}</span></div>
                            </div>

                            <div class="my-4 fw-bold fst-italic" v-if="ballsRemaining == 1">
                                Note: this will continue the run for {{ players[game.active_player]['name'] }}, and will reset the balls on table to {{ maxBalls }}.
                            </div>
                            <div class="my-4 fw-bold fst-italic" v-else>
                                Note: this will end the turn for {{ players[game.active_player]['name'] }}.
                            </div>
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-light" @click="cancelScore"> Cancel </button>
                            <button class="btn btn-primary ms-auto" @click="submitScore"><i class="bi bi-check-lg"></i> Confirm</button>
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
                        <p>Are you sure you want to delete the previous score?</p>

                        <div class="mb-3" v-if="lastScore()">
                            <div>Player: <b>{{ players[lastScore().player_id]['name'] }}</b></div>
                            <div>Points: <b>{{ lastScore().points }}</b></div>
                            <div>Foul points: <b>{{ lastScore().foul_points }}</b></div>
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-primary ms-auto" @click="undoLastScore">Yes, undo this score</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="resetBallsModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reset balls on table</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>This will reset the balls on table to {{ maxBalls }}</p>
                        <p>Warning! This will not count scores, only reset the balls on table so you can start a new run.</p>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-primary ms-auto" @click="resetBallsOnTable"><i class="bi bi-check-lg"></i> Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
