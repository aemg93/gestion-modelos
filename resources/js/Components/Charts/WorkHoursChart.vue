<script setup>
const props = defineProps({
  data: { type: Array, required: true }
})

import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)

// Definimos la meta de horas (ejemplo: 36)
const META = 36

const chartData = {
  labels: (props.data || []).map(item => item.date),
  datasets: [
    {
      label: 'Horas trabajadas',
      data: (props.data || []).map(item => item.hours),
      backgroundColor: 'orange'
    },
    {
      label: 'Horas restantes',
      data: (props.data || []).map(item => META - item.hours),
      backgroundColor: 'lightblue'
    }
  ]
}
</script>

<template>
  <Bar :data="chartData" />
</template>
