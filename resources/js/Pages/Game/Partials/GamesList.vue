<script setup>
    import { useForm, Link, router } from '@inertiajs/vue3';
    import { defineProps, onMounted, ref } from 'vue';

    const props = defineProps({
        games: {
            type: Array,
            default: () => [],
        },
        showMoreButton: {
            type: Boolean,
            default: false,
        },
        showRowEditButton: {
            type: Boolean,
            default: false,
        },
        showRowDeleteButton: {
            type: Boolean,
            default: false,
        }
    });

    let selectedGame = ref(null);
    let deleteModal = ref(null);

    onMounted(() => {
        deleteModal = new Modal('#deleteModal');
    })

    const showDeleteModal = (game) => {
        selectedGame.value = game;
        deleteModal.show();
    }

    const deleteGame = () => {
        const form = useForm({
            id: selectedGame.value.id,
        });

        form.delete(route('game.delete'), {
            onSuccess: () => {
                router.visit(route('games'));
                deleteModal.hide();
            }
        });
    }
</script>

<template>
    <section>
        <div class="table-list" v-if="games.length > 0">
            <div class="table-list-row table-list-header">
                <div>Date</div>
                <div>Type</div>
                <div>Race to</div>
                <div>Player(s)</div>
                <div></div>
            </div>

            <div class="table-list-row" aria-current="true" v-for="game in games" :key="game.id">
                <div><div class="label">Date:</div> {{ game.start_date_formatted }}</div>
                <div><div class="label">Type:</div>{{ game.type.name }}</div>
                <div><div class="label">Race to:</div>{{ game.score_goal }}</div>
                <div><div class="label">Player(s):</div>{{ game.player.name }}<span v-if="game.opponent.name"> vs {{ game.opponent.name }}</span></div>
                <div class="actions">
                    <Link :href="route('game.show', game.id)" class="btn btn-sm btn-outline-primary"><i class="bi bi-search"></i> View</Link>
                    <Link v-if="showRowEditButton" :href="route('game.edit', game.id)" class="btn btn-sm btn-outline-gray-500"><i class="bi bi-pencil"></i> Edit</Link>
                    <button v-if="showRowDeleteButton" @click="showDeleteModal(game)" class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i> Delete</button>
                </div>
            </div>

            <Link :href="route('games')" class="btn btn-gray-600 mt-3" v-if="showMoreButton">View all games</Link>
        </div>

        <div v-else class="py-2">
            <em>No games found. <a :href="route('game.new')">Create a new game</a></em>
        </div>

        <div class="modal" id="deleteModal"  tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Delete game #{{ selectedGame?.id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this game?</p>
                        <p><b>{{ selectedGame?.start_date_formatted }} - {{ selectedGame?.player.name }}<span v-if="selectedGame?.opponent.name"> vs {{ selectedGame?.opponent.name }}</span></b></p>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-danger ms-auto" @click="deleteGame"><i class="bi bi-check-lg"></i> Yes, delete this game</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>