<template>
  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black relative overflow-hidden">
    <div class="absolute inset-0 z-0">
      <div class="absolute bottom-0 right-0 w-[800px] h-[600px] opacity-30">
        <svg viewBox="0 0 800 600" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M100,400 L700,400 L650,300 L150,300 Z"/>
          
          <path fill="#1A0F0A" d="M150,300 L650,300 L600,250 L200,250 Z"/>
          
          <path fill="#8B4513" d="M400,250 L400,100 L420,100 L420,250 Z"/>
          
          <path fill="#8B4513" d="M250,250 L250,150 L270,150 L270,250 Z"/>
          
          <path fill="#8B4513" d="M550,250 L550,180 L570,180 L570,250 Z"/>
          
          <path fill="#D4AF37" d="M420,100 L600,180 L420,250 Z"/>
          <path fill="#D4AF37" d="M400,100 L220,180 L400,250 Z"/>
          
          <path fill="#D4AF37" d="M270,150 L400,200 L270,250 Z"/>
          
          <path fill="#D4AF37" d="M570,180 L700,220 L570,250 Z"/>
          
          <path fill="#000" d="M400,100 L400,50 L450,50 L400,75 Z"/>
          <path fill="#FFD700" d="M400,75 L420,65 L420,85 Z"/>
          
          <circle cx="300" cy="350" r="15" fill="#FFD700"/>
          <circle cx="400" cy="350" r="15" fill="#FFD700"/>
          <circle cx="500" cy="350" r="15" fill="#FFD700"/>
          
          <path fill="#8B4513" d="M100,400 L100,350 L150,300 L150,400 Z"/>
          <path fill="#D4AF37" d="M100,350 L120,340 L150,300 L150,350 Z"/>
          
          <path fill="#8B4513" d="M650,400 L650,350 L700,400 Z"/>
          <path fill="#D4AF37" d="M650,350 L670,340 L700,400 L650,400 Z"/>
        </svg>
      </div>
      
      <div class="absolute top-10 left-10 w-24 h-24 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
        </svg>
      </div>
      <div class="absolute top-20 right-20 w-24 h-24 opacity-40">
        <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <path fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <path fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <path fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
        </svg>
      </div>
    </div>

    <MainMenu :auth="auth" />
    
    <div class="py-8 relative z-10">
      <div class="max-w-4xl mx-auto px-4">
        <div class="bg-gray-900/90 backdrop-blur-sm rounded-lg shadow-xl p-6 border-2 border-amber-900">
          <h1 class="text-3xl font-bold text-amber-600 mb-6 text-center">Estadísticas de Batalla</h1>
          
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 text-center border border-amber-900">
              <h3 class="text-xl font-semibold text-amber-600 mb-2">Victorias</h3>
              <p class="text-3xl font-bold text-green-400">{{ won }}</p>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 text-center border border-amber-900">
              <h3 class="text-xl font-semibold text-amber-600 mb-2">Derrotas</h3>
              <p class="text-3xl font-bold text-red-400">{{ lost }}</p>
            </div>
            <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-4 text-center border border-amber-900">
              <h3 class="text-xl font-semibold text-amber-600 mb-2">Total Partidas</h3>
              <p class="text-3xl font-bold text-blue-400">{{ total }}</p>
            </div>
          </div>

          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 mb-8 border border-amber-900">
            <StatsChart 
              :won="won" 
              :lost="lost" 
              :total="total"
              @update-type="updateType" 
            />
          </div>

          <div class="flex justify-center space-x-4 mb-6">
          </div>

          <StatsTable 
            :games="showWon ? wonGames : lostGames" 
            :type="showWon ? 'won' : 'lost'"
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
import MainMenu from '../Components/MainMenu.vue';
import { Link } from '@inertiajs/vue3';

export default {
  name: 'UserStats',
  components: {
    StatsChart,
    StatsTable,
    MainMenu,
    Link
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
    },
    auth: {
      type: Object,
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
      Inertia.visit(`/history/${gameId}`);
    },
    updateType(type) {
      this.showWon = type === 'won';
    }
  }
};
</script>

<style>
@keyframes glow {
  0% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
  50% { filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.5)); }
  100% { filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.3)); }
}

svg {
  animation: glow 4s infinite;
}
</style>