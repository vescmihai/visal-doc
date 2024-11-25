<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Gestión de Trámites</h1>

      <!-- Botón para crear un nuevo trámite -->
      <div class="mb-6">
        <Link
          href="/tramites/create"
          class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
        >
          Crear Trámite
        </Link>
      </div>

      <!-- Mostrar mensaje si no hay trámites -->
      <p v-if="!tramites || tramites.length === 0" class="text-gray-500">
        No hay trámites disponibles.
      </p>

      <!-- Tabla de Trámites -->
      <table v-else class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="px-6 py-3 text-left">ID</th>
            <th class="px-6 py-3 text-left">Título</th>
            <th class="px-6 py-3 text-left">Estado</th>
            <th class="px-6 py-3 text-left">Cliente</th>
            <th class="px-6 py-3 text-left">Observación</th>
            <th class="px-6 py-3 text-left">Fecha de Ingreso</th>
            <th class="px-6 py-3 text-left">Última Revisión</th>
            <th class="px-6 py-3 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="tramite in tramites"
            :key="tramite.id"
            class="border-b"
          >
            <td class="px-6 py-4 text-gray-800">{{ tramite.id || 'N/A' }}</td>
            <td class="px-6 py-4 text-gray-800">{{ tramite.title || 'Sin título' }}</td>
            <td class="px-6 py-4 text-gray-500">{{ tramite.status || 'Desconocido' }}</td>
            <td class="px-6 py-4 text-gray-800">{{ tramite.client_name || 'Anónimo' }}</td>
            <td class="border border-gray-300 px-4 py-2">
              <input
                v-model="tramite.observation"
                placeholder="Añadir observación"
                class="w-full px-2 py-1 border border-gray-300 rounded"
              />
            </td>
            <td class="px-6 py-4 text-gray-500">{{ tramite.created_at || 'N/D' }}</td>
            <td class="px-6 py-4 text-gray-500">{{ tramite.updated_at || 'N/D' }}</td>
            <td class="px-6 py-4 flex gap-2">
              <button
                @click="updateStatus(tramite, 'Aprobado')"
                class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600"
              >
                Aprobar
              </button>
              <button
                @click="updateStatus(tramite, 'Rechazado')"
                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600"
              >
                Rechazar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  components: {
    Link,
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();
    const tramites = ref(props.tramites || []); 

    const updateStatus = (tramite, status) => {
      axios
        .put(`/tramites/${tramite.id}/update-status`, {
          status: status,
          observation: tramite.observation || '', 
        })
        .then((response) => {
          tramite.status = status; 
          console.log('Trámite actualizado:', response.data);
        })
        .catch((error) => {
          console.error('Error al actualizar el trámite:', error);
        });
    };

    return { tramites, updateStatus };
  },
};
</script>
