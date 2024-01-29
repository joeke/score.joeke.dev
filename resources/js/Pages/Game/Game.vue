<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

const players = usePage().props.players;
const gameTypes = usePage().props.gameTypes;
const game = usePage().props.game;

const isCreate = game ? false : true;

const title = isCreate ? 'New game' : 'Edit game';
const buttonTitle = isCreate ? 'Start game' : 'Save game';

const form = game || useForm({
    type_id: gameTypes[0]?.id || 0,
    score_goal: 100,
    player_id: usePage().props.auth.user.id || 0,
    opponent_id: 0,
});

const submit = () => {
    const endpoint = isCreate ? 'game.store' : 'game.update';
    form.post(route(endpoint), {
        onFinish: () => form.reset()
    });
};

</script>

<template>
    <Head :title="title" />

    <AuthenticatedLayout>
        <template #header>
            <h1>{{ title }}</h1>
        </template>

        <form @submit.prevent="submit" class="w-100">
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="type" value="Type" />
                    <select id="type" class="form-select form-select-lg" v-model="form.type_id">
                        <option v-for="gameType in gameTypes" :key="gameType.id" :value="gameType.id">{{ gameType.name }}</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="goal" value="Race to" />
                    <TextInput id="goal" ref="goalInput" v-model="form.score_goal" type="number" class="form-control-lg" />
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="player_1" value="Player 1" />
                    <select id="player_1" class="form-select form-select-lg" v-model="form.player_id">
                        <option disabled selected>Select player 1</option>
                        <option v-for="player in players" :key="player.id" :value="player.id">{{ player.name }}</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="player_2" value="Player 2" />
                    <select id="player_2" class="form-select form-select-lg" v-model="form.opponent_id">
                        <option disabled selected>Select player 2</option>
                        <option value="0">None (solo game)</option>
                        <option v-for="player in players" :key="player.id" :value="player.id">{{ player.name }}</option>
                    </select>
                </div>
            </div>

            <PrimaryButton class="btn-lg w-100" :disabled="form.processing">{{ buttonTitle }}</PrimaryButton>
        </form>
    </AuthenticatedLayout>
</template>
