<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue'
import axios from 'axios'

import EarningsChart from '@/Components/Charts/EarningsChart.vue'
import WorkHoursChart from '@/Components/Charts/WorkHoursChart.vue'

// 🔹 Obtenemos el rol del usuario desde Inertia
const { props } = usePage()
const userRole = props.userRole // viene del backend

const models = ref([])
const selectedModel = ref(null)
const earnings = ref([])
const work_hours = ref([])
const summary = ref({})
const globalSummary = ref([])
const viewMode = ref('fecha')
const chartMode = ref('absolute')

// 🔹 Filtros de fechas
const fromDate = ref(null)
const toDate = ref(null)

async function loadModels() {
  const response = await axios.get('/dashboard-models')
  models.value = response.data
  if (models.value.length) {
    selectedModel.value = models.value[0].id
  }
}

async function loadData() {
  if (viewMode.value === 'fecha' && selectedModel.value) {
    const response = await axios.get('/dashboard-data', { 
      params: { 
        model_id: selectedModel.value,
        from: fromDate.value,
        to: toDate.value
      } 
    })
    earnings.value = response.data.earnings
    work_hours.value = response.data.work_hours
    summary.value = response.data.summary
  } else {
    const response = await axios.get('/dashboard-data', { 
      params: { from: fromDate.value, to: toDate.value } 
    })
    globalSummary.value = response.data.summary
  }
}

onMounted(async () => {
  await loadModels()
  await loadData()
})

watch([viewMode, selectedModel, fromDate, toDate], loadData)
</script>

<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">

            <!-- Selectores -->
            <div class="mb-4 flex gap-4 items-center">
              <!-- Ver por -->
              <div>
                <label class="mr-2">Ver por:</label>
                <select v-model="viewMode" class="border rounded px-2 py-1">
                  <option value="fecha">Fechas</option>
                  <!-- 🔹 Solo Admin/S-Admin ven la opción Modelos -->
                  <option v-if="['Admin','S-Admin'].includes(userRole)" value="modelo">Modelos</option>
                </select>
              </div>

              <!-- Modelo -->
              <div v-if="viewMode === 'fecha'">
                <label class="mr-2">Modelo:</label>
                <select v-model="selectedModel" class="border rounded px-2 py-1">
                  <option v-for="m in models" :key="m.id" :value="m.id">
                    {{ m.name }}
                  </option>
                </select>
              </div>

              <!-- Modo gráfico -->
              <div>
                <label class="mr-2">Mostrar en:</label>
                <select v-model="chartMode" class="border rounded px-2 py-1">
                  <option value="absolute">Valores absolutos</option>
                  <option value="percent">Porcentajes</option>
                </select>
              </div>

              <!-- Filtros de fechas -->
              <div>
                <label class="mr-2">Desde:</label>
                <input type="date" v-model="fromDate" class="border rounded px-2 py-1" />
              </div>
              <div>
                <label class="mr-2">Hasta:</label>
                <input type="date" v-model="toDate" class="border rounded px-2 py-1" />
              </div>
            </div>

            <!-- Vista por fechas -->
            <div v-if="viewMode === 'fecha'">
              <!-- Tarjetas resumen -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
                <div class="bg-green-100 p-4 rounded shadow">
                  <p class="text-sm text-gray-600">Horas trabajadas</p>
                  <p class="text-2xl font-bold text-green-700">{{ summary.trabajadas }}</p>
                </div>
                <div class="bg-blue-100 p-4 rounded shadow">
                  <p class="text-sm text-gray-600">Ganancias</p>
                  <p class="text-2xl font-bold text-blue-700">${{ summary.ganancias }}</p>
                </div>
                <div class="bg-purple-100 p-4 rounded shadow">
                  <p class="text-sm text-gray-600">% Cumplimiento</p>
                  <p class="text-2xl font-bold text-purple-700">{{ summary.cumplimiento }}%</p>
                </div>
              </div>

              <!-- Gráficas -->
              <EarningsChart 
                v-if="earnings.length" 
                :data="earnings" 
                :mode="chartMode" 
                :title="`Ganancias de ${models.find(m => m.id === selectedModel)?.name || ''}`" 
              />
              <WorkHoursChart 
                v-if="work_hours.length" 
                :data="work_hours" 
                :mode="chartMode" 
                :title="`Horas de ${models.find(m => m.id === selectedModel)?.name || ''}`" 
              />
            </div>

            <!-- Vista por modelos -->
            <div v-else>
              <!-- Tarjetas resumen global -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6" v-if="globalSummary.length">
                <div class="bg-green-100 p-4 rounded shadow">
                  <p class="text-sm text-gray-600">Total horas trabajadas</p>
                  <p class="text-2xl font-bold text-green-700">
                    {{ globalSummary.reduce((acc, m) => acc + m.horas_trabajadas, 0) }}
                  </p>
                </div>
                <div class="bg-blue-100 p-4 rounded shadow">
                  <p class="text-sm text-gray-600">Total ganancias</p>
                  <p class="text-2xl font-bold text-blue-700">
                    ${{ globalSummary.reduce((acc, m) => acc + m.ganancias, 0) }}
                  </p>
                </div>
                <div class="bg-purple-100 p-4 rounded shadow">
                  <p class="text-sm text-gray-600">% Cumplimiento promedio</p>
                  <p class="text-2xl font-bold text-purple-700">
                    {{
                      (globalSummary.reduce((acc, m) => acc + m.cumplimiento, 0) / globalSummary.length).toFixed(2)
                    }}%
                  </p>
                </div>
              </div>

              <!-- Gráfica global -->
              <WorkHoursChart 
                v-if="globalSummary.length" 
                :data="globalSummary" 
                :mode="chartMode" 
                title="Resumen global de horas" 
              />
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
