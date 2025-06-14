<template>
  <div class="bg-white/5 backdrop-blur-sm rounded-lg overflow-hidden">
    <div v-if="games.length === 0" class="text-center py-8 text-gray-400">
      No hay partidas para mostrar.
    </div>
    <table v-else class="min-w-full">
      <thead>
        <tr>
          <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Fecha</th>
          <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Oponente</th>
          <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Movimientos</th>
          <th class="py-3 px-4 border-b border-white/10 text-center text-gray-300">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="game in games" :key="game.id" class="hover:bg-white/5 transition">
          <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ game.created_at }}</td>
          <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ game.opponent }}</td>
          <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ game.moves_count }}</td>
          <td class="py-3 px-4 border-b border-white/10 text-center">
            <button 
              @click="showGameDetails(game)"
              class="text-blue-400 hover:text-blue-300 transition"
              title="Ver detalles de la partida"
            >
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- Modal de detalles de la partida -->
  <div v-if="showModal && selectedGame" class="fixed inset-0 flex items-start justify-center z-50 p-4 overflow-y-auto">
    <div class="bg-gradient-to-b from-blue-900 to-blue-800 rounded-xl shadow-2xl w-full max-w-6xl mt-16 mb-8">
      <!-- Encabezado del Modal -->
      <div class="sticky top-0 bg-blue-900 border-b border-white/10 p-6">
        <div class="flex justify-between items-center">
          <div>
            <h3 class="text-2xl font-bold text-white">Detalles de la Partida</h3>
            <p class="text-blue-200 mt-1">Partida contra {{ selectedGame.opponent }} - {{ selectedGame.created_at }}</p>
          </div>
          <button @click="closeModal" class="text-blue-200 hover:text-white transition p-2 rounded-lg hover:bg-white/10">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <div class="p-6 space-y-6">
        <!-- Resumen de la Partida -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-white/5 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-blue-200">Total de Movimientos</p>
                <p class="text-2xl font-bold text-white mt-1">{{ selectedGame.moves_count }}</p>
              </div>
              <div class="text-4xl text-blue-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
              </div>
            </div>
          </div>
          <div class="bg-white/5 rounded-lg p-4">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-blue-200">Resultado</p>
                <p class="text-2xl font-bold mt-1" :class="type === 'won' ? 'text-green-400' : 'text-red-400'">
                  {{ type === 'won' ? 'Victoria' : 'Derrota' }}
                </p>
              </div>
              <div class="text-4xl" :class="type === 'won' ? 'text-green-400' : 'text-red-400'">
                <svg v-if="type === 'won'" xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Tableros -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="bg-white/5 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-white mb-4 text-center">Tu tablero</h4>
            <Board 
              :ships="selectedGame.my_board.ships" 
              :shots="selectedGame.my_board.shots"
              :is-own="true" 
            />
          </div>
          <div class="bg-white/5 rounded-lg p-6">
            <h4 class="text-lg font-semibold text-white mb-4 text-center">Tablero del oponente</h4>
            <Board 
              :ships="selectedGame.opponent_board.ships" 
              :shots="selectedGame.opponent_board.shots"
              :is-own="false" 
            />
          </div>
        </div>

        <!-- Historial de Movimientos -->
        <div class="bg-white/5 rounded-lg p-6">
          <h4 class="text-lg font-semibold text-white mb-4">Historial de Movimientos</h4>
          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead>
                <tr class="border-b border-white/10">
                  <th class="py-3 px-4 text-left text-blue-200 font-medium">Hora</th>
                  <th class="py-3 px-4 text-left text-blue-200 font-medium">Jugador</th>
                  <th class="py-3 px-4 text-left text-blue-200 font-medium">Disparó a</th>
                  <th class="py-3 px-4 text-left text-blue-200 font-medium">Resultado</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-white/10">
                <tr v-for="move in selectedGame.moves" :key="move.id" class="hover:bg-white/5 transition">
                  <td class="py-3 px-4 text-white">{{ move.created_at }}</td>
                  <td class="py-3 px-4 text-white">{{ move.shooter }}</td>
                  <td class="py-3 px-4 text-white">{{ move.position }}</td>
                  <td class="py-3 px-4">
                    <span :class="[
                      move.hit ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400',
                      'px-3 py-1 rounded-full text-sm font-medium'
                    ]">
                      {{ move.hit ? '¡Impacto!' : 'Falló' }}
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Board from './Board.vue';

export default {
  name: 'StatsTable',
  components: {
    Board
  },
  props: {
    games: {
      type: Array,
      required: true
    },
    type: {
      type: String,
      required: true,
      validator: value => ['won', 'lost'].includes(value)
    }
  },
  data() {
    return {
      showModal: false,
      selectedGame: null
    };
  },
  methods: {
    showGameDetails(game) {
      this.selectedGame = game;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedGame = null;
    }
  }
};
</script> 