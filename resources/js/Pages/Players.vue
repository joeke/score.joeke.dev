<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import { ref, computed, onMounted } from 'vue';

let isCreate = ref(true);
let modal = ref(null);

onMounted(() => {
    modal = new Modal('#playerModal', {backdrop: 'static', keyboard: false});
});

const openModal = (id) => {
    form.reset();
    isCreate.value = true;

    if (id) {
        isCreate.value = false;
        let player = players.value.find(player => player.id === id);
        if (player) {
            form.id = player.id;
            form.name = player.name;
        }
    }

    modal.show();
}

const closeModal = () => {
    modal.hide();
}

const players = computed(() => usePage().props.players);

const form = useForm({
    id: null,
    name: '',
});

const save = () => {
    if (isCreate.value) {
        form.post(route('player.store'), {
            onSuccess: () => closeModal()
        });
    } else {
        form.patch(route('player.update'), {
            onSuccess: () => closeModal()
        });
    }

};
</script>

<template>
    <Head title="Players" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Players</h1>
        </template>

        <button class="btn btn-primary" @click="openModal(null)"><i class="bi bi-plus-lg"></i> New player</button>

        <div class="mt-4">
            <h2>Players</h2>

            <div class="table-list">
                <div class="table-list-row table-list-header">
                    <div>ID</div>
                    <div>Name</div>
                    <div>Actions</div>
                </div>
                <div class="table-list-row" v-for="player in players" :key="player.id">
                    <div>#{{ player.id }}</div>
                    <div>{{ player.name }}</div>
                    <div><button class="btn btn-gray-500 btn-sm" @click="openModal(player.id)"><i class="bi bi-pencil"></i> Edit</button></div>
                </div>
            </div>
        </div>

        <div class="modal" id="playerModal"  tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="isCreate">Add player</h5>
                        <h5 class="modal-title" v-else>Edit player</h5>
                        <button type="button" class="btn-close" @click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" v-model="form.name" placeholder="Player name" @keyup.enter="save" />
                            <label class="form-label sr-only" for="name">Player name</label>

                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-light" @click="closeModal"> Cancel </button>
                            <button class="btn btn-primary ms-auto" :disabled="form.processing" @click="save">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
