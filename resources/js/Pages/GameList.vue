<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800">
    <MainMenu :auth="$page.props.auth" />
    
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-xl p-6">
          <h1 class="text-3xl font-bold text-white mb-6 text-center">Partidas de Batalla Naval</h1>
          
          <div class="mb-6 text-center">
            <span class="text-gray-300">Jugando como: </span>
            <span class="text-white font-semibold">{{ $page.props.auth.user.name }}</span>
          </div>

          <div v-if="message" class="mb-4 p-3 rounded bg-green-100 text-green-800 border border-green-300">{{ message }}</div>
          <div v-if="error" class="mb-4 p-3 rounded bg-red-100 text-red-800 border border-red-300">{{ error }}</div>
          <div v-if="$page.props.flash.game_started" class="mb-4 p-3 rounded bg-blue-100 text-blue-800 border border-blue-300">
            {{ $page.props.flash.game_started }}
          </div>

          <div class="mb-8 text-center">
            <button 
              @click="createGame" 
              :disabled="loading" 
              class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed text-lg font-medium"
            >
              Crear nueva partida
            </button>
          </div>

          <div v-if="loading" class="text-center py-8">
            <svg class="animate-spin h-8 w-8 mx-auto text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <div class="text-gray-300 mt-2">Cargando...</div>
          </div>

          <div v-else>
            <div class="bg-white/5 backdrop-blur-sm rounded-lg overflow-hidden">
              <table class="min-w-full">
                <thead>
                  <tr>
                    <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">ID</th>
                    <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Estado</th>
                    <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Jugadores</th>
                    <th class="py-3 px-4 border-b border-white/10 text-center text-gray-300">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="game in games" :key="game.id" class="hover:bg-white/5 transition">
                    <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ game.id }}</td>
                    <td class="py-3 px-4 border-b border-white/10">
                      <span :class="[statusClass(game.status), 'px-3 py-1 rounded-full text-sm']">
                        {{ statusText(game.status) }}
                      </span>
                    </td>
                    <td class="py-3 px-4 border-b border-white/10">
                      <div v-for="board in game.boards" :key="board.id" class="text-gray-300 text-sm">
                        {{ board.user ? board.user.name : 'Jugador ' + board.id }}
                      </div>
                      <div v-if="game.boards.length < 2" class="text-gray-400 text-sm">
                        Esperando oponente...
                      </div>
                    </td>
                    <td class="py-3 px-4 border-b border-white/10 text-center">
                      <button 
                        v-if="canJoin(game)" 
                        @click="joinGame(game.id)" 
                        :disabled="loading" 
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition mr-2 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Unirse
                      </button>
                      <button 
                        v-if="canCancel(game)"
                        @click="cancelGame(game.id)" 
                        :disabled="loading" 
                        class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition mr-2 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Cancelar
                      </button>
                      <button 
                        @click="goToGame(game.id)" 
                        :disabled="loading" 
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Ver
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-if="games.length === 0" class="text-center py-8 text-gray-400">
              No hay partidas disponibles.
            </div>
          </div>
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
import MainMenu from '@/Components/MainMenu.vue';

export default {
  name: 'GameList',
  components: {
    MainMenu
  },
  props: {
    games: {
      type: Array,
      required: true,
    },
  },
  data() {
    return {
      loading: false,
      error: '',
      message: '',
      showCancelModal: false,
      gameToCancel: null,
      pollingInterval: null,
    };
  },
  mounted() {
    // Iniciar polling cada 5 segundos
    this.startPolling();
  },
  beforeUnmount() {
    // Limpiar el intervalo cuando el componente se desmonte
    this.stopPolling();
  },
  methods: {
    startPolling() {
      this.pollingInterval = setInterval(() => {
        this.refreshGames();
      }, 5000); // Actualizar cada 5 segundos
    },
    stopPolling() {
      if (this.pollingInterval) {
        clearInterval(this.pollingInterval);
        this.pollingInterval = null;
      }
    },
    refreshGames() {
      // Solo actualizar si no hay operaciones en curso
      if (!this.loading) {
        Inertia.reload({ only: ['games'] });
      }
    },
    createGame() {
      this.loading = true;
      this.error = '';
      this.message = '';
      axios.post('/games')
        .then(() => {
          this.message = '¡Partida creada exitosamente!';
          // Recargar inmediatamente después de crear una partida
          this.refreshGames();
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al crear partida';
        })
        .finally(() => {
          this.loading = false;
        });
    },
    joinGame(gameId) {
      this.loading = true;
      this.error = '';
      this.message = '';
      axios.post(`/games/${gameId}/join`)
        .then(response => {
          this.message = response.data.message;
          // Recargar inmediatamente después de unirse a una partida
          this.refreshGames();
          setTimeout(() => {
            Inertia.visit(`/games/${gameId}`);
          }, 800);
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al unirse a la partida';
          if (err.response?.data?.gameId) {
            setTimeout(() => {
              Inertia.visit(`/games/${err.response.data.gameId}`);
            }, 800);
          }
        })
        .finally(() => {
          this.loading = false;
        });
    },
    goToGame(gameId) {
      Inertia.visit(`/games/${gameId}`);
    },
    canJoin(game) {
      return game.status === 'waiting' && game.boards.length < 2;
    },
    canCancel(game) {
      return game.status === 'waiting' && 
             game.boards.length === 1 && 
             game.boards[0].user_id === this.$page.props.auth.user.id;
    },
    cancelGame(gameId) {
      this.gameToCancel = gameId;
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
          // Recargar inmediatamente después de cancelar una partida
          this.refreshGames();
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
  },
};
</script> 