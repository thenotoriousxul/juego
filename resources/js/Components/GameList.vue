<template>
  <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900 relative overflow-hidden">
    <!-- Elementos decorativos piratas -->
    <div class="absolute top-0 right-0 w-24 h-24 opacity-20">
      <svg viewBox="0 0 24 24" fill="currentColor" class="text-amber-600">
        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
      </svg>
    </div>
    
    <h2 class="text-3xl font-bold text-amber-600 mb-6 text-center">
      Taberna de los Corsarios
    </h2>
    
    <div class="space-y-4">
      <div v-for="game in games" :key="game.id" 
           class="bg-gray-800/50 rounded-lg p-4 border border-amber-900/50 hover:bg-gray-800/70 transition-colors relative">
        <div class="absolute top-2 right-2 text-amber-600/30">
          <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
          </svg>
        </div>
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-lg font-semibold text-amber-600">Galeón #{{ game.id }}</h3>
            <p class="text-gray-300 text-sm">Capitán: {{ game.creator.name }}</p>
          </div>
          <div class="flex items-center space-x-2">
            <span :class="{
              'px-3 py-1 rounded-full text-sm': true,
              'bg-green-900/50 text-green-300': game.status === 'active',
              'bg-amber-900/50 text-amber-300': game.status === 'pending',
              'bg-red-900/50 text-red-300': game.status === 'finished'
            }">
              {{ game.status === 'active' ? '¡Al abordaje!' : 
                 game.status === 'pending' ? 'En puerto' : 
                 'Botín tomado' }}
            </span>
            <button
              v-if="game.status === 'pending'"
              @click="$emit('join', game.id)"
              class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-md text-sm transition-colors duration-200 flex items-center space-x-1"
            >
              Unirse
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  games: {
    type: Array,
    required: true
  }
});

defineEmits(['join']);
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Pirata+One&display=swap');

.font-pirata {
  font-family: 'Pirata One', cursive;
}

/* Efecto de papel antiguo */
.bg-gray-900\/50 {
  background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%23d4af37' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
}
</style> 