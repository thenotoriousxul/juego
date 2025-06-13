<template>
  <div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800">
    <MainMenu :auth="$page.props.auth" />
    
    <div class="py-8">
      <div class="max-w-4xl mx-auto px-4">
        <div class="bg-white/10 backdrop-blur-lg rounded-lg shadow-xl p-6">
          <h1 class="text-3xl font-bold text-white mb-6 text-center">Estadísticas de Batalla</h1>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 text-center">
              <h3 class="text-xl font-semibold text-white mb-2">Victorias</h3>
              <p class="text-3xl font-bold text-green-400">{{ won }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 text-center">
              <h3 class="text-xl font-semibold text-white mb-2">Derrotas</h3>
              <p class="text-3xl font-bold text-red-400">{{ lost }}</p>
            </div>
            <div class="bg-white/5 backdrop-blur-sm rounded-lg p-4 text-center">
              <h3 class="text-xl font-semibold text-white mb-2">Total Partidas</h3>
              <p class="text-3xl font-bold text-blue-400">{{ total }}</p>
            </div>
          </div>

          <div class="bg-white/5 backdrop-blur-sm rounded-lg p-6 mb-8">
            <StatsChart :won="won" :lost="lost" :total="total" />
          </div>

          <div class="flex justify-center space-x-4 mb-6">
            <button 
              @click="showWon = true" 
              :class="[
                'px-4 py-2 rounded-lg transition',
                showWon 
                  ? 'bg-green-600 text-white' 
                  : 'bg-white/10 text-gray-300 hover:bg-white/20'
              ]"
            >
              Victorias
            </button>
            <button 
              @click="showWon = false" 
              :class="[
                'px-4 py-2 rounded-lg transition',
                !showWon 
                  ? 'bg-red-600 text-white' 
                  : 'bg-white/10 text-gray-300 hover:bg-white/20'
              ]"
            >
              Derrotas
            </button>
          </div>

          <StatsTable 
            :games="showWon ? wonGames : lostGames" 
            :title="showWon ? 'Partidas Ganadas' : 'Partidas Perdidas'"
            @show-history="goToHistory" 
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StatsChart from '../Components/StatsChart.vue';
import StatsTable from '../Components/StatsTable.vue';
import MainMenu from '@/Components/MainMenu.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  name: 'UserStats',
  components: {
    StatsChart,
    StatsTable,
    MainMenu
  },
  props: {
    won: {
      type: Number,
      required: true
    },
    lost: {
      type: Number,
      required: true
    },
    total: {
      type: Number,
      required: true
    },
    wonGames: {
      type: Array,
      required: true
    },
    lostGames: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      showWon: true
    };
  },
  methods: {
    goToHistory(gameId) {
      Inertia.visit(`/games/${gameId}/history`);
    }
  }
};
</script> 