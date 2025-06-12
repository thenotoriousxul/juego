<template>
  <div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Partidas de Batalla Naval</h1>
    <div class="mb-4 text-gray-600">
      Jugando como: <span class="font-semibold">{{ $page.props.auth.user.name }}</span>
    </div>
    <div v-if="message" class="mb-4 p-2 rounded bg-green-100 text-green-800 border border-green-300">{{ message }}</div>
    <div v-if="error" class="mb-4 p-2 rounded bg-red-100 text-red-800 border border-red-300">{{ error }}</div>
    <div v-if="$page.props.flash.game_started" class="mb-4 p-2 rounded bg-blue-100 text-blue-800 border border-blue-300">
      {{ $page.props.flash.game_started }}
    </div>
    <div class="mb-4 flex justify-between items-center">
      <button @click="createGame" :disabled="loading" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition disabled:opacity-50">Crear nueva partida</button>
    </div>
    <div v-if="loading" class="text-center py-8">
      <svg class="animate-spin h-8 w-8 mx-auto text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg>
      <div>Cargando...</div>
    </div>
    <div v-else>
      <table class="min-w-full bg-white shadow rounded">
        <thead>
          <tr>
            <th class="py-2 px-4 border-b">ID</th>
            <th class="py-2 px-4 border-b">Estado</th>
            <th class="py-2 px-4 border-b">Jugadores</th>
            <th class="py-2 px-4 border-b">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="game in games" :key="game.id">
            <td class="py-2 px-4 border-b">{{ game.id }}</td>
            <td class="py-2 px-4 border-b">
              <span :class="statusClass(game.status)">{{ statusText(game.status) }}</span>
            </td>
            <td class="py-2 px-4 border-b">
              <div v-for="board in game.boards" :key="board.id" class="text-sm">
                {{ board.user ? board.user.name : 'Jugador ' + board.id }}
              </div>
              <div v-if="game.boards.length < 2" class="text-sm text-gray-500">
                Esperando oponente...
              </div>
            </td>
            <td class="py-2 px-4 border-b">
              <button v-if="canJoin(game)" @click="joinGame(game.id)" :disabled="loading" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 transition mr-2 disabled:opacity-50">Unirse</button>
              <button @click="goToGame(game.id)" :disabled="loading" class="bg-gray-600 text-white px-2 py-1 rounded hover:bg-gray-700 transition disabled:opacity-50">Ver</button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-if="games.length === 0" class="text-center py-8 text-gray-500">No hay partidas disponibles.</div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import { Inertia } from '@inertiajs/inertia';

export default {
  name: 'GameList',
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
    };
  },
  methods: {
    createGame() {
      this.loading = true;
      this.error = '';
      this.message = '';
      axios.post('/games')
        .then(() => {
          this.message = '¡Partida creada exitosamente!';
          setTimeout(() => window.location.reload(), 800);
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
  },
};
</script> 