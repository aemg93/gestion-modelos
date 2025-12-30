<script setup>
import { computed } from 'vue'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  data: { type: Array, required: true },
  mode: { type: String, default: 'absolute' },
  title: { type: String, default: 'Ganancias por fecha' }
})

const META = 1000 // meta ejemplo para porcentajes

const chartData = computed(() => ({
  labels: (props.data || []).map(item => item.date),
  datasets: [
    {
      label: props.mode === 'percent' ? 'Ganancias (%)' : 'Ganancias ($)',
      data: (props.data || []).map(item => {
        return props.mode === 'percent'
          ? ((item.amount / META) * 100).toFixed(2)
          : item.amount
      }),
      backgroundColor: '#10b981'
    }
  ]
}))

const chartOptions = computed(() => ({
  responsive: true,
  plugins: {
    legend: { position: 'bottom' },
    title: { display: true, text: props.title },
    tooltip: {
      callbacks: {
        label: function(context) {
          return props.mode === 'percent' ? context.raw + '%' : '$' + context.raw
        }
      }
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      max: props.mode === 'percent' ? 100 : undefined,
      ticks: {
        callback: function(value) {
          return props.mode === 'percent' ? value + '%' : '$' + value
        }
      }
    }
  }
}))
</script>

<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>
