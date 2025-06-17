<script>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';


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
    status: String,
  },
  data() {
    return {
      form: useForm({
        email: '',
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(route('password.email'));
    },
  },
};
</script>

<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
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
      <h2 class="text-3xl font-bold text-center text-amber-600 mb-6">Recuperar Contraseña</h2>

      <div class="mb-4 text-sm text-amber-600">
        ¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para que puedas elegir una nueva.
      </div>

      <div v-if="status" class="mb-4 font-medium text-sm text-green-400">
        {{ status }}
      </div>

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

        <div class="flex items-center justify-end mt-4">
          <PrimaryButton class="bg-amber-900 hover:bg-amber-800 focus:bg-amber-800 active:bg-amber-950" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Enviar Enlace
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>

<style>
@keyframes glow {
  0% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
  50% { filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5)); }
  100% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
}

svg {
  animation: glow 4s infinite;
}
</style>
