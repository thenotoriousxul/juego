<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

export default {
  components: {
    ApplicationLogo,
    Checkbox,
    GuestLayout,
    InputError,
    InputLabel,
    PrimaryButton,
    TextInput,
    Head,
    Link,
  },
  props: {
    canResetPassword: Boolean,
    status: String,
  },
  data() {
    return {
      form: useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(route('register'), {
        onFinish: () => this.form.reset('password', 'password_confirmation'),
      });
    },
  },
};
</script>

<template>
  <Head title="Registro" />

  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
      <div class="ship-container">
        <svg class="ship" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path class="ship-hull" fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path class="ship-deck" fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path class="ship-mast" fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path class="ship-flag" fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="ship-container ship-container-2">
        <svg class="ship ship-2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path class="ship-hull" fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path class="ship-deck" fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path class="ship-mast" fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path class="ship-flag" fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="ship-container ship-container-3">
        <svg class="ship ship-3" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path class="ship-hull" fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path class="ship-deck" fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path class="ship-mast" fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path class="ship-flag" fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-900/90 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-lg relative z-10 border-2 border-amber-900">
      <div class="flex justify-center mb-6">
        <ApplicationLogo class="text-amber-600" />
      </div>
      <h2 class="text-3xl font-bold text-center text-amber-600 mb-6">Unirse a la Tripulación</h2>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="name" value="Nombre" class="text-white" />
          <TextInput
            id="name"
            type="text"
            class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div class="mt-4">
          <InputLabel for="email" value="Correo Electrónico" class="text-white" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
            v-model="form.email"
            required
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Contraseña" class="text-white" />
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
          <InputLabel for="password_confirmation" value="Confirmar Contraseña" class="text-white" />
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
          <Link
            :href="route('login')"
            class="underline text-sm text-amber-600 hover:text-amber-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500"
          >
            ¿Ya eres miembro? Inicia sesión
          </Link>

          <PrimaryButton class="ml-4 bg-amber-900 hover:bg-amber-800 focus:bg-amber-800 active:bg-amber-950" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Unirse
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Pirata+One&display=swap');

.font-pirata {
  font-family: 'Pirata One', cursive;
}

/* Efecto de brillo en los barcos */
@keyframes glow {
  0% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
  50% { filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5)); }
  100% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
}

svg {
  animation: glow 4s infinite;
}

.ship-container {
  position: absolute;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.ship {
  position: absolute;
  width: 150px;
  height: 150px;
  animation: sail 20s linear infinite;
  filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.3));
}

.ship-hull {
  animation: float 3s ease-in-out infinite;
}

.ship-deck {
  animation: float 3s ease-in-out infinite 0.2s;
}

.ship-mast {
  animation: float 3s ease-in-out infinite 0.4s;
}

.ship-flag {
  animation: wave 2s ease-in-out infinite;
}

@keyframes sail {
  0% {
    transform: translateX(-150px) translateY(50vh) rotate(5deg);
  }
  50% {
    transform: translateX(calc(100vw + 150px)) translateY(40vh) rotate(-5deg);
  }
  100% {
    transform: translateX(-150px) translateY(50vh) rotate(5deg);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
}

@keyframes wave {
  0%, 100% {
    transform: rotate(0deg);
  }
  50% {
    transform: rotate(10deg);
  }
}
</style>
