<template>
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
        <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900">
          <h1 class="text-3xl font-bold text-amber-600 mb-6 text-center">Historial de Partidas</h1>

          <div class="mb-6">
            <div class="flex justify-between items-center mb-4">
              <div class="flex gap-4">
                <button 
                  @click="filter = 'all'"
                  :class="[
                    'px-4 py-2 rounded transition',
                    filter === 'all' 
                      ? 'bg-amber-900 text-white' 
                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                  ]"
                >
                  Todas
                </button>
                <button 
                  @click="filter = 'won'"
                  :class="[
                    'px-4 py-2 rounded transition',
                    filter === 'won' 
                      ? 'bg-amber-900 text-white' 
                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                  ]"
                >
                  Victorias
                </button>
                <button 
                  @click="filter = 'lost'"
                  :class="[
                    'px-4 py-2 rounded transition',
                    filter === 'lost' 
                      ? 'bg-amber-900 text-white' 
                      : 'bg-gray-800 text-gray-300 hover:bg-gray-700'
                  ]"
                >
                  Derrotas
                </button>
              </div>
              <div class="text-amber-600 text-xl">
                Total: {{ filteredGames.length }}
              </div>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="text-left border-b-2 border-amber-900">
                  <th class="pb-3 text-amber-600 font-pirata">ID</th>
                  <th class="pb-3 text-amber-600 font-pirata">Fecha</th>
                  <th class="pb-3 text-amber-600 font-pirata">Oponente</th>
                  <th class="pb-3 text-amber-600 font-pirata">Resultado</th>
                  <th class="pb-3 text-amber-600 font-pirata">Duración</th>
                  <th class="pb-3 text-amber-600 font-pirata">Acciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="game in filteredGames" :key="game.id" class="border-b border-gray-800">
                  <td class="py-4 text-gray-300">#{{ game.id }}</td>
                  <td class="py-4 text-gray-300">{{ formatDate(game.created_at) }}</td>
                  <td class="py-4 text-gray-300">{{ getOpponentName(game) }}</td>
                  <td class="py-4">
                    <span :class="[
                      'px-3 py-1 rounded-full text-sm',
                      game.winner_id === $page.props.auth.user.id 
                        ? 'bg-green-900/50 text-green-300' 
                        : 'bg-red-900/50 text-red-300'
                    ]">
                      {{ game.winner_id === $page.props.auth.user.id ? 'Victoria' : 'Derrota' }}
                    </span>
                  </td>
                  <td class="py-4 text-gray-300">{{ formatDuration(game) }}</td>
                  <td class="py-4">
                    <button 
                      @click="viewGame(game.id)"
                      class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded transition"
                    >
                      Ver detalles
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="filteredGames.length === 0" class="text-center py-8">
            <p class="text-gray-400 text-xl">No hay partidas que mostrar</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';

export default {
  name: 'GameHistoryView',
  props: {
    games: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      filter: 'all'
    };
  },
  computed: {
    filteredGames() {
      if (this.filter === 'all') return this.games;
      return this.games.filter(game => {
        const isWinner = game.winner_id === this.$page.props.auth.user.id;
        return this.filter === 'won' ? isWinner : !isWinner;
      });
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    formatDuration(game) {
      if (!game.finished_at) return 'En curso';
      const start = new Date(game.created_at);
      const end = new Date(game.finished_at);
      const minutes = Math.floor((end - start) / 1000 / 60);
      return `${minutes} min`;
    },
    getOpponentName(game) {
      const opponent = game.boards.find(b => b.user_id !== this.$page.props.auth.user.id);
      return opponent?.user?.name || 'Desconocido';
    },
    viewGame(id) {
      Inertia.visit(`/games/${id}`);
    }
  }
};
</script>

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