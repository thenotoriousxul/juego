<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800">
    <MainMenu :auth="$page.props.auth" />
    
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-xl p-6">
          <h1 class="text-3xl font-bold text-white mb-6 text-center">Historial de la Partida #{{ game.id }}</h1>
          
          <div class="mb-6 text-center">
            <span class="text-white font-semibold mr-2">Estado:</span>
            <span :class="[statusClass(game.status), 'px-3 py-1 rounded-full text-sm']">{{ statusText(game.status) }}</span>
            <span v-if="game.status === 'finished' && game.winner_id" class="ml-4 text-green-300 font-semibold">
              Ganador: {{ winnerName }}
            </span>
          </div>

          <div class="bg-white/5 backdrop-blur-sm rounded-lg overflow-hidden">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Hora</th>
                  <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Jugador</th>
                  <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Disparó a</th>
                  <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Resultado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="move in moves" :key="move.id" class="hover:bg-white/5 transition">
                  <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ move.created_at }}</td>
                  <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ move.shooter }}</td>
                  <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ move.position }}</td>
                  <td class="py-3 px-4 border-b border-white/10">
                    <span :class="[move.hit ? 'text-green-400' : 'text-red-400', 'font-semibold']">
                      {{ move.hit ? '¡Impacto!' : 'Falló' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-8 text-center">
            <button @click="goBack" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
              Volver a estadísticas
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import MainMenu from '@/Components/MainMenu.vue';

export default {
  name: 'GameHistoryView',
  components: {
    MainMenu
  },
  props: {
    game: {
      type: Object,
      required: true
    },
    moves: {
      type: Array,
      required: true
    }
  },
  computed: {
    winnerName() {
      const winner = this.game.boards.find(b => b.user_id === this.game.winner_id);
      return winner && winner.user ? winner.user.name : 'Desconocido';
    }
  },
  methods: {
    goBack() {
      Inertia.visit('/stats');
    },
    statusText(status) {
      switch (status) {
        case 'waiting': return 'Esperando jugadores';
        case 'playing': return 'En juego';
        case 'finished': return 'Finalizado';
        default: return status;
      }
    },
    statusClass(status) {
      switch (status) {
        case 'waiting': return 'bg-yellow-500/20 text-yellow-300';
        case 'playing': return 'bg-blue-500/20 text-blue-300';
        case 'finished': return 'bg-green-500/20 text-green-300';
        default: return '';
      }
    }
  }
};
</script> 