<template>
    <AuthenticatedLayout>
      <div class="p-6 bg-gray-100 min-h-screen">
        <h1 class="text-4xl font-extrabold mb-8 text-gray-800">Dashboard</h1>
  
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-12">
          <!-- Usuarios -->
          <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Usuarios Registrados</h2>
            <p class="text-4xl font-bold text-blue-500 mb-4">{{ userCount }}</p>
            <canvas id="usersChart" class="max-w-full"></canvas>
          </div>
  
          <!-- Trámites -->
          <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Trámites Aprobados</h2>
            <p class="text-4xl font-bold text-green-500 mb-4">{{ approvedTramitesCount }}</p>
            <canvas id="tramitesChart" class="max-w-full"></canvas>
          </div>
  
          <!-- Documentos -->
          <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Documentos Aprobados</h2>
            <p class="text-4xl font-bold text-indigo-500 mb-4">{{ approvedDocumentsCount }}</p>
            <canvas id="documentsChart" class="max-w-full"></canvas>
          </div>
  
          <!-- Placas -->
          <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center justify-center">
            <h2 class="text-2xl font-semibold text-gray-700 mb-4">Placas Registradas</h2>
            <p class="text-4xl font-bold text-purple-500 mb-4">{{ placasCount }}</p>
            <canvas id="placasChart" class="max-w-full"></canvas>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script>
  import { onMounted } from 'vue';
  import { usePage } from '@inertiajs/vue3'; 
  import Chart from 'chart.js/auto';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  
  export default {
    components: {
      AuthenticatedLayout,
    },
    setup() {

      const { props } = usePage();
      const userCount = props.userCount || 0;
      const approvedTramitesCount = props.approvedTramitesCount || 0;
      const approvedDocumentsCount = props.approvedDocumentsCount || 0;
      const placasCount = props.placasCount || 0;
  
      onMounted(() => {
        new Chart(document.getElementById('usersChart'), {
          type: 'bar',
          data: {
            labels: ['Usuarios'],
            datasets: [
              {
                label: 'Total',
                data: [userCount],
                backgroundColor: '#3b82f6',
              },
            ],
          },
        });
  
        new Chart(document.getElementById('tramitesChart'), {
          type: 'doughnut',
          data: {
            labels: ['Aprobados', 'Pendientes'],
            datasets: [
              {
                data: [approvedTramitesCount, 50],
                backgroundColor: ['#16a34a', '#facc15'],
              },
            ],
          },
        });
  
        new Chart(document.getElementById('documentsChart'), {
          type: 'line',
          data: {
            labels: ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4'],
            datasets: [
              {
                label: 'Documentos Aprobados',
                data: [20, 25, 15, approvedDocumentsCount],
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.3)',
                fill: true,
              },
            ],
          },
        });
  
        new Chart(document.getElementById('placasChart'), {
          type: 'pie',
          data: {
            labels: ['Registradas', 'Pendientes'],
            datasets: [
              {
                data: [placasCount, 100], 
                backgroundColor: ['#8b5cf6', '#f87171'],
              },
            ],
          },
        });
      });
  
      return {
        userCount,
        approvedTramitesCount,
        approvedDocumentsCount,
        placasCount,
      };
    },
  };
  </script>
  
  <style scoped>
  canvas {
    max-height: 250px;
    margin: 0 auto;
  }
  </style>
  