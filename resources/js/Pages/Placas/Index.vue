<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div v-if="successMessage" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6">
        {{ successMessage }}
      </div>
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
                <button @click="editPlaca(placa)" class="btn-yellow">
                  Realizar pago
                </button>
                <button @click="deletePlaca(placa.id)" class="btn-danger">
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-4 flex justify-center gap-2">
          <!-- Botón "Anterior" -->
          <button
            v-if="placasData.prev_page_url"
            @click="changePage(placasData.prev_page_url)"
            class="px-4 py-2 rounded border bg-gray-100 text-gray-600 hover:bg-gray-200"
          >
            Anterior
          </button>

          <!-- Botones numerados -->
          <button
            v-for="link in visiblePages"
            :key="link.url"
            @click="changePage(link.url)"
            :disabled="!link.url || link.active"
            class="px-4 py-2 rounded border"
            :class="{
              'bg-blue-500 text-white': link.active,
              'bg-gray-100 text-gray-600 hover:bg-gray-200': !link.active,
            }"
          >
            {{ link.label }}
          </button>

          <!-- Botón "Siguiente" -->
          <button
            v-if="placasData.next_page_url"
            @click="changePage(placasData.next_page_url)"
            class="px-4 py-2 rounded border bg-gray-100 text-gray-600 hover:bg-gray-200"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: {
    Link,
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();

    // Mensaje de éxito
    const successMessage = ref(props.flash?.success || null);

    // Datos de placas
    const placasData = ref(props.placas || {});
    const placas = ref(placasData.value.data || []);
    const currentPage = ref(placasData.value.current_page || 1);
    const lastPage = ref(placasData.value.last_page || 1);

    const submit = () => {
      Inertia.post(route('placas.store'), form.value, {
        onError: (errorBag) => {
          // Manejo de errores de validación
          errors.value = errorBag;
        },
        onSuccess: () => {
          // Limpiar errores en caso de éxito
          errors.value = {};
        },
      });
    };


    // Función para editar una placa
    const editPlaca = (placa) => {
      Inertia.visit(`/ventas/create/${placa.id}`);
    };

    // Función para eliminar una placa
    const deletePlaca = async (placaId) => {
      if (confirm("¿Estás seguro de eliminar esta placa?")) {
        try {
          await Inertia.delete(`/placas/${placaId}`, {
            onSuccess: () => {
              // Mostrar mensaje de éxito y recargar datos
              successMessage.value = "Placa eliminada exitosamente.";
              Inertia.reload({ only: ["placas"] });
            },
            onError: (error) => {
              console.error("Error al eliminar la placa:", error);
            },
          });
        } catch (error) {
          console.error("Error al procesar la eliminación:", error);
        }
      }
    };

    // Función para cambiar de página
    const changePage = (url) => {
      if (url) {
        Inertia.get(url, {}, { preserveState: true, replace: true });
      }
    };

    // Cálculo de páginas visibles (máximo 3 botones numerados)
    const visiblePages = computed(() => {
      let startPage = currentPage.value - 1;
      let endPage = currentPage.value + 1;

      if (startPage < 1) {
        startPage = 1;
        endPage = Math.min(3, lastPage.value);
      }

      if (endPage > lastPage.value) {
        endPage = lastPage.value;
        startPage = Math.max(lastPage.value - 2, 1);
      }

      return placasData.value.links.filter((link) => {
        if (isNaN(link.label)) {
          return false;
        }
        const pageNumber = Number(link.label);
        return pageNumber >= startPage && pageNumber <= endPage;
      });
    });

    return {
      successMessage,
      placas,
      placasData,
      editPlaca,
      deletePlaca,
      changePage,
      visiblePages,
    };
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
