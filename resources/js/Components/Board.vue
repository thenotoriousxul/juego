<template>
  <div class="flex justify-center items-center">
    <div class="inline-block border-2 border-gray-900 rounded-lg shadow-lg bg-white overflow-x-auto">
      <table class="table-fixed border-collapse">
        <thead>
          <tr>
            <th class="w-10 h-10 text-amber-600"></th>
            <th v-for="col in 8" :key="col" class="w-10 h-10 text-center text-amber-600">{{ col }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in rows" :key="row">
            <th class="w-10 h-10 text-center text-amber-600">{{ row }}</th>
            <td v-for="col in 8" :key="col" class="w-10 h-10 text-center border border-amber-900/50 relative">
              <span v-if="isShip(row, col) && isOwn" class="inline-block w-6 h-6 text-2xl">🚢</span>
              <span v-if="isShot(row, col)" :class="shotClass(row, col)" class="inline-block w-7 h-7 text-2xl absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2">
                {{ isShip(row, col) ? '💥' : '💧' }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Board',
  props: {
    ships: {
      type: Array,
      default: () => []
    },
    shots: {
      type: Array,
      default: () => []
    },
    isOwn: {
      type: Boolean,
      default: false
    }
  },
  computed: {
    rows() {
      return ['A','B','C','D','E','F','G','H'];
    }
  },
  methods: {
    isShip(row, col) {
      return this.ships.includes(row + col);
    },
    isShot(row, col) {
      return this.shots.includes(row + col);
    },
    shotClass(row, col) {
      if (this.isShip(row, col)) {
        return 'bg-red-900/50 text-red-600'; // acierto
      } else {
        return 'bg-blue-900/50 text-blue-600'; // fallo
      }
    }
  }
};
</script>

<style>
</style> 