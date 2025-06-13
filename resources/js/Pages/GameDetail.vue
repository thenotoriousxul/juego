<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800">
    <MainMenu :auth="$page.props.auth" />
    
    <div class="py-8">
      <div class="max-w-5xl mx-auto px-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-xl p-6">
          <h1 class="text-3xl font-bold text-white mb-6 text-center">Partida #{{ game.id }}</h1>
          
          <div v-if="message" class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">{{ message }}</div>
          <div v-if="error" class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">{{ error }}</div>
          <div v-if="$page.props.flash.game_started" class="mb-4 p-3 rounded bg-blue-100 text-blue-800 border border-blue-300">
            {{ $page.props.flash.game_started }}
          </div>

          <div class="mb-6 text-center">
            <span class="text-white font-semibold mr-2">Estado:</span>
            <span :class="[statusClass(game.status), 'px-3 py-1 rounded-full text-sm']">{{ statusText(game.status) }}</span>
            <span v-if="game.status === 'finished' && game.winner_id" class="ml-4 text-green-300 font-semibold">
              Ganador: {{ winnerName }}
            </span>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4">
              <h2 class="font-semibold text-white mb-4 text-center">Tu tablero</h2>
              <Board :board="myBoard" :is-own="true" />
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4">
              <h2 class="font-semibold text-white mb-4 text-center">Tablero rival</h2>
              <Board :board="rivalBoard" :is-own="false" />
            </div>
          </div>

          <div class="mt-8 text-center">
            <div v-if="game.status === 'playing'">
              <div v-if="isMyTurn" class="mb-4 text-green-300 font-semibold text-lg">¡Es tu turno!</div>
              <div v-else class="mb-4 text-gray-300">Esperando turno del rival...</div>
              
              <form @submit.prevent="shoot" class="flex items-center justify-center gap-3 mb-6">
                <input 
                  v-model="shot" 
                  maxlength="3" 
                  placeholder="Ej: A3" 
                  class="bg-white/10 border border-white/20 rounded px-3 py-2 text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 w-24" 
                  :disabled="!isMyTurn || loading" 
                />
                <button 
                  type="submit" 
                  class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed" 
                  :disabled="!isMyTurn || loading"
                >
                  Disparar
                </button>
                <svg v-if="loading" class="animate-spin h-6 w-6 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
              </form>

              <div v-if="shotResult !== null" class="mb-6">
                <span v-if="shotResult" class="text-green-400 font-semibold">¡Le diste a un barco!</span>
                <span v-else class="text-red-400 font-semibold">Fallaste.</span>
              </div>

              <button 
                @click="showSurrenderModal = true"
                class="bg-red-600 text-white px-6 py-2 rounded hover:bg-red-700 transition"
              >
                Rendirse
              </button>
            </div>
            <div v-else-if="game.status === 'waiting'" class="text-yellow-300 text-lg">Esperando a que se una un rival...</div>
            <div v-else-if="game.status === 'finished'" class="text-green-300 text-lg font-semibold">La partida ha terminado.</div>
          </div>

          <div class="mt-8 text-center">
            <button @click="goBack" class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700 transition">
              Volver a la lista
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de confirmación de rendición -->
    <div v-if="showSurrenderModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-xl font-bold text-gray-900 mb-4">¿Estás seguro de que quieres rendirte?</h3>
        <p class="text-gray-600 mb-6">Esta acción no se puede deshacer y perderás la partida.</p>
        <div class="flex justify-end gap-3">
          <button 
            @click="showSurrenderModal = false"
            class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition"
          >
            Cancelar
          </button>
          <button 
            @click="confirmSurrender"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition"
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
    },
    startPolling() {
      this.stopPolling();
      if (this.game.status === 'playing' || this.game.status === 'waiting') {
        this.pollingInterval = setInterval(() => {
          this.reloadGame();
        }, 2000);
      }
    },
    stopPolling() {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
      }
    },
    reloadGame() {
      this.loading = true;
      this.shotResult = null;
      this.$inertia.reload({ only: ['game'] });
      this.loading = false;
    },
  },
};
</script> 