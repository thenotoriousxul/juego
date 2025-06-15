<template>
  <div class="inline-block border rounded shadow bg-white overflow-x-auto">
    <table class="table-fixed border-collapse">
      <thead>
        <tr>
          <th class="w-8 h-8"></th>
          <th v-for="col in 8" :key="col" class="w-8 h-8 text-center">{{ col }}</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="row in rows" :key="row">
          <th class="w-8 h-8 text-center">{{ row }}</th>
          <td v-for="col in 8" :key="col" class="w-8 h-8 text-center border relative">
            <span v-if="isShip(row, col) && isOwn" class="inline-block w-4 h-4 bg-gray-700 rounded-full"></span>
            <span v-if="isShot(row, col)" :class="shotClass(row, col)" class="inline-block w-6 h-6 rounded-full absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2"></span>
          </td>
        </tr>
      </tbody>
    </table>
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
        return 'bg-red-500 border-2 border-red-700'; // acierto
      } else {
        return 'bg-blue-300 border-2 border-blue-500'; // fallo
      }
    }
  }
};
</script> 