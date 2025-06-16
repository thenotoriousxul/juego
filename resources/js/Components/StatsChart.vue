<template>
  <div class="bg-gray-800/50 rounded-lg shadow p-6 max-w-md mx-auto border border-amber-900">
    <h2 class="text-xl font-bold mb-4 text-center text-amber-600 font-pirata">Estadísticas de Partidas</h2>
    <canvas ref="chart" class="mb-4" style="max-height: 300px;"></canvas>
    <div class="flex justify-between text-sm">
      <div class="text-amber-600">Ganados: <span class="font-bold text-green-400">{{ won }}</span></div>
      <div class="text-amber-600">Perdidos: <span class="font-bold text-red-400">{{ lost }}</span></div>
      <div class="text-amber-600">Total: <span class="font-bold text-blue-400">{{ total }}</span></div>
    </div>
  </div>
</template>

<script>
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

export default {
  name: 'StatsChart',
  props: {
    won: { type: Number, required: true },
    lost: { type: Number, required: true },
    total: { type: Number, default: null },
  },
  mounted() {
    this.renderChart();
  },
  methods: {
    renderChart() {
      const ctx = this.$refs.chart.getContext('2d');
      if (this._chart) this._chart.destroy();
      this._chart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Ganados', 'Perdidos'],
          datasets: [{
            label: 'Partidas',
            data: [this.won, this.lost],
            backgroundColor: [
              'rgba(34,197,94,0.7)', // verde
              'rgba(239,68,68,0.7)', // rojo
            ],
            borderColor: [
              'rgba(34,197,94,1)',
              'rgba(239,68,68,1)',
            ],
            borderWidth: 1,
          }],
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: { 
              enabled: true,
              backgroundColor: 'rgba(17, 24, 39, 0.9)',
              titleColor: '#D97706',
              bodyColor: '#FCD34D',
              borderColor: '#92400E',
              borderWidth: 1,
            },
          },
          scales: {
            y: {
              beginAtZero: true,
              grid: {
                color: 'rgba(217, 119, 6, 0.1)',
              },
              ticks: {
                color: '#D97706',
              }
            },
            x: {
              grid: {
                color: 'rgba(217, 119, 6, 0.1)',
              },
              ticks: {
                color: '#D97706',
              }
            }
          },
          onClick: (e, elements) => {
            if (elements.length > 0) {
              const idx = elements[0].index;
              if (idx === 0) this.$emit('show-won');
              if (idx === 1) this.$emit('show-lost');
            }
          },
        },
      });
    },
  },
  watch: {
    won() { this.renderChart(); },
    lost() { this.renderChart(); },
  },
  beforeUnmount() {
    if (this._chart) this._chart.destroy();
  },
};
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Pirata+One&display=swap');

.font-pirata {
  font-family: 'Pirata One', cursive;
}
</style> 