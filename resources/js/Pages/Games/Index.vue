<template>
  <Head title="Partidas" />

  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black relative overflow-hidden">
    <!-- Barra de Navegación -->
    <nav class="bg-gray-900/90 backdrop-blur-sm border-b-2 border-amber-900">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <Link :href="route('dashboard')" class="text-2xl font-bold text-amber-600 font-pirata">
              Batalla Naval
            </Link>
          </div>
          
          <div class="flex items-center space-x-4">
            <Link
              :href="route('dashboard')"
              class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Dashboard
            </Link>
            <Link
              :href="route('games.index')"
              class="bg-amber-900 text-white hover:bg-amber-800 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Partidas
            </Link>
            <Link
              :href="route('stats')"
              class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Estadísticas
            </Link>
            <Link
              :href="route('profile.edit')"
              class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Perfil
            </Link>
            <Link
              :href="route('logout')"
              method="post"
              as="button"
              class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-medium transition-colors"
            >
              Cerrar Sesión
            </Link>
          </div>
        </div>
      </div>
    </nav>

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
      <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold text-amber-600">Mis Partidas</h1>
          <Link
            :href="route('games.create')"
            class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-3 rounded-md text-lg font-medium transition-colors duration-200"
          >
            Nueva Partida
          </Link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="game in games" :key="game.id" 
               class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900 hover:scale-[1.02] transition-all duration-250">
            <div class="flex justify-between items-start mb-4">
              <div>
                <h3 class="text-xl font-semibold text-amber-600 font-pirata">Partida #{{ game.id }}</h3>
                <p class="text-gray-300 text-sm mt-1">Estado: {{ game.status }}</p>
              </div>
              <span :class="{
                'px-3 py-1 rounded-full text-sm': true,
                'bg-green-900/50 text-green-300': game.status === 'active',
                'bg-amber-900/50 text-amber-300': game.status === 'pending',
                'bg-red-900/50 text-red-300': game.status === 'finished'
              }">
                {{ game.status }}
              </span>
            </div>

            <div class="space-y-2 text-gray-300">
              <p>Jugador 1: {{ game.player1?.name || 'Esperando...' }}</p>
              <p>Jugador 2: {{ game.player2?.name || 'Esperando...' }}</p>
              <p class="text-sm">Creada: {{ new Date(game.created_at).toLocaleDateString() }}</p>
            </div>

            <div class="mt-4 flex justify-end space-x-2">
              <Link
                v-if="game.status === 'pending'"
                :href="route('games.join', game.id)"
                class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              >
                Unirse
              </Link>
              <Link
                v-if="game.status === 'active'"
                :href="route('games.show', game.id)"
                class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200"
              >
                Continuar
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  games: {
    type: Array,
    required: true
  }
});
</script>

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