<template>
  <div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Tus estadísticas</h1>
    <StatsChart :won="won" :lost="lost" :total="total" @show-won="showType = 'won'" @show-lost="showType = 'lost'" />
    <div class="mt-8">
      <StatsTable :games="showType === 'won' ? wonGames : lostGames" :type="showType" @show-history="goToHistory" />
    </div>
  </div>
</template>

<script>
import StatsChart from '../Components/StatsChart.vue';
import StatsTable from '../Components/StatsTable.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  name: 'UserStats',
  components: { StatsChart, StatsTable },
  props: {
    won: Number,
    lost: Number,
    total: Number,
    wonGames: Array,
    lostGames: Array,
  },
  data() {
    return {
      showType: 'won',
    };
  },
  methods: {
    goToHistory(gameId) {
      Inertia.visit(`/history/${gameId}`);
    },
  },
};
</script> 