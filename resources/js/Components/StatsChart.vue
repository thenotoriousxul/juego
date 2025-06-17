<template>
  <div class="relative">
    <p class="text-amber-600 text-center mb-4 font-semibold">Presiona las gráficas para ver el historial de partidas</p>
    
    <div v-if="total === 0" class="flex flex-col items-center space-y-4">
      <div class="flex justify-center space-x-8">
        <div class="w-32 h-32 relative">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-16 h-full bg-gray-600 rounded-t-lg relative overflow-hidden">
              <div class="absolute bottom-0 left-0 right-0 bg-gray-500 h-full"></div>
            </div>
          </div>
        </div>

        <div class="w-32 h-32 relative">
          <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-16 h-full bg-gray-600 rounded-t-lg relative overflow-hidden">
              <div class="absolute bottom-0 left-0 right-0 bg-gray-500 h-full"></div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="text-center">
        <span class="text-xl font-bold text-amber-400">Sin datos disponibles</span>
        <p class="text-amber-300 mt-2">Aún no hay registro de partidas para mostrar estadísticas</p>
      </div>
    </div>

    <div v-else class="flex justify-center space-x-8">
      <div 
        class="w-32 h-32 relative cursor-pointer transform transition-transform hover:scale-110"
        @click="$emit('update-type', 'won')"
      >
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="w-16 h-full bg-gray-800 rounded-t-lg relative overflow-hidden">
            <div 
              class="absolute bottom-0 left-0 right-0 bg-green-500 transition-all duration-500"
              :style="{ height: `${(won / total) * 100}%` }"
            ></div>
          </div>
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
          <span class="text-2xl font-bold">{{ Math.round((won / total) * 100) }}%</span>
          <span class="text-sm">Victorias</span>
        </div>
      </div>

      <div 
        class="w-32 h-32 relative cursor-pointer transform transition-transform hover:scale-110"
        @click="$emit('update-type', 'lost')"
      >
        <div class="absolute inset-0 flex items-center justify-center">
          <div class="w-16 h-full bg-gray-800 rounded-t-lg relative overflow-hidden">
            <div 
              class="absolute bottom-0 left-0 right-0 bg-red-500 transition-all duration-500"
              :style="{ height: `${(lost / total) * 100}%` }"
            ></div>
          </div>
        </div>
        <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
          <span class="text-2xl font-bold">{{ Math.round((lost / total) * 100) }}%</span>
          <span class="text-sm">Derrotas</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'StatsChart',
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
    }
  },
  emits: ['update-type']
};
</script>

<style scoped>
.cursor-pointer {
  cursor: pointer;
}
</style> 