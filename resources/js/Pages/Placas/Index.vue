<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Gestión de Placas</h1>

      <div class="mb-6">
        <Link
          href="/placas/create"
          class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600"
        >
          Crear Placa
        </Link>
      </div>

      <p v-if="!placas || placas.length === 0" class="text-gray-500">
        No hay placas registradas.
      </p>

      <!-- Tabla de Placas -->
      <table v-else class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
        <thead class="bg-gray-200 text-gray-700">
          <tr>
            <th class="px-6 py-3 text-left">ID</th>
            <th class="px-6 py-3 text-left">Número de Placa</th>
            <th class="px-6 py-3 text-left">Número de Motor</th>
            <th class="px-6 py-3 text-left">Número de Chasis</th>
            <th class="px-6 py-3 text-left">Póliza</th>
            <th class="px-6 py-3 text-left">Estado de Pago</th>
            <th class="px-6 py-3 text-left">Trámite Asociado</th>
            <th class="px-6 py-3 text-left">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="placa in placas" :key="placa.id" class="border-b">
            <td class="px-6 py-4 text-gray-800">{{ placa.id }}</td>
            <td class="px-6 py-4 text-gray-800">{{ placa.placa }}</td>
            <td class="px-6 py-4 text-gray-800">{{ placa.motor }}</td>
            <td class="px-6 py-4 text-gray-800">{{ placa.chasis }}</td>
            <td class="px-6 py-4 text-gray-800">{{ placa.poliza }}</td>
            <td class="px-6 py-4 text-gray-500">
              {{ placa.estado_pago || 'Pendiente' }}
            </td>
            <td class="px-6 py-4 text-gray-800">
              <span v-if="placa.tramite">{{ placa.tramite.title }}</span>
              <span v-else>--</span>
            </td>
            <td class="px-6 py-4 flex gap-2">
              <button
                @click="editPlaca(placa)"
                class="bg-yellow-500 text-white px-4 py-2 rounded-md hover:bg-yellow-600"
              >
                Realizar pago
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  components: {
    Link,
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();
    const placas = ref(props.placas || []); 

    const editPlaca = (placa) => {
      Inertia.visit(`/placas/${placa.id}/edit`);
    };

    const deletePlaca = (placaId) => {
      if (confirm('¿Estás seguro de eliminar esta placa?')) {
        axios
          .delete(`/placas/${placaId}`)
          .then((response) => {
            placas.value = placas.value.filter((placa) => placa.id !== placaId);
            console.log('Placa eliminada:', response.data);
          })
          .catch((error) => {
            console.error('Error al eliminar la placa:', error);
          });
      }
    };

    return { placas, editPlaca, deletePlaca };
  },
};
</script>
