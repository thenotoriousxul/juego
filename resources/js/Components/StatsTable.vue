<template>
  <div class="bg-white/5 backdrop-blur-sm rounded-lg overflow-hidden">
    <h2 class="text-xl font-semibold text-white mb-4">{{ title }}</h2>
    <table class="min-w-full">
      <thead>
        <tr>
          <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">ID</th>
          <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Fecha</th>
          <th class="py-3 px-4 border-b border-white/10 text-left text-gray-300">Oponente</th>
          <th class="py-3 px-4 border-b border-white/10 text-center text-gray-300">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="game in games" :key="game.id" class="hover:bg-white/5 transition">
          <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ game.id }}</td>
          <td class="py-3 px-4 border-b border-white/10 text-gray-300">{{ formatDate(game.created_at) }}</td>
          <td class="py-3 px-4 border-b border-white/10 text-gray-300">
            {{ getOpponentName(game) }}
          </td>
          <td class="py-3 px-4 border-b border-white/10 text-center">
            <button 
              @click="$emit('show-history', game.id)"
              class="text-blue-400 hover:text-blue-300 transition"
            >
              Ver detalles
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="games.length === 0" class="text-center py-8 text-gray-400">
      No hay partidas para mostrar.
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatsTable',
  props: {
    games: {
      type: Array,
      required: true
    },
    title: {
      type: String,
      required: true
    }
  },
  methods: {
    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    getOpponentName(game) {
      const currentUserId = this.$page.props.auth.user.id;
      const opponent = game.boards.find(board => board.user_id !== currentUserId);
      return opponent?.user?.name || 'Jugador desconocido';
    }
  }
};
</script> 