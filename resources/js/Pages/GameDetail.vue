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
          
          <div v-if="message" class="mb-6 p-6 rounded-lg bg-green-900/80 text-green-300 border-2 border-green-600 text-center text-2xl font-bold shadow-lg shadow-green-900/50 animate-bounce">
            {{ message }}
          </div>
          <div v-if="error" class="mb-6 p-6 rounded-lg bg-red-900/80 text-red-300 border-2 border-red-600 text-center text-2xl font-bold shadow-lg shadow-red-900/50 animate-bounce">
            {{ error }}
          </div>
          <div v-if="$page.props.flash.game_started" class="mb-6 p-6 rounded-lg bg-amber-900/80 text-amber-300 border-2 border-amber-600 text-center text-2xl font-bold shadow-lg shadow-amber-900/50 animate-bounce">
            {{ $page.props.flash.game_started }}
          </div>

          <div v-if="game.status === 'finished' && game.winner_id" 
               class="mb-8 p-8 rounded-xl text-center animate-bounce"
               :class="game.winner_id === myId ? 'bg-green-900/90 border-4 border-green-500' : 'bg-red-900/90 border-4 border-red-500'">
            <h2 class="text-4xl font-bold mb-4" :class="game.winner_id === myId ? 'text-green-300' : 'text-red-300'">
              {{ game.winner_id === myId ? '¡Victoria! 🏆' : '¡Derrota! 💀' }}
            </h2>
            <p class="text-2xl" :class="game.winner_id === myId ? 'text-green-200' : 'text-red-200'">
              {{ game.winner_id === myId ? '¡Has hundido todos los barcos enemigos!' : '¡Tu flota ha sido hundida!' }}
            </p>
          </div>

          <div class="mb-6 text-center">
            <span class="text-amber-600 text-2xl mr-2">Estado del galeón:</span>
            <span :class="[statusClass(game.status), 'px-6 py-3 rounded-full text-xl font-bold border-2']">{{ statusText(game.status) }}</span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-4 border-2 border-amber-900">
              <h2 class="text-amber-600 text-xl mb-4 text-center">Tu flota</h2>
              <Board :ships="myBoard.ships" :shots="myBoard.shots" :is-own="true" />
            </div>
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-4 border-2 border-amber-900">
              <h2 class="text-amber-600 text-xl mb-4 text-center">
                <template v-if="rivalBoard.user && rivalBoard.user.name && game.status === 'playing'">
                  Tablero de {{ rivalBoard.user.name }}
                </template>
                <template v-else>
                  Tablero del oponente
                </template>
              </h2>
              <Board 
                :ships="rivalBoard.ships" 
                :shots="rivalBoard.shots" 
                :is-own="false"
                :is-my-turn="isMyTurn"
                @attack="handleAttack"
              />
            </div>
          </div>

          <div v-if="game.status === 'playing' && game.moves && game.moves.length >= 2" class="mb-8 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-white rounded-lg p-4 border border-gray-900 text-center">
              <h3 class="text-lg font-bold text-gray-900 mb-2">Tu último movimiento</h3>
              <div v-if="lastMyMove">
                <span class="text-gray-700">
                  Disparaste a <b>{{ lastMyMove.position }}</b> y <b>{{ lastMyMove.hit ? 'acertaste' : 'fallaste' }}</b>
                </span>
                <div class="mt-2 text-sm text-gray-600">
                  Barcos restantes: <b>{{ myBoard.ships.length - myBoard.shots.filter(s => myBoard.ships.includes(s)).length }}</b>
                </div>
              </div>
            </div>
            <div class="bg-white rounded-lg p-4 border border-gray-900 text-center">
              <h3 class="text-lg font-bold text-gray-900 mb-2">Último movimiento del oponente</h3>
              <div v-if="lastRivalMove">
                <span class="text-gray-700">
                  Disparó a <b>{{ lastRivalMove.position }}</b> y <b>{{ lastRivalMove.hit ? 'acertó' : 'falló' }}</b>
                </span>
                <div class="mt-2 text-sm text-gray-600">
                  Barcos restantes: <b>{{ rivalBoard.ships.length - rivalBoard.shots.filter(s => rivalBoard.ships.includes(s)).length }}</b>
                </div>
              </div>
            </div>
          </div>

          <div v-if="game.status === 'playing'" class="text-center">
            <div v-if="isMyTurn" class="mb-4">
              <label class="block text-amber-600 text-xl mb-2">¡Tu turno de atacar! Haz clic en una casilla del tablero del oponente</label>
              <div v-if="timeLeft <= 10" class="mt-2 text-red-500 animate-pulse">
                ¡Te quedan {{ timeLeft }} segundos para atacar!
              </div>
            </div>
            <div v-else class="text-amber-600 text-xl">Esperando el ataque del rival...</div>
          </div>

          <div v-if="game.status === 'playing'" class="mt-6 text-center">
            <button 
              @click="showSurrenderModal = true"
              class="bg-red-900 hover:bg-red-800 text-white px-6 py-2 rounded transition"
            >
              Rendirse
            </button>
          </div>
          <div v-else-if="game.status === 'waiting'" class="text-amber-600 text-xl">Esperando a que se una un rival...</div>
          <div v-else-if="game.status === 'finished'" class="text-green-300 text-xl">La batalla ha terminado.</div>

          <div class="mt-8 text-center">
            <button 
              v-if="game.status === 'finished'"
              @click="goBack" 
              class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-2 rounded transition"
            >
              Volver al puerto
            </button>
            <button 
              v-if="game.status === 'waiting'"
              @click="cancelGame" 
              class="bg-red-900 hover:bg-red-800 text-white px-6 py-2 rounded transition"
            >
              Cancelar partida
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de rendición -->
    <div v-if="showSurrenderModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-50">
      <div class="bg-gray-900/90 backdrop-blur-sm rounded-lg p-6 max-w-md w-full border-2 border-amber-900">
        <h3 class="text-xl font-bold text-amber-600 mb-4">¿Estás seguro de que quieres rendirte?</h3>
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

    <!-- Modal de confirmación de cancelación -->
    <div v-if="showCancelModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
      <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-semibold text-white mb-4">Confirmar Cancelación</h3>
        <p class="text-gray-300 mb-6">
          ¿Estás seguro de que deseas cancelar esta partida? Esta acción no se puede deshacer.
        </p>
        <div class="flex justify-end space-x-4">
          <button 
            @click="showCancelModal = false" 
            class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition"
          >
            No, mantener partida
          </button>
          <button 
            @click="confirmCancel" 
            class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition"
          >
            Sí, cancelar partida
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
      loading: false,
      pollingInterval: null,
      error: '',
      message: '',
      showSurrenderModal: false,
      showCancelModal: false,
      gameToCancel: null,
      timeLeft: 59,
      timerInterval: null,
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
    lastMyMove() {
      if (!this.game.moves || this.game.moves.length < 2) return null;
      // El último movimiento de mi usuario
      return [...this.game.moves].reverse().find(m => m.player_id === this.myId) || null;
    },
    lastRivalMove() {
      if (!this.game.moves || this.game.moves.length < 2) return null;
      // El último movimiento del rival
      const rival = this.rivalBoard.user_id;
      return [...this.game.moves].reverse().find(m => m.player_id === rival) || null;
    },
  },
  methods: {
    startPolling() {
      this.pollingInterval = setInterval(() => {
        // Hacer polling siempre que la partida no esté terminada
        if (this.game.status !== 'finished') {
          axios.get(`/games/${this.game.id}`)
            .then(response => {
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
    handleAttack(position) {
      if (this.loading || !this.isMyTurn) return;
      
      this.loading = true;
      this.error = '';
      this.message = '';
      
      axios.post(`/games/${this.game.id}/move`, { position })
        .then(res => {
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
    cancelGame() {
      this.gameToCancel = this.game.id;
      this.showCancelModal = true;
    },
    confirmCancel() {
      if (!this.gameToCancel) return;
      this.loading = true;
      this.error = '';
      this.message = '';
      this.showCancelModal = false;
      axios.delete(`/games/${this.gameToCancel}`)
        .then(() => {
          this.message = 'Partida cancelada exitosamente';
          this.goBack();
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al cancelar la partida';
        })
        .finally(() => {
          this.loading = false;
          this.gameToCancel = null;
        });
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