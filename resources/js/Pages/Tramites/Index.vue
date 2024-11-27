<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Trámites</h1>
        <!-- Solo mostrar el botón "Generar Trámite" si el usuario no es cliente -->
        <button
          @click="generateTramite"
          class="btn-primary"
          v-if="auth.user.role !== 'cliente'"
        >
          + Generar Trámite
        </button>
      </div>

      <!-- Mensaje cuando no haya trámites disponibles -->
      <p
        v-if="!filteredTramites || filteredTramites.length === 0"
        class="text-center text-gray-500 text-lg"
      >
        No hay trámites disponibles.
      </p>

      <!-- Mostrar tabla solo si hay trámites disponibles -->
      <div v-else class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full border-collapse text-left text-sm">
          <thead class="bg-gray-200 text-gray-700">
            <tr>
              <th class="px-6 py-3">Código</th>
              <th v-if="auth.user.role !== 'cliente'"  class="px-6 py-3">Cliente</th>
              <th class="px-6 py-3">Fecha de Ingreso</th>
              <th class="px-6 py-3">Última Revisión</th>
              <th class="px-6 py-3">Estado</th>
              <!-- Solo mostrar columnas de Observación y Acciones si el usuario no es cliente -->
              <th v-if="auth.user.role !== 'cliente'" class="px-6 py-3">Observación</th>
              <th v-if="auth.user.role !== 'cliente'" class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <!-- Iterar solo sobre los trámites filtrados -->
            <tr
              v-for="tramite in filteredTramites"
              :key="tramite.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 text-gray-800">{{ tramite.title || 'Sin título' }}</td>
              <td v-if="auth.user.role !== 'cliente'"  class="px-6 py-4 text-gray-800">{{ tramite.client_name || 'Anónimo' }}</td>
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
              <!-- Solo permitir añadir observaciones si no es cliente -->
              <td v-if="auth.user.role !== 'cliente'" class="px-6 py-4">
                <input
                  v-model="tramite.observation"
                  placeholder="Añadir observación"
                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200"
                />
              </td>
              <!-- Mostrar botones de acción solo si el usuario no es cliente -->
              <td v-if="auth.user.role !== 'cliente'" class="px-6 py-4 flex gap-2 justify-center">
                <button
                  @click="viewDocuments(tramite.id)"
                  class="btn-yellow"
                >
                  Documentos
                </button>
                <button
                  @click="updateStatus(tramite, 'Aprobado')"
                  class="btn-success"
                >
                  Aprobar
                </button>
                <button
                  @click="updateStatus(tramite, 'Rechazado')"
                  class="btn-danger"
                >
                  Rechazar
                </button>
                
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


<script>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: {
    AuthenticatedLayout,
  },
  props: {
    tramites: Array, 
    auth: Object, 
  },
  setup(props) {
    const tramites = ref(props.tramites || []); 
    const userId = props.auth.user.id; // Obtener el ID del usuario autenticado

    // Filtrar los trámites si el usuario tiene el rol 'cliente'
    const filteredTramites = computed(() => {
  console.log("Trámites disponibles:", tramites.value);  // Verifica los datos completos
  if (props.auth.user.role === 'cliente') {
    const filtered = tramites.value.filter(tramite => tramite.user_id === props.auth.user.id);
    console.log("Trámites filtrados:", filtered);  // Verifica los trámites filtrados
    return filtered;
  }
  return tramites.value;
});



    // Generar un título aleatorio para los trámites
    const generateRandomTitle = () => {
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      let randomTitle = '';
      for (let i = 0; i < 4; i++) {
        randomTitle += characters.charAt(Math.floor(Math.random() * characters.length));
      }
      return randomTitle;
    };

    // Generar un nuevo trámite
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



    // Actualizar el estado de un trámite
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
    .then((response) => {
      // Actualiza el estado del trámite
      tramite.status = status;
      console.log("Trámite actualizado:", response.data);
    })
    .catch((error) => {
      console.error("Error al actualizar el trámite:", error);
    });
};


const viewDocuments = (tramiteId) => {
  Inertia.get(route('documents.index', { tramite_id: tramiteId }));
};



    return { filteredTramites, generateTramite, updateStatus, viewDocuments, auth: props.auth };
  },
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
