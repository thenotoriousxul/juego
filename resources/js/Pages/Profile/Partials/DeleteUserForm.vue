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
            <h2 class="text-lg font-medium text-amber-600">Eliminar Cuenta</h2>

            <p class="mt-1 text-sm text-gray-300">
                Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion" class="bg-red-900 hover:bg-red-800 focus:bg-red-800 active:bg-red-950">Eliminar Cuenta</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-amber-600">
                    ¿Estás seguro de que quieres eliminar tu cuenta?
                </h2>

                <p class="mt-1 text-sm text-gray-300">
                    Una vez que tu cuenta sea eliminada, todos sus recursos y datos serán eliminados permanentemente.
                    Por favor, escribe tu contraseña para confirmar que deseas eliminar permanentemente tu cuenta.
                </p>

                <div class="mt-6">
                    <TextInput
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
                        placeholder="Contraseña"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>

                    <DangerButton
                        class="ml-3 bg-red-900 hover:bg-red-800 focus:bg-red-800 active:bg-red-950"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Eliminar Cuenta
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>

<style>
</style>
