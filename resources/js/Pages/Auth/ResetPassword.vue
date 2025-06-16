<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

/*const props = defineProps({
    email: String,
    token: String,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
}; */

export default {
  components: {
    GuestLayout,
    InputError,
    InputLabel,
    PrimaryButton,
    TextInput,
    Head,
  },

  props: {
    email: String,
    token: String,
  },

  data() {
    return {
      form: useForm({
        token: this.token,
        email: this.email,
        password: '',
        password_confirmation: '',
      }),
    };
  },

  methods: {
    submit() {
      this.form.post(route('password.store'), {
        onFinish: () => {
          this.form.reset('password', 'password_confirmation');
        },
      });
    },
  },
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
    <!-- Elementos decorativos -->
    <div class="absolute inset-0 z-0">
      <!-- Barcos decorativos -->
      <div class="absolute top-20 left-20 w-32 h-32 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="absolute bottom-20 right-20 w-32 h-32 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-900/90 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-lg relative z-10 border-2 border-amber-900">
      <div class="flex justify-center mb-6">
        <ApplicationLogo class="text-amber-600" />
      </div>
      <h2 class="text-3xl font-bold text-center text-amber-600 mb-6 font-pirata">Restablecer Contraseña</h2>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="email" value="Correo Electrónico" class="text-amber-600" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Nueva Contraseña" class="text-amber-600" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
            v-model="form.password"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="mt-4">
          <InputLabel for="password_confirmation" value="Confirmar Contraseña" class="text-amber-600" />
          <TextInput
            id="password_confirmation"
            type="password"
            class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
            v-model="form.password_confirmation"
            required
            autocomplete="new-password"
          />
          <InputError class="mt-2" :message="form.errors.password_confirmation" />
        </div>

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton class="bg-amber-900 hover:bg-amber-800 focus:bg-amber-800 active:bg-amber-950" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Restablecer Contraseña
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>

<style>
/* Efecto de brillo en los barcos */
@keyframes glow {
  0% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
  50% { filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5)); }
  100% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
}

svg {
  animation: glow 4s infinite;
}
</style>
