<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Trámites</h1>
        <button
          @click="generateTramite"
          class="btn-primary"
        >
          + Generar Trámite
        </button>
      </div>

      <p
        v-if="!tramites || tramites.length === 0"
        class="text-center text-gray-500 text-lg"
      >
        No hay trámites disponibles.
      </p>

      <div v-else class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full border-collapse text-left text-sm">
          <thead class="bg-gray-200 text-gray-700">
            <tr>
              <th class="px-6 py-3">Código</th>
              <th class="px-6 py-3">Cliente</th>
              <th class="px-6 py-3">Fecha de Ingreso</th>
              <th class="px-6 py-3">Última Revisión</th>
              <th class="px-6 py-3">Estado</th>
              <th v-if="auth.user.role !== 'cliente'"class="px-6 py-3">Observación</th>
              <th v-if="auth.user.role !== 'cliente'" class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="tramite in tramites"
              :key="tramite.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 text-gray-800">{{ tramite.title || 'Sin título' }}</td>
              <td class="px-6 py-4 text-gray-800">{{ tramite.client_name || 'Anónimo' }}</td>
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
              <td v-if="auth.user.role !== 'cliente'" class="px-6 py-4 flex gap-2 justify-center">
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
import { ref } from "vue";
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

    const generateRandomTitle = () => {
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      let randomTitle = '';
      for (let i = 0; i < 4; i++) {
        randomTitle += characters.charAt(Math.floor(Math.random() * characters.length));
      }
      return randomTitle;
    };

    const generateTramite = () => {
      const title = generateRandomTitle(); 

      Inertia.post(route('tramites.store'), { title })
        .then((response) => {
          tramites.value.push(response.data); 
        })
        .catch((error) => {
          console.error("Error al crear el trámite:", error);
        });
    };

    const updateStatus = (tramite, status) => {
      axios
        .put(`/tramites/${tramite.id}/update-status`, {
          status: status,
          observation: tramite.observation || "",
        })
        .then((response) => {
          tramite.status = status;
          console.log("Trámite actualizado:", response.data);
        })
        .catch((error) => {
          console.error("Error al actualizar el trámite:", error);
        });
    };

    return { tramites, generateTramite, updateStatus, auth: props.auth };
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
</style>
