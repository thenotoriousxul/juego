<template>
  <Head title="Partida" />

  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black relative overflow-hidden">
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

    <div class="relative z-10">
      <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="flex justify-between items-center mb-8">
          <h1 class="text-3xl font-bold text-amber-600">Partida #{{ game.id }}</h1>
          <div class="flex items-center space-x-4">
            <span class="text-amber-600 text-xl">Turno: {{ game.current_turn }}</span>
            <span :class="{
              'px-4 py-2 rounded-md text-lg font-medium': true,
              'bg-green-900/50 text-green-300': game.status === 'active',
              'bg-amber-900/50 text-amber-300': game.status === 'pending',
              'bg-red-900/50 text-red-300': game.status === 'finished'
            }">
              {{ game.status }}
            </span>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
          <!-- Tablero del Jugador -->
          <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900">
            <h2 class="text-2xl font-bold text-amber-600 font-pirata mb-4">Tu Tablero</h2>
            <div class="grid grid-cols-10 gap-1">
              <div v-for="(cell, index) in game.player_board" :key="index"
                   class="aspect-square border border-amber-900/50 rounded-sm flex items-center justify-center"
                   :class="{
                     'bg-blue-900/50': cell === 'water',
                     'bg-amber-900/50': cell === 'ship',
                     'bg-red-900/50': cell === 'hit',
                     'bg-gray-700/50': cell === 'miss'
                   }">
              </div>
            </div>
          </div>

          <!-- Tablero del Oponente -->
          <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900">
            <h2 class="text-2xl font-bold text-amber-600 font-pirata mb-4">Tablero del Oponente</h2>
            <div class="grid grid-cols-10 gap-1">
              <div v-for="(cell, index) in game.opponent_board" :key="index"
                   class="aspect-square border border-amber-900/50 rounded-sm flex items-center justify-center cursor-pointer hover:bg-amber-900/20 transition-colors"
                   :class="{
                     'bg-blue-900/50': cell === 'water',
                     'bg-red-900/50': cell === 'hit',
                     'bg-gray-700/50': cell === 'miss'
                   }"
                   @click="makeMove(index)">
              </div>
            </div>
          </div>
        </div>

        <!-- Historial de Movimientos -->
        <div class="mt-8 bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900">
          <h2 class="text-2xl font-bold text-amber-600 font-pirata mb-4">Historial de Movimientos</h2>
          <div class="space-y-2">
            <div v-for="(move, index) in game.moves" :key="index"
                 class="text-gray-300 p-2 rounded-md"
                 :class="{
                   'bg-amber-900/30': move.player_id === auth.user.id,
                   'bg-gray-800/30': move.player_id !== auth.user.id
                 }">
              <span class="font-medium text-amber-600">{{ move.player_name }}</span>
              <span class="mx-2">disparó a</span>
              <span class="font-medium">{{ move.position }}</span>
              <span class="mx-2">y</span>
              <span :class="{
                'text-red-400': move.result === 'hit',
                'text-gray-400': move.result === 'miss'
              }">
                {{ move.result === 'hit' ? 'acertó' : 'falló' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';

defineProps({
  game: {
    type: Object,
    required: true
  },
  auth: {
    type: Object,
    required: true
  }
});

const makeMove = (position) => {
  // Implementar lógica de movimiento
};
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