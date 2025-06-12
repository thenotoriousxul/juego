<template>
  <div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-4">Partida #{{ game.id }}</h1>
    <div v-if="message" class="mb-4 p-2 rounded bg-green-100 text-green-800 border border-green-300">{{ message }}</div>
    <div v-if="error" class="mb-4 p-2 rounded bg-red-100 text-red-800 border border-red-300">{{ error }}</div>
    <div class="mb-4">
      <span class="font-semibold">Estado:</span>
      <span :class="statusClass(game.status)">{{ statusText(game.status) }}</span>
      <span v-if="game.status === 'finished' && game.winner_id" class="ml-4 text-green-700 font-semibold">Ganador: {{ winnerName }}</span>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div>
        <h2 class="font-semibold mb-2">Tu tablero</h2>
        <Board :board="myBoard" :is-own="true" />
      </div>
      <div>
        <h2 class="font-semibold mb-2">Tablero rival</h2>
        <Board :board="rivalBoard" :is-own="false" />
      </div>
    </div>
    <div class="mt-8">
      <div v-if="game.status === 'playing'">
        <div v-if="isMyTurn" class="mb-2 text-blue-700 font-semibold">¡Es tu turno!</div>
        <div v-else class="mb-2 text-gray-600">Esperando turno del rival...</div>
        <form @submit.prevent="shoot" class="flex items-center gap-2">
          <input v-model="shot" maxlength="3" placeholder="Ej: A3" class="border rounded px-2 py-1 w-24" :disabled="!isMyTurn || loading" />
          <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition" :disabled="!isMyTurn || loading">Disparar</button>
          <svg v-if="loading" class="animate-spin h-6 w-6 text-blue-600 ml-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
        </form>
        <div v-if="shotResult !== null" class="mt-2">
          <span v-if="shotResult" class="text-green-600 font-semibold">¡Le diste a un barco!</span>
          <span v-else class="text-red-600 font-semibold">Fallaste.</span>
        </div>
      </div>
      <div v-else-if="game.status === 'waiting'" class="text-yellow-600">Esperando a que se una un rival...</div>
      <div v-else-if="game.status === 'finished'" class="text-green-700 font-semibold">La partida ha terminado.</div>
    </div>
    <div class="mt-8">
      <button @click="goBack" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-700 transition">Volver a la lista</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';
import Board from '../Components/Board.vue';

export default {
  name: 'GameDetail',
  components: { Board },
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
        case 'waiting': return 'text-yellow-600';
        case 'playing': return 'text-blue-600';
        case 'finished': return 'text-green-600';
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