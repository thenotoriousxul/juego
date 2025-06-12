<template>
  <div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Detalle de la partida #{{ game.id }}</h1>
    <GameHistory :game="game" />
    <div class="mt-8">
      <button @click="goBack" class="bg-gray-500 text-white px-3 py-1 rounded hover:bg-gray-700 transition">Volver a estadísticas</button>
    </div>
  </div>
</template>

<script>
import GameHistory from '../Components/GameHistory.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  name: 'GameHistoryView',
  components: { GameHistory },
  props: {
    game: Object,
  },
  methods: {
    goBack() {
      Inertia.visit('/stats');
    },
  },
};
</script> 