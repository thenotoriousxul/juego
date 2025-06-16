<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
});
</script>

<template>
  <Head title="Batalla Naval" />

  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black relative overflow-hidden">
    <!-- Elementos decorativos -->
    <div class="absolute inset-0 z-0">
      <!-- Barcos decorativos -->
      <div class="absolute bottom-20 right-20 w-32 h-32 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="absolute bottom-10 right-40 w-28 h-28 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="absolute bottom-30 right-0 w-24 h-24 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <path fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
    </div>

    <div class="relative z-10">
      <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
        <Link v-if="canLogin" :href="route('login')" class="font-semibold text-amber-600 hover:text-amber-400 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500 font-pirata">Iniciar Sesión</Link>

        <Link v-if="canRegister" :href="route('register')" class="ml-4 font-semibold text-amber-600 hover:text-amber-400 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500 font-pirata">Registrarse</Link>
      </div>

      <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-center">
          <ApplicationLogo class="w-20 h-20 text-amber-600" />
        </div>

        <div class="mt-8 bg-gray-900/50 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-lg border-2 border-amber-900">
          <div class="p-6 text-center">
            <h1 class="text-4xl font-bold text-amber-600 mb-4 font-pirata">¡Bienvenido a Batalla Naval!</h1>
            <p class="text-gray-300 text-lg mb-6">El juego de estrategia naval más emocionante del Caribe</p>
            
            <div class="flex justify-center space-x-4">
              <Link v-if="canLogin" :href="route('login')" class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-3 rounded-lg text-lg font-medium transition-colors duration-200 font-pirata">
                Iniciar Sesión
              </Link>
              <Link v-if="canRegister" :href="route('register')" class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-3 rounded-lg text-lg font-medium transition-colors duration-200 font-pirata">
                Registrarse
              </Link>
            </div>
          </div>
        </div>
      </div>
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
</style>
