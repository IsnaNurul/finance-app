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

const chartData = {
  labels: props.data.map(item => item.month_name),
  datasets: [
    {
      label: 'Income',
      data: props.data.map(item => item.income),
      borderColor: 'rgb(34, 197, 94)',
      backgroundColor: 'rgba(34, 197, 94, 0.1)',
      tension: 0.1
    },
    {
      label: 'Expense',
      data: props.data.map(item => item.expense),
      borderColor: 'rgb(239, 68, 68)',
      backgroundColor: 'rgba(239, 68, 68, 0.1)',
      tension: 0.1
    },
    {
      label: 'Profit',
      data: props.data.map(item => item.profit),
      borderColor: 'rgb(59, 130, 246)',
      backgroundColor: 'rgba(59, 130, 246, 0.1)',
      tension: 0.1
    }
  ]
}

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: 'top',
    },
    title: {
      display: true,
      text: props.title
    }
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
        callback: function(value) {
          return 'Rp ' + value.toLocaleString('id-ID')
        }
      }
    }
  }
}

onMounted(async () => {
  await nextTick()
  if (chartCanvas.value) {
    chartInstance = new Chart(chartCanvas.value, {
      type: 'line',
      data: chartData,
      options: chartOptions
    })
  }
})

watch(() => props.data, (newData) => {
  if (chartInstance && newData) {
    chartInstance.data.labels = newData.map(item => item.month_name)
    chartInstance.data.datasets[0].data = newData.map(item => item.income)
    chartInstance.data.datasets[1].data = newData.map(item => item.expense)
    chartInstance.data.datasets[2].data = newData.map(item => item.profit)
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