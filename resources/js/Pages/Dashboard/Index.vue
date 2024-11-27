<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-50 min-h-screen">
      <!-- Título del Dashboard -->
      <h1 class="text-4xl font-extrabold mb-8 text-gray-800 text-center">
        Visal Import Export S.A.
      </h1>

      <!-- Resumen General -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <!-- Usuarios Totales -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
          <div class="icon bg-blue-500 text-white rounded-full p-4 mb-4">
            <i class="fas fa-users fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Usuarios</h2>
          <p class="text-4xl font-bold text-blue-500">{{ userCount }}</p>
          <div class="grid grid-cols-3 gap-2 mt-4 text-center">
            <div>
              <p class="text-sm text-gray-500">Clientes</p>
              <p class="text-lg font-bold text-green-500">{{ clientCount }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Gestores</p>
              <p class="text-lg font-bold text-orange-500">{{ gestorCount }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Administradores</p>
              <p class="text-lg font-bold text-red-500">{{ adminCount }}</p>
            </div>
          </div>
        </div>

        <!-- Trámites Totales -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
          <div class="icon bg-indigo-500 text-white rounded-full p-4 mb-4">
            <i class="fas fa-file-alt fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Trámites</h2>
          <p class="text-4xl font-bold text-indigo-500">{{ tramiteCount }}</p>
          <canvas id="tramitesChart" class="mt-6 max-w-full"></canvas>
        </div>

        <!-- Documentos Totales -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
          <div class="icon bg-teal-500 text-white rounded-full p-4 mb-4">
            <i class="fas fa-file fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Documentos</h2>
          <p class="text-4xl font-bold text-teal-500">{{ documentCount }}</p>
          <canvas id="documentsChart" class="mt-6 max-w-full"></canvas>
        </div>

        <!-- Placas Totales -->
        <div class="bg-white rounded-lg shadow-lg p-6 flex flex-col items-center">
          <div class="icon bg-yellow-500 text-white rounded-full p-4 mb-4">
            <i class="fas fa-id-badge fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Pagos</h2>
          <p class="text-4xl font-bold text-yellow-500">{{ placasCount }}</p>
          <canvas id="placasChart" class="mt-6 max-w-full"></canvas>
        </div>
      </div>

      <!-- Visitas Totales -->
      <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-lg text-white shadow-lg p-6 flex flex-col items-center">
        <h2 class="text-xl font-semibold mb-2">Visitas en total</h2>
        <p class="text-5xl font-bold">{{ pageVisitCount }}</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import Chart from "chart.js/auto";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: {
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();

    const userCount = props.userCount || 0;
    const clientCount = props.clientCount || 0;
    const gestorCount = props.gestorCount || 0;
    const adminCount = props.adminCount || 0;

    const tramiteCount = props.tramiteCount || 0;
    const pendingTramites = props.pendingTramites || 0;
    const approvedTramites = props.approvedTramites || 0;
    const rejectedTramites = props.rejectedTramites || 0;

    const documentCount = props.documentCount || 0;
    const pendingDocuments = props.pendingDocuments || 0;
    const approvedDocuments = props.approvedDocuments || 0;
    const rejectedDocuments = props.rejectedDocuments || 0;

    const placasCount = props.placasCount || 0;
    const pendingPlacas = props.pendingPlacas || 0;
    const paidPlacas = props.paidPlacas || 0;

    const pageVisitCount = props.pageVisitCount || 0;

    onMounted(() => {
      new Chart(document.getElementById("tramitesChart"), {
        type: "doughnut",
        data: {
          labels: ["Pendientes", "Aprobados", "Rechazados"],
          datasets: [
            {
              data: [pendingTramites, approvedTramites, rejectedTramites],
              backgroundColor: ["#facc15", "#16a34a", "#dc2626"],
            },
          ],
        },
      });

      new Chart(document.getElementById("documentsChart"), {
        type: "pie",
        data: {
          labels: ["Pendientes", "Aprobados", "Rechazados"],
          datasets: [
            {
              data: [pendingDocuments, approvedDocuments, rejectedDocuments],
              backgroundColor: ["#60a5fa", "#10b981", "#f43f5e"],
            },
          ],
        },
      });

      new Chart(document.getElementById("placasChart"), {
        type: "bar",
        data: {
          labels: ["Completados", "Pendientes"],
          datasets: [
            {
              data: [paidPlacas, pendingPlacas],
              backgroundColor: ["#fbbf24", "#ef4444"],
            },
          ],
        },
      });
    });

    return {
      userCount,
      clientCount,
      gestorCount,
      adminCount,
      tramiteCount,
      pendingTramites,
      approvedTramites,
      rejectedTramites,
      documentCount,
      pendingDocuments,
      approvedDocuments,
      rejectedDocuments,
      placasCount,
      pendingPlacas,
      paidPlacas,
      pageVisitCount,
    };
  },
};
</script>

<style scoped>
canvas {
  max-height: 200px;
  margin: 0 auto;
}

.icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 60px;
  height: 60px;
}
</style>
