<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2>Delete Account</h2>

            <p class="mt-1 text-sm text-gray-500">
                Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting
                your account, please download any data or information that you wish to retain.
            </p>
        </header>

        <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">Delete Account</button>

        <div class="modal fade" id="deleteAccountModal"  tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Are you sure you want to delete your account?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Please
                        enter your password to confirm you would like to permanently delete your account.</p>

                        <div class="form-floating mb-3">
                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="form.password"
                                type="password"
                                placeholder="Password"
                                @keyup.enter="deleteUser"
                            />
                            <InputLabel for="password" value="Password" class="sr-only" />

                            <InputError :message="form.errors.password" class="mt-2" />
                        </div>

                        <div class="mt-4 d-flex">
                            <button class="btn btn-light" @click="closeModal"> Cancel </button>
                            <DangerButton class="ms-auto" :disabled="form.processing" @click="deleteUser">Delete Account</DangerButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
