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
        email: '',
        password: '',
        remember: false,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(route('login'), {
        onFinish: () => this.form.reset('password'),
      });
    },
  },
};
</script>

<template>
  <Head title="Iniciar Sesión" />

  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden">
    <!-- Elementos decorativos -->
    <div class="absolute inset-0 z-0">
      <!-- Barco pirata grande -->
      <div class="absolute bottom-0 right-0 w-[800px] h-[600px] opacity-30">
        <svg viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg">
          <!-- Casco del barco -->
          <path fill="#2C1810" d="M100,400 L700,400 L650,300 L150,300 Z"/>
          
          <!-- Cubierta -->
          <path fill="#1A0F0A" d="M150,300 L650,300 L600,250 L200,250 Z"/>
          
          <!-- Mástil principal -->
          <path fill="#8B4513" d="M400,250 L400,100 L420,100 L420,250 Z"/>
          
          <!-- Mástil delantero -->
          <path fill="#8B4513" d="M250,250 L250,150 L270,150 L270,250 Z"/>
          
          <!-- Mástil trasero -->
          <path fill="#8B4513" d="M550,250 L550,180 L570,180 L570,250 Z"/>
          
          <!-- Velas principales -->
          <path fill="#D4AF37" d="M420,100 L600,180 L420,250 Z"/>
          <path fill="#D4AF37" d="M400,100 L220,180 L400,250 Z"/>
          
          <!-- Velas delanteras -->
          <path fill="#D4AF37" d="M270,150 L400,200 L270,250 Z"/>
          
          <!-- Velas traseras -->
          <path fill="#D4AF37" d="M570,180 L700,220 L570,250 Z"/>
          
          <!-- Bandera pirata -->
          <path fill="#000" d="M400,100 L400,50 L450,50 L400,75 Z"/>
          <path fill="#FFD700" d="M400,75 L420,65 L420,85 Z"/>
          
          <!-- Ventanas -->
          <circle cx="300" cy="350" r="15" fill="#FFD700"/>
          <circle cx="400" cy="350" r="15" fill="#FFD700"/>
          <circle cx="500" cy="350" r="15" fill="#FFD700"/>
          
          <!-- Proa decorativa -->
          <path fill="#8B4513" d="M100,400 L100,350 L150,300 L150,400 Z"/>
          <path fill="#D4AF37" d="M100,350 L120,340 L150,300 L150,350 Z"/>
          
          <!-- Timón -->
          <path fill="#8B4513" d="M650,400 L650,350 L700,400 Z"/>
          <path fill="#D4AF37" d="M650,350 L670,340 L700,400 L650,400 Z"/>
        </svg>
      </div>
      
      <!-- Barcos pequeños decorativos -->
      <div class="absolute top-10 left-10 w-24 h-24 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
        </svg>
      </div>
      <div class="absolute top-20 right-20 w-24 h-24 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
        </svg>
      </div>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-gray-900/90 backdrop-blur-sm shadow-xl overflow-hidden sm:rounded-lg relative z-10 border-2 border-amber-900">
      <div class="flex justify-center mb-6">
        <ApplicationLogo class="text-amber-600" />
      </div>
      <h2 class="text-3xl font-bold text-center text-amber-600 mb-6 font-pirata">Batalla Naval</h2>

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
          <InputLabel for="password" value="Contraseña" class="text-amber-600" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full bg-gray-800 border-amber-900 text-amber-100 focus:border-amber-600 focus:ring-amber-600"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="block mt-4">
          <label class="flex items-center">
            <Checkbox name="remember" v-model:checked="form.remember" class="border-amber-900 bg-gray-800" />
            <span class="ml-2 text-sm text-amber-600">Recordarme</span>
          </label>
        </div>

        <div class="flex items-center justify-end mt-4">
          <Link
            :href="route('register')"
            class="underline text-sm text-amber-600 hover:text-amber-400 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500"
          >
            ¿No tienes cuenta? Regístrate
          </Link>

          <PrimaryButton class="ml-4 bg-amber-900 hover:bg-amber-800 focus:bg-amber-800 active:bg-amber-950" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Iniciar Sesión
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

/* Efecto de brillo en el barco */
@keyframes glow {
  0% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
  50% { filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5)); }
  100% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
}

svg {
  animation: glow 4s infinite;
}
</style>
