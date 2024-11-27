<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Crear Nueva Placa</h1>
      
      <form @submit.prevent="submit">
        <!-- Selección de Trámite -->
        <div class="mb-4">
  <label for="tramite_id" class="block text-gray-700">Trámite</label>
  <select
    v-model="form.tramite_id"
    id="tramite_id"
    class="w-full px-4 py-2 border border-gray-300 rounded"
    required
  >
    <option v-for="tramite in tramites" :key="tramite.id" :value="tramite.id">
      {{ tramite.title || 'Sin título' }} ({{ tramite.status || 'Desconocido' }})
    </option>
  </select>
  <p v-if="!tramites.length" class="text-red-500 text-sm mt-2">
    No se encontró ningún trámite aprobado, debe aprobar al menos un tramite para registrar la placa.
  </p>
</div>


        <!-- Número de Placa -->
        <div class="mb-4">
          <label for="placa" class="block text-gray-700">Número de Placa</label>
          <input
            v-model="form.placa"
            id="placa"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
            required
          />
        </div>

        <!-- Número de Motor -->
        <div class="mb-4">
          <label for="motor" class="block text-gray-700">Número de Motor</label>
          <input
            v-model="form.motor"
            id="motor"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
            required
          />
        </div>

        <!-- Número de Chasis -->
        <div class="mb-4">
          <label for="chasis" class="block text-gray-700">Número de Chasis</label>
          <input
            v-model="form.chasis"
            id="chasis"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
            required
          />
        </div>

        <!-- Póliza -->
        <div class="mb-4">
          <label for="poliza" class="block text-gray-700">Póliza</label>
          <input
            v-model="form.poliza"
            id="poliza"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
            required
          />
        </div>

        <!-- Botón de guardar -->
        <button
          type="submit"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          Guardar
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { ref, computed } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { usePage } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: {
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();
    const tramites = ref(props.tramites || []); // Lista de trámites desde el servidor
    const placas = ref(props.placas || []); // Lista de placas ya registradas

    // Filtrar trámites aprobados y sin placa asociada
    const filteredTramites = computed(() => {
      const tramiteIdsConPlaca = new Set(placas.value.map((placa) => placa.tramite_id));
      return tramites.value.filter(
        (tramite) => tramite.status === "Aprobado" && !tramiteIdsConPlaca.has(tramite.id)
      );
    });

    const form = ref({
      tramite_id: "",
      placa: "",
      motor: "",
      chasis: "",
      poliza: "",
      pago: "Pendiente", 
    });

    const submit = () => {
      Inertia.post(route("placas.store"), form.value);
    };

    return { tramites: filteredTramites, form, submit };
  },
};
</script>
