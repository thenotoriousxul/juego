<template>
  <div class="bg-white rounded shadow p-6 max-w-md mx-auto">
    <h2 class="text-xl font-bold mb-4 text-center">Estadísticas de Partidas</h2>
    <canvas ref="chart" class="mb-4" style="max-height: 300px;"></canvas>
    <div class="flex justify-between text-sm text-gray-600">
      <div>Ganados: <span class="font-bold text-green-600">{{ won }}</span></div>
      <div>Perdidos: <span class="font-bold text-red-600">{{ lost }}</span></div>
      <div>Total: <span class="font-bold">{{ total }}</span></div>
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
            tooltip: { enabled: true },
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