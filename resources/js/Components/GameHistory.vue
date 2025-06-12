<template>
  <div class="bg-white rounded shadow p-6 max-w-4xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Historial de la Partida #{{ game.id }}</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
      <div>
        <h3 class="font-semibold mb-2 text-center">{{ playerName(game.boards[0]) }}</h3>
        <Board :board="game.boards[0]" :is-own="true" />
      </div>
      <div>
        <h3 class="font-semibold mb-2 text-center">{{ playerName(game.boards[1]) }}</h3>
        <Board :board="game.boards[1]" :is-own="true" />
      </div>
    </div>
    <h3 class="font-semibold mb-2">Movimientos realizados</h3>
    <table class="min-w-full bg-white mb-4">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b">#</th>
          <th class="py-2 px-4 border-b">Jugador</th>
          <th class="py-2 px-4 border-b">Posición</th>
          <th class="py-2 px-4 border-b">Resultado</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(move, idx) in game.moves" :key="move.id">
          <td class="py-2 px-4 border-b">{{ idx + 1 }}</td>
          <td class="py-2 px-4 border-b">{{ playerNameById(move.user_id) }}</td>
          <td class="py-2 px-4 border-b">{{ move.position }}</td>
          <td class="py-2 px-4 border-b">
            <span :class="move.hit ? 'text-green-600' : 'text-red-600'">
              {{ move.hit ? 'Acierto' : 'Fallo' }}
            </span>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import Board from './Board.vue';

export default {
  name: 'GameHistory',
  components: { Board },
  props: {
    game: { type: Object, required: true },
  },
  methods: {
    playerName(board) {
      return board && board.user ? board.user.name : 'Jugador';
    },
    playerNameById(userId) {
      const board = this.game.boards.find(b => b.user_id === userId);
      return board && board.user ? board.user.name : 'Jugador';
    },
  },
};
</script> 