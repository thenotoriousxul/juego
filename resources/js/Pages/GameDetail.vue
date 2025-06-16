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
      <div class="max-w-5xl mx-auto p-6 lg:p-8">
        <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900">
          <h1 class="text-3xl font-bold text-amber-600 font-pirata mb-6 text-center">Partida #{{ game.id }}</h1>
          
          <div v-if="message" class="mb-4 p-3 rounded bg-green-900/50 text-green-300 border border-green-900">{{ message }}</div>
          <div v-if="error" class="mb-4 p-3 rounded bg-red-900/50 text-red-300 border border-red-900">{{ error }}</div>
          <div v-if="$page.props.flash.game_started" class="mb-4 p-3 rounded bg-amber-900/50 text-amber-300 border border-amber-900">
            {{ $page.props.flash.game_started }}
          </div>

          <div class="mb-6 text-center">
            <span class="text-amber-600 font-pirata text-xl mr-2">Estado del galeón:</span>
            <span :class="[statusClass(game.status), 'px-3 py-1 rounded-full text-sm font-pirata']">{{ statusText(game.status) }}</span>
            <span v-if="game.status === 'finished' && game.winner_id" class="ml-4 text-green-300 font-pirata text-xl">
              Capitán victorioso: {{ winnerName }}
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-4 border-2 border-amber-900">
              <h2 class="font-pirata text-amber-600 text-xl mb-4 text-center">Tu flota</h2>
              <Board :ships="myBoard.ships" :shots="myBoard.shots" :is-own="true" />
            </div>
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-4 border-2 border-amber-900">
              <h2 class="font-pirata text-amber-600 text-xl mb-4 text-center">Flota rival</h2>
              <Board :ships="rivalBoard.ships" :shots="rivalBoard.shots" :is-own="false" />
            </div>
          </div>

          <div v-if="game.status === 'playing'" class="text-center">
            <div v-if="isMyTurn" class="mb-4">
              <label class="block text-amber-600 font-pirata text-xl mb-2">¡Tu turno de atacar!</label>
              <div class="flex justify-center items-center space-x-2">
                <input 
                  v-model="shot" 
                  type="text" 
                  placeholder="Ej: A3" 
                  class="bg-gray-800 text-amber-100 border-2 border-amber-900 rounded px-4 py-2 focus:outline-none focus:border-amber-600"
                  :disabled="loading"
                />
                <button 
                  @click="shoot" 
                  :disabled="loading || !shot"
                  class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-2 rounded transition disabled:opacity-50 disabled:cursor-not-allowed font-pirata"
                >
                  ¡Disparar cañón!
                </button>
              </div>
              <div v-if="shotResult !== null" class="mt-2 text-amber-400 font-pirata">
                {{ shotResult ? '¡Impacto directo!' : '¡Agua!' }}
              </div>
            </div>
            <div v-else class="text-amber-600 font-pirata text-xl">Esperando el ataque del rival...</div>
          </div>

          <div v-if="game.status === 'playing'" class="mt-6 text-center">
            <button 
              @click="showSurrenderModal = true"
              class="bg-red-900 hover:bg-red-800 text-white px-6 py-2 rounded transition font-pirata"
            >
              ¡Rendir bandera!
            </button>
          </div>
          <div v-else-if="game.status === 'waiting'" class="text-amber-600 font-pirata text-xl">Esperando a que se una un rival...</div>
          <div v-else-if="game.status === 'finished'" class="text-green-300 font-pirata text-xl">La batalla ha terminado.</div>

          <div class="mt-8 text-center">
            <button 
              v-if="game.status === 'finished'"
              @click="goBack" 
              class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-2 rounded transition font-pirata"
            >
              Volver al puerto
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de rendición -->
    <div v-if="showSurrenderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-gray-900/90 backdrop-blur-sm rounded-lg p-6 max-w-md w-full border-2 border-amber-900">
        <h3 class="text-xl font-bold text-amber-600 font-pirata mb-4">¿Estás seguro de que quieres rendirte?</h3>
        <p class="text-gray-300 mb-6">Esta acción no se puede deshacer y perderás la partida.</p>
        <div class="flex justify-end gap-3">
          <button 
            @click="showSurrenderModal = false"
            class="bg-gray-700 text-white px-4 py-2 rounded hover:bg-gray-600 transition"
          >
            Cancelar
          </button>
          <button 
            @click="confirmSurrender"
            class="bg-red-900 text-white px-4 py-2 rounded hover:bg-red-800 transition"
          >
            Sí, rendirme
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import Board from '../Components/Board.vue';
import MainMenu from '@/Components/MainMenu.vue';

