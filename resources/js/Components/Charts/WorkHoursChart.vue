<script setup>
import { computed } from 'vue'
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

const props = defineProps({
  data: { type: Array, required: true },
  mode: { type: String, default: 'absolute' },
  title: { type: String, default: 'Horas trabajadas vs faltantes' }
})

const META = 36

const chartData = computed(() => ({
  labels: (props.data || []).map(item => item.modelo ?? item.date),
  datasets: [
    {
      label: props.mode === 'percent' ? 'Horas trabajadas (%)' : 'Horas trabajadas',
      data: (props.data || []).map(item => {
        const trabajadas = item.horas_trabajadas ?? item.hours
        return props.mode === 'percent'
          ? ((trabajadas / META) * 100).toFixed(2)
          : trabajadas
      }),
      backgroundColor: '#f97316'
    },
    {
      label: props.mode === 'percent' ? 'Horas restantes (%)' : 'Horas restantes',
      data: (props.data || []).map(item => {
        const restantes = item.horas_restantes ?? (META - item.hours)
        return props.mode === 'percent'
          ? ((restantes / META) * 100).toFixed(2)
          : restantes
      }),
      backgroundColor: '#60a5fa'
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
          return props.mode === 'percent' ? context.raw + '%' : context.raw + ' horas'
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
          return props.mode === 'percent' ? value + '%' : value + 'h'
        }
      }
    }
  }
}))
</script>

<template>
  <Bar :data="chartData" :options="chartOptions" />
</template>
