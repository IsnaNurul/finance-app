<template>
  <div class="chart-container">
    <canvas ref="chartCanvas"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
import Chart from 'chart.js/auto'

const props = defineProps({
  data: {
    type: Array,
    required: true
  },
  title: {
    type: String,
    default: 'Chart'
  }
})

const chartCanvas = ref(null)
let chartInstance = null

const colors = [
  'rgb(239, 68, 68)',
  'rgb(245, 158, 11)',
  'rgb(34, 197, 94)',
  'rgb(59, 130, 246)',
  'rgb(147, 51, 234)',
  'rgb(236, 72, 153)',
  'rgb(14, 165, 233)',
  'rgb(16, 185, 129)',
  'rgb(251, 146, 60)',
  'rgb(168, 85, 247)'
]

const chartData = {
  labels: props.data.map(item => item.name),
  datasets: [
    {
      data: props.data.map(item => item.total_expense),
      backgroundColor: colors.slice(0, props.data.length),
      borderWidth: 2,
      borderColor: '#ffffff'
    }
  ]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'bottom',
      labels: {
        padding: 20,
        usePointStyle: true
      }
    },
    title: {
      display: true,
      text: props.title
    },
    tooltip: {
      callbacks: {
        label: function(context) {
          const label = context.label || ''
          const value = context.parsed
          return label + ': Rp ' + value.toLocaleString('id-ID')
        }
      }
    }
  }
}

onMounted(async () => {
  await nextTick()
  if (chartCanvas.value) {
    chartInstance = new Chart(chartCanvas.value, {
      type: 'doughnut',
      data: chartData,
      options: chartOptions
    })
  }
})

watch(() => props.data, (newData) => {
  if (chartInstance && newData) {
    chartInstance.data.labels = newData.map(item => item.name)
    chartInstance.data.datasets[0].data = newData.map(item => item.total_expense)
    chartInstance.data.datasets[0].backgroundColor = colors.slice(0, newData.length)
    chartInstance.update()
  }
}, { deep: true })
</script>

<style scoped>
.chart-container {
  position: relative;
  height: 300px;
  width: 100%;
}
</style>