export default {
  name: 'GameDetail',
  components: { 
    Board,
    MainMenu
  },
  props: {
    game: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      shot: '',
      shotResult: null,
      loading: false,
      pollingInterval: null,
      error: '',
      message: '',
      showSurrenderModal: false,
    };
  },
  mounted() {
    this.startPolling();
  },
  beforeUnmount() {
    this.stopPolling();
  },
  watch: {
    'game.status'(newStatus) {
      if (newStatus === 'finished') {
        this.stopPolling();
      } else {
        this.startPolling();
      }
    }
  },
  computed: {
    myId() {
      return this.$page.props.auth.user.id;
    },
    myBoard() {
      return this.game.boards.find(b => b.user_id === this.myId) || { ships: [], shots: [] };
    },
    rivalBoard() {
      return this.game.boards.find(b => b.user_id !== this.myId) || { ships: [], shots: [] };
    },
    isMyTurn() {
      if (this.game.status !== 'playing') return false;
      const moves = this.game.moves || [];
      const turnIndex = moves.length % 2;
      const myIndex = this.game.boards.findIndex(b => b.user_id === this.myId);
      return turnIndex === myIndex;
    },
    winnerName() {
      const winner = this.game.boards.find(b => b.user_id === this.game.winner_id);
      return winner && winner.user ? winner.user.name : 'Desconocido';
    },
  },
  methods: {
    startPolling() {
      this.pollingInterval = setInterval(() => {
        if (this.game.status === 'playing') {
          axios.get(`/games/${this.game.id}`)
            .then(response => {
              // Actualizar solo los datos necesarios sin recargar la página
              this.game.boards = response.data.game.boards;
              this.game.status = response.data.game.status;
              this.game.winner_id = response.data.game.winner_id;
              this.game.moves = response.data.game.moves;
            })
            .catch(error => {
              console.error('Error al actualizar el estado del juego:', error);
            });
        }
      }, 5000);
    },
    stopPolling() {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
      }
    },
    shoot() {
      if (!this.shot.match(/^[A-H][1-8]$/i)) {
        this.error = 'Posición inválida. Usa formato como A3.';
        this.message = '';
        return;
      }
      this.loading = true;
      this.error = '';
      this.message = '';
      axios.post(`/games/${this.game.id}/move`, { position: this.shot.toUpperCase() })
        .then(res => {
          this.shotResult = res.data.hit;
          this.message = res.data.hit ? '¡Le diste a un barco!' : 'Fallaste.';
          this.error = '';
          Inertia.reload({ only: ['game'] });
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al disparar';
          this.message = '';
        })
        .finally(() => {
          this.loading = false;
          this.shot = '';
        });
    },
    confirmSurrender() {
      this.loading = true;
      this.error = '';
      this.message = '';
      axios.post(`/games/${this.game.id}/surrender`)
        .then(() => {
          this.showSurrenderModal = false;
          Inertia.reload({ only: ['game'] });
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al rendirse';
        })
        .finally(() => {
          this.loading = false;
        });
    },
    goBack() {
      Inertia.visit('/games');
    },
    statusText(status) {
      switch (status) {
        case 'waiting': return 'Esperando tripulación';
        case 'playing': return '¡En batalla!';
        case 'finished': return 'Batalla finalizada';
        default: return status;
      }
    },
    statusClass(status) {
      switch (status) {
        case 'waiting': return 'bg-amber-900/50 text-amber-300';
        case 'playing': return 'bg-green-900/50 text-green-300';
        case 'finished': return 'bg-red-900/50 text-red-300';
        default: return '';
      }
    },
  },
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