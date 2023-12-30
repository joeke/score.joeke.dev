<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    type: 1,
    goal: 100,
});

const submit = () => {
    form.post(route('game/create'), {
        onFinish: () => form.reset('password')
    });
};

</script>

<template>
    <Head title="New game" />

    <AuthenticatedLayout>
        <template #header>
            <h1>New game</h1>
        </template>

        <form @submit.prevent="submit" class="w-100">
            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="type" value="Type" />
                    <select id="type" class="form-select form-select-lg" v-model="form.type">
                        <option value="1" selected>Straight / 14.1</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="goal" value="Race to" />
                    <TextInput id="goal" ref="goalInput" v-model="form.goal" type="number" class="form-control-lg" />
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="player_1" value="Player 1" />
                    <select id="player_1" class="form-select form-select-lg">
                        <option selected>Player 1</option>
                    </select>
                </div>

                <div class="col-12 col-md-6 mb-4">
                    <InputLabel for="player_2" value="Player 2" />
                    <select id="player_2" class="form-select form-select-lg">
                        <option>None (solo game)</option>
                        <option selected>Player 2</option>
                    </select>
                </div>
            </div>

            <PrimaryButton class="btn-lg w-100" :disabled="form.processing">Start game</PrimaryButton>
        </form>
    </AuthenticatedLayout>
</template>
