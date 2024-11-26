<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Placas</h1>
        <Link href="/placas/create" class="btn-primary">+ Registrar Placa</Link>
      </div>

      <p v-if="!placas || placas.length === 0" class="text-center text-gray-500 text-lg">
        No hay placas registradas.
      </p>

      <div v-else class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-200 text-gray-700">
            <tr>
              <th class="px-6 py-3 text-left">ID</th>
              <th class="px-6 py-3 text-left">Número de Placa</th>
              <th class="px-6 py-3 text-left">Número de Motor</th>
              <th class="px-6 py-3 text-left">Número de Chasis</th>
              <th class="px-6 py-3 text-left">Póliza</th>
              <th class="px-6 py-3 text-left">Estado de Pago</th>
              <th class="px-6 py-3 text-left">Trámite Asociado</th>
              <th class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="placa in placas" :key="placa.id" class="border-b hover:bg-gray-50 transition">
              <td class="px-6 py-4 text-gray-800">{{ placa.id }}</td>
              <td class="px-6 py-4 text-gray-800">{{ placa.placa }}</td>
              <td class="px-6 py-4 text-gray-800">{{ placa.motor }}</td>
              <td class="px-6 py-4 text-gray-800">{{ placa.chasis }}</td>
              <td class="px-6 py-4 text-gray-800">{{ placa.poliza }}</td>
              <td class="px-6 py-4">
  <span
    class="inline-block px-3 py-1 text-xs font-semibold rounded-full transition-all duration-300"
    :class="{
      'bg-green-100 text-green-600': placa.pago === 'Pagado',
      'bg-yellow-100 text-yellow-600': placa.pago === 'Pendiente',
      'bg-red-100 text-red-600': placa.pago === 'Vencido',
      'bg-gray-100 text-gray-600': !placa.pago || placa.pago === 'Pendiente'
    }"
  >
    {{ placa.pago || 'Pendiente' }}
  </span>
</td>

              <td class="px-6 py-4 text-gray-800">
                <span v-if="placa.tramite">{{ placa.tramite.title }}</span>
                <span v-else>--</span>
              </td>
              <td class="px-6 py-4 flex justify-center gap-2">
                <button
                  @click="editPlaca(placa)"
                  class="btn-yellow"
                >
                  Realizar pago
                </button>
                <button
                  @click="deletePlaca(placa.id)"
                  class="btn-danger"
                >
                  Eliminar
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
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Inertia } from '@inertiajs/inertia';

export default {
  components: {
    Link,
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();
    const placas = ref(props.placas || []); 

    const editPlaca = (placa) => {
      Inertia.visit(`/ventas/create/${placa.id}`);
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
