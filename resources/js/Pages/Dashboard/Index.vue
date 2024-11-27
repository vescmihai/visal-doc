<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-50 min-h-screen">
      <!-- Título del Dashboard -->
      <h1 class="text-5xl font-bold mb-12 text-gray-900 text-center tracking-wide">
        Visal Import Export S.A. 
      </h1>
      <div class="flex justify-end mb-4">
        <button
          @click="downloadReport"
          class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded shadow-md transition"
        >
          Generar Reporte PDF
        </button>
      </div>
      <!-- Resumen General -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
        <!-- Usuarios Totales -->
        <div class="bg-white rounded-xl shadow-md p-6 text-center transform hover:scale-105 transition-all duration-300">
          <div class="icon bg-blue-500 text-white mx-auto rounded-full p-4 mb-4">
            <i class="fas fa-users fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Usuarios Totales</h2>
          <p class="text-4xl font-extrabold text-blue-500 mb-4">{{ userCount }}</p>
          <div class="grid grid-cols-3 gap-2 text-center">
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
        <div class="bg-white rounded-xl shadow-md p-6 text-center transform hover:scale-105 transition-all duration-300">
          <div class="icon bg-indigo-500 text-white mx-auto rounded-full p-4 mb-4">
            <i class="fas fa-file-alt fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Trámites Totales</h2>
          <p class="text-4xl font-extrabold text-indigo-500 mb-4">{{ tramiteCount }}</p>
          <canvas id="tramitesChart" class="mt-4 max-w-full"></canvas>
        </div>

        <!-- Documentos Totales -->
        <div class="bg-white rounded-xl shadow-md p-6 text-center transform hover:scale-105 transition-all duration-300">
          <div class="icon bg-teal-500 text-white mx-auto rounded-full p-4 mb-4">
            <i class="fas fa-file fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Documentos Totales</h2>
          <p class="text-4xl font-extrabold text-teal-500 mb-4">{{ documentCount }}</p>
          <canvas id="documentsChart" class="mt-4 max-w-full"></canvas>
        </div>

        <!-- Placas Totales -->
        <div class="bg-white rounded-xl shadow-md p-6 text-center transform hover:scale-105 transition-all duration-300">
          <div class="icon bg-yellow-500 text-white mx-auto rounded-full p-4 mb-4">
            <i class="fas fa-id-badge fa-2x"></i>
          </div>
          <h2 class="text-lg font-semibold text-gray-700">Placas Totales</h2>
          <p class="text-4xl font-extrabold text-yellow-500 mb-4">{{ placasCount }}</p>
          <canvas id="placasChart" class="mt-4 max-w-full"></canvas>
        </div>
      </div>

      <!-- Visitas Totales -->
      <div class="bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-lg text-white shadow-lg p-8 text-center transform hover:scale-105 transition-all duration-300">
        <h2 class="text-2xl font-semibold mb-2">Visitas Totales</h2>
        <p class="text-6xl font-extrabold">{{ pageVisitCount }}</p>
      </div>
    </div>
  </AuthenticatedLayout>
</template>





<script>
import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";
import Chart from "chart.js/auto";
import axios from "axios"; // Importar Axios
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

    // Función para descargar el reporte en formato PDF
    const downloadReport = async () => {
      try {
        const response = await axios.get("/api/reports/dashboard", {
          responseType: "blob", // Asegurar que el PDF sea manejado como archivo
        });

        // Crear un enlace para descargar el archivo
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement("a");
        link.href = url;
        link.setAttribute("download", "reporte-dashboard.pdf");
        document.body.appendChild(link);
        link.click();
      } catch (error) {
        console.error("Error al descargar el reporte:", error);
      }
    };

    // Gráficos para el Dashboard
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
          labels: ["Pagadas", "Pendientes"],
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
      downloadReport, // Exponer la función para descargar el reporte
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
