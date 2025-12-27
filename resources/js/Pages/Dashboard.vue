<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue'
import axios from 'axios'

import EarningsChart from '@/Components/Charts/EarningsChart.vue'
import WorkHoursChart from '@/Components/Charts/WorkHoursChart.vue'

const earnings = ref([])
const work_hours = ref([])
const summary = ref({})

onMounted(async () => {
  const response = await axios.get('/dashboard-data?model_id=1')
  earnings.value = response.data.earnings
  work_hours.value = response.data.work_hours
  summary.value = response.data.summary
})
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
            <EarningsChart v-if="earnings.length" :data="earnings" />
            <WorkHoursChart v-if="work_hours.length" :data="work_hours" />

            <div class="mt-4">
              <p>Meta: {{ summary.meta }}</p>
              <p>Horas trabajadas: {{ summary.trabajadas }}</p>
              <p>Horas faltantes: {{ summary.faltantes }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
