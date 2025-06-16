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

    <MainMenu :auth="$page.props.auth" />
    
    <div class="relative z-10">
      <div class="max-w-7xl mx-auto p-6 lg:p-8">
        <div class="bg-gray-900/50 backdrop-blur-sm rounded-lg p-6 border-2 border-amber-900">
          <h1 class="text-3xl font-bold text-amber-600 mb-6 text-center">
            Bienvenido a Batalla Naval
          </h1>
          
          <div class="mb-6 text-center">
            <span class="text-amber-600">Navegando como: </span>
            <span class="text-amber-400">{{ $page.props.auth.user.name }}</span>
          </div>

          <div v-if="message" class="mb-4 p-3 rounded bg-green-900/50 text-green-300 border border-green-900">{{ message }}</div>
          <div v-if="error" class="mb-4 p-3 rounded bg-red-900/50 text-red-300 border border-red-900">{{ error }}</div>
          <div v-if="$page.props.flash.game_started" class="mb-4 p-3 rounded bg-amber-900/50 text-amber-300 border border-amber-900">
            {{ $page.props.flash.game_started }}
          </div>

          <div class="mb-8 text-center">
            <button 
              @click="createGame" 
              :disabled="loading" 
              class="bg-amber-900 hover:bg-amber-800 text-white px-6 py-3 rounded-lg hover:bg-amber-800 transition disabled:opacity-50 disabled:cursor-not-allowed text-lg"
            >
              <span class="text-amber-400"></span> Crear nueva partida <span class="text-amber-400"></span>
            </button>
          </div>

          <div v-if="loading" class="text-center py-8">
            <svg class="animate-spin h-8 w-8 mx-auto text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
            </svg>
            <div class="text-amber-400 mt-2">Cargando galeones...</div>
          </div>

          <div v-else>
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg overflow-hidden border border-amber-900/50">
              <table class="min-w-full">
                <thead>
                  <tr>
                    <th class="py-3 px-4 border-b border-amber-900/50 text-left text-amber-600">ID</th>
                    <th class="py-3 px-4 border-b border-amber-900/50 text-left text-amber-600">Estado</th>
                    <th class="py-3 px-4 border-b border-amber-900/50 text-left text-amber-600">Tripulación</th>
                    <th class="py-3 px-4 border-b border-amber-900/50 text-center text-amber-600">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="game in games" :key="game.id" class="hover:bg-gray-700/50 transition">
                    <td class="py-3 px-4 border-b border-amber-900/50 text-amber-100">Galeón #{{ game.id }}</td>
                    <td class="py-3 px-4 border-b border-amber-900/50">
                      <span :class="[statusClass(game.status), 'px-3 py-1 rounded-full text-sm']">
                        {{ statusText(game.status) }}
                      </span>
                    </td>
                    <td class="py-3 px-4 border-b border-amber-900/50">
                      <div v-for="board in game.boards" :key="board.id" class="text-amber-100 text-sm">
                        {{ board.user ? board.user.name : 'Capitán pendiente' }}
                      </div>
                      <div v-if="game.boards.length < 2" class="text-amber-400/70 text-sm">
                        Esperando tripulación...
                      </div>
                    </td>
                    <td class="py-3 px-4 border-b border-amber-900/50 text-center">
                      <button 
                        v-if="game.status === 'waiting' && game.boards.length < 2 && game.boards[0].user_id !== $page.props.auth.user.id" 
                        @click="joinGame(game.id)" 
                        :disabled="loading" 
                        class="bg-amber-900 text-white px-4 py-2 rounded hover:bg-amber-800 transition mr-2 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Unirse
                      </button>
                      <button 
                        v-if="game.status === 'waiting' && game.boards.length === 1 && game.boards[0].user_id === $page.props.auth.user.id"
                        @click="cancelGame(game.id)" 
                        :disabled="loading" 
                        class="bg-red-900 text-white px-4 py-2 rounded hover:bg-red-800 transition mr-2 disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Cancelar
                      </button>
                      <button 
                        @click="goToGame(game.id)" 
                        :disabled="loading" 
                        class="bg-amber-900 text-white px-4 py-2 rounded hover:bg-amber-800 transition disabled:opacity-50 disabled:cursor-not-allowed"
                      >
                        Ver partida
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-if="games.length === 0" class="text-center py-8 text-amber-400/70">
              No hay galeones en el puerto.
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
      isRedirecting: false,
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
      // Solo actualizar si no hay operaciones en curso y no estamos en proceso de redirección
      if (!this.loading && !this.isRedirecting) {
        this.isRedirecting = true;
        Inertia.reload({ only: ['games'] }).then(() => {
          // Verificar si alguna partida del usuario cambió a estado 'playing'
          const userGames = this.games.filter(game => 
            game.boards.some(board => board.user_id === this.$page.props.auth.user.id)
          );
          
          const playingGame = userGames.find(game => game.status === 'playing');
          if (playingGame) {
            Inertia.visit(`/games/${playingGame.id}`);
          } else {
            this.isRedirecting = false;
          }
        });
      }
    },
    createGame() {
      this.loading = true;
      this.error = '';
      this.message = '';
      axios.post('/games')
        .then(response => {
          this.message = '¡Partida creada exitosamente!';
          // Redirigir inmediatamente a la vista de la partida
          Inertia.visit(`/games/${response.data.id}`);
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al crear partida';
          // Si hay un gameId en la respuesta, redirigir a esa partida
          if (err.response?.data?.gameId) {
            setTimeout(() => {
              Inertia.visit(`/games/${err.response.data.gameId}`);
            }, 1500);
          }
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
          
          // Si la partida está en estado 'playing', redirigir inmediatamente
          if (response.data.game && response.data.game.status === 'playing') {
            Inertia.visit(`/games/${gameId}`);
          } else {
            // Si aún está en espera, esperar un momento antes de recargar
            setTimeout(() => {
              this.refreshGames();
            }, 1000);
          }
        })
        .catch(err => {
          this.error = err.response?.data?.message || 'Error al unirse a la partida';
          // Si hay un gameId en la respuesta, redirigir a esa partida
          if (err.response?.data?.gameId) {
            setTimeout(() => {
              Inertia.visit(`/games/${err.response.data.gameId}`);
            }, 1500);
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
        case 'waiting': return 'Esperando tripulación';
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

<style>
@import url('https://fonts.googleapis.com/css2?family=Pirata+One&display=swap');

.font-pirata {
  font-family: 'Pirata One', cursive;
}
</style> 