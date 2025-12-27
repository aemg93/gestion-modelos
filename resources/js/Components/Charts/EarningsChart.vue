<script setup>
const props = defineProps({
  data: { type: Array, required: true }
})

import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement } from 'chart.js'
import { Line } from 'vue-chartjs'

// Registramos los módulos para línea
ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, LinearScale, PointElement)

const META = 1000

const chartData = {
  labels: (props.data || []).map(item => item.date),
  datasets: [
    {
      label: 'Ganancias',
      data: (props.data || []).map(item => parseFloat(item.amount)),
      borderColor: 'blue',
      backgroundColor: 'lightblue',
      tension: 0.3
    },
    {
      label: 'Ganancias restantes',
      data: (props.data || []).map(item => META - parseFloat(item.amount)),
      borderColor: 'red',
      backgroundColor: 'pink',
      tension: 0.3
    }
  ]
}
</script>

<template>
  <Line :data="chartData" />
</template>
