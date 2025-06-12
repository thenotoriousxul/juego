<template>
  <div class="bg-white rounded shadow p-6 max-w-2xl mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">
      {{ type === 'won' ? 'Partidas Ganadas' : 'Partidas Perdidas' }}
    </h2>
    <table class="min-w-full bg-white">
      <thead>
        <tr>
          <th class="py-2 px-4 border-b">ID</th>
          <th class="py-2 px-4 border-b">Rival</th>
          <th class="py-2 px-4 border-b">Fecha</th>
          <th class="py-2 px-4 border-b">Detalle</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="game in games" :key="game.id">
          <td class="py-2 px-4 border-b">{{ game.id }}</td>
          <td class="py-2 px-4 border-b">{{ rivalName(game) }}</td>
          <td class="py-2 px-4 border-b">{{ formatDate(game.created_at) }}</td>
          <td class="py-2 px-4 border-b text-center">
            <button @click="$emit('show-history', game.id)" class="text-blue-600 hover:text-blue-900" title="Ver detalle">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <div v-if="games.length === 0" class="text-center py-8 text-gray-500">No hay partidas para mostrar.</div>
  </div>
</template>

<script>
export default {
  name: 'StatsTable',
  props: {
    games: { type: Array, required: true },
    type: { type: String, required: true }, // 'won' o 'lost'
  },
  computed: {
    myId() {
      return this.$page.props.auth.user.id;
    },
  },
  methods: {
    rivalName(game) {
      if (!game.boards) return 'Desconocido';
      const rival = game.boards.find(b => b.user_id !== this.myId);
      return rival && rival.user ? rival.user.name : 'Desconocido';
    },
    formatDate(date) {
      return new Date(date).toLocaleString();
    },
  },
};
</script> 