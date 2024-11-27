<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div v-if="successMessage" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6">
        {{ successMessage }}
      </div>
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Trámites</h1>
        <button @click="generateTramite" class="btn-primary">
          + Generar Trámite
        </button>
      </div>

      <p v-if="!tramites.data || tramites.data.length === 0" class="text-center text-gray-500 text-lg">
        No hay trámites disponibles.
      </p>

      <div v-else class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full border-collapse text-left text-sm">
          <thead class="bg-gray-200 text-gray-700">
            <tr>
              <th class="px-6 py-3">Código</th>
              <th v-if="auth.user.role !== 'cliente'" class="px-6 py-3">Cliente</th>
              <th class="px-6 py-3">Fecha de Ingreso</th>
              <th class="px-6 py-3">Última Revisión</th>
              <th class="px-6 py-3">Estado</th>
              <th v-if="auth.user.role !== 'cliente'" class="px-6 py-3">Observación</th>
              <th class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="tramite in tramites.data" :key="tramite.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 text-gray-800">{{ tramite.title || 'Sin título' }}</td>
              <td v-if="auth.user.role !== 'cliente'" class="px-6 py-4 text-gray-800">
                {{ tramite.client_name || 'Anónimo' }}
              </td>
              <td class="px-6 py-4 text-gray-500">{{ tramite.created_at || 'N/D' }}</td>
              <td class="px-6 py-4 text-gray-500">{{ tramite.updated_at || 'N/D' }}</td>
              <td class="px-6 py-4">
                <span
                  class="inline-block px-3 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-600': tramite.status === 'Aprobado',
                    'bg-red-100 text-red-600': tramite.status === 'Rechazado',
                    'bg-yellow-100 text-yellow-600': tramite.status === 'Pendiente',
                  }"
                >
                  {{ tramite.status || 'Desconocido' }}
                </span>
              </td>
              <td v-if="auth.user.role !== 'cliente'" class="px-6 py-4">
                <input
                  v-model="tramite.observation"
                  placeholder="Añadir observación"
                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200"
                />
              </td>
              <td class="px-6 py-4 flex gap-2 justify-center">
                <button @click="viewDocuments(tramite.id)" class="btn-yellow">
                  Documentos
                </button>
                <button
                  v-if="auth.user.role !== 'cliente'"
                  @click="updateStatus(tramite, 'Aprobado')"
                  class="btn-success"
                >
                  Aprobar
                </button>
                <button
                  v-if="auth.user.role !== 'cliente'"
                  @click="updateStatus(tramite, 'Rechazado')"
                  class="btn-danger"
                >
                  Rechazar
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-6 flex justify-center items-center space-x-2">
          <button
            v-if="tramites.prev_page_url"
            @click="changePage(tramites.prev_page_url)"
            class="px-4 py-2 rounded border border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200"
          >
            Anterior
          </button>

          <button
            v-for="page in visiblePages"
            :key="page.url"
            @click="changePage(page.url)"
            :class="{
              'bg-blue-500 text-white': page.active,
              'bg-gray-100 text-gray-600 hover:bg-gray-200': !page.active
            }"
            class="px-4 py-2 rounded border border-gray-300"
            :disabled="!page.url"
          >
            {{ page.label }}
          </button>

          <button
            v-if="tramites.next_page_url"
            @click="changePage(tramites.next_page_url)"
            class="px-4 py-2 rounded border border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>



<script setup>
import { computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps(["tramites", "auth", "flash"]);


const successMessage = props.flash?.success || null;

const updateStatus = (tramite, status) => { 
  if (status === "Rechazado" && (!tramite.observation || tramite.observation.length < 5)) {
    alert("Debe agregar una observación con al menos 5 caracteres para rechazar el trámite.");
    return;
  }

  axios
    .put(`/tramites/${tramite.id}/update-status`, {
      status: status,
      observation: tramite.observation || "",
    })
    .then(() => {
      tramite.status = status;
    })
    .catch(console.error);
};

const changePage = (url) => {
  if (url) {
    Inertia.get(url);
  }
};
const generateTramite = async () => {
  const title = generateRandomTitle();
  try {
    const response = await Inertia.post(route('tramites.store'), { title });
    // Verifica la respuesta para ver qué estructura tiene
    console.log("Respuesta del servidor:", response);
    // Si la respuesta es exitosa, agrega el trámite a la lista
    if (response && response.data) {
      tramites.value.push(response.data);
    } else {
      console.error("La respuesta no contiene datos esperados.");
    }
  } catch (error) {
    console.error("Error al crear el trámite:", error);
  }
};

const generateRandomTitle = () => {
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      let randomTitle = '';

      for (let i = 0; i < 6; i++) {
        randomTitle += characters.charAt(Math.floor(Math.random() * characters.length));
      }
      return randomTitle;
    };

const visiblePages = computed(() => {
  const currentPage = props.tramites.current_page;
  const lastPage = props.tramites.last_page;

  let startPage = Math.max(currentPage - 1, 1);
  let endPage = Math.min(currentPage + 1, lastPage);

  if (currentPage === 1) {
    endPage = Math.min(startPage + 2, lastPage);
  } else if (currentPage === lastPage) {
    startPage = Math.max(endPage - 2, 1);
  }

  return props.tramites.links.filter(
    (link) =>
      link.label !== "&laquo; Previous" &&
      link.label !== "Next &raquo;" &&
      Number(link.label) >= startPage &&
      Number(link.label) <= endPage
  );
});

const viewDocuments = (tramiteId) => {
  Inertia.get(route("documents.index", { tramite_id: tramiteId }));
};
</script>




<style scoped>
  .btn-primary {
    background-color: #2563eb; 
    color: white;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }
  .btn-primary:hover {
    background-color: #1d4ed8; 
  }

  .btn-success {
    background-color: #34d399; 
    color: white;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }
  .btn-success:hover {
    background-color: #10b981; 
  }

  .btn-danger {
    background-color: #f87171; 
    color: white;
    padding: 0.5rem 1rem;
    font-weight: 600;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
  }
  .btn-danger:hover {
    background-color: #ef4444; 
  }

  .btn-yellow {
  background-color: #f59e0b; 
  color: white;
  padding: 0.5rem 1rem;
  font-weight: 600;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
}
.btn-yellow:hover {
  background-color: #d97706;
}
</style>
