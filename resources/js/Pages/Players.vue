<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, usePage, useForm, router } from '@inertiajs/vue3';
    import InputError from '@/Components/InputError.vue';
    import { ref, computed, onMounted } from 'vue';

    let isCreate = true;
    let editModal = ref(null);
    let deleteModal = ref(null);

    onMounted(() => {
        editModal = new Modal('#editModal', {backdrop: 'static', keyboard: false});
        deleteModal = new Modal('#deleteModal', {backdrop: 'static', keyboard: false});
    });

    const players = computed(() => usePage().props.players);

    const user = usePage().props.auth.user;

    const form = useForm({
        id: null,
        name: '',
    });

    const openEditModal = (player) => {
        form.reset();
        isCreate = true;

        if (player) {
            isCreate = false;
            form.id = player.id;
            form.name = player.name;
        }

        editModal.show();
    }

    const openDeleteModal = (player) => {
        form.reset();

        if (player) {
            form.id = player.id;
            form.name = player.name;
        }

        deleteModal.show();
    }

    const save = () => {
        const method = isCreate ? 'post' : 'patch';
        const endpoint = isCreate ? 'player.store' : 'player.update';

        form.submit(method, route(endpoint), {
            onSuccess: () => editModal.hide()
        });
    };

    const deletePlayer = () => {
        form.delete(route('player.delete'), {
            onSuccess: () => {
                router.visit(route('players'));
                deleteModal.hide();
            }
        });
    };
</script>

<template>
    <Head title="Players" />

    <AuthenticatedLayout>
        <template #header>
            <h1>Players</h1>
        </template>

        <button class="btn btn-primary" @click="openEditModal(null)"><i class="bi bi-plus-lg"></i> New player</button>

        <div class="mt-4">
            <h2>Players</h2>

            <div class="table-list">
                <div class="table-list-row table-list-header">
                    <div>ID</div>
                    <div>Name</div>
                    <div></div>
                </div>
                <div class="table-list-row" v-for="player in players" :key="player.id">
                    <div>#{{ player.id }}</div>
                    <div>{{ player.name }}</div>
                    <div class="actions" v-if="user.id !== player.id">
                        <button class="btn btn-sm btn-outline-gray-500" @click="openEditModal(player)"><i class="bi bi-pencil"></i> Edit</button>
                        <button class="btn btn-sm btn-danger" @click="openDeleteModal(player)"><i class="bi bi-trash-fill"></i> Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="editModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-if="isCreate">Add player</h5>
                        <h5 class="modal-title" v-else>Edit player #{{ form.id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" v-model="form.name" placeholder="Player name" @keyup.enter="save" />
                            <label class="form-label sr-only" for="name">Player name</label>

                            <InputError :message="form.errors.name" class="mt-2" />
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-primary ms-auto" :disabled="form.processing" @click="save">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="deleteModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete player #{{ form.id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this player?</p>
                        <p><b>{{ form.name }}</b></p>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button class="btn btn-danger ms-auto" @click="deletePlayer"><i class="bi bi-check-lg"></i> Yes, delete this player</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
