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
          >
            <option value="" disabled>Seleccione un trámite</option>
            <option v-for="tramite in tramites" :key="tramite.id" :value="tramite.id">
              {{ tramite.title || 'Sin título' }} ({{ tramite.status || 'Desconocido' }})
            </option>
          </select>
          <p v-if="errors.tramite_id" class="text-red-500 text-sm mt-1">{{ errors.tramite_id }}</p>
        </div>

        <!-- Número de Placa -->
        <div class="mb-4">
          <label for="placa" class="block text-gray-700">Número de Placa</label>
          <input
            v-model="form.placa"
            id="placa"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <p v-if="errors.placa" class="text-red-500 text-sm mt-1">{{ errors.placa }}</p>
        </div>

        <!-- Número de Motor -->
        <div class="mb-4">
          <label for="motor" class="block text-gray-700">Número de Motor</label>
          <input
            v-model="form.motor"
            id="motor"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <p v-if="errors.motor" class="text-red-500 text-sm mt-1">{{ errors.motor }}</p>
        </div>

        <!-- Número de Chasis -->
        <div class="mb-4">
          <label for="chasis" class="block text-gray-700">Número de Chasis</label>
          <input
            v-model="form.chasis"
            id="chasis"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <p v-if="errors.chasis" class="text-red-500 text-sm mt-1">{{ errors.chasis }}</p>
        </div>

        <!-- Póliza -->
        <div class="mb-4">
          <label for="poliza" class="block text-gray-700">Póliza</label>
          <input
            v-model="form.poliza"
            id="poliza"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
          />
          <p v-if="errors.poliza" class="text-red-500 text-sm mt-1">{{ errors.poliza }}</p>
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
    const tramites = ref(props.tramites || []);
    const placas = ref(props.placas || []);

    // Filtrar trámites aprobados y sin placa asociada
    const filteredTramites = computed(() => {
      const tramiteIdsConPlaca = new Set(placas.value.map((placa) => placa.tramite_id));
      return tramites.value.filter(
        (tramite) => tramite.status === "Aprobado" && !tramiteIdsConPlaca.has(tramite.id)
      );
    });

    // Formulario reactivo
    const form = ref({
      tramite_id: "",
      placa: "",
      motor: "",
      chasis: "",
      poliza: "",
      pago: "Pendiente",
    });

    // Errores
    const errors = ref({});

    // Enviar el formulario
    const submit = () => {
      Inertia.post(route("placas.store"), form.value, {
        onError: (errorBag) => {
          // Capturar errores del servidor
          errors.value = errorBag;
        },
        onSuccess: () => {
          // Resetear errores si la solicitud fue exitosa
          errors.value = {};
        },
      });
    };

    return { tramites: filteredTramites, form, submit, errors };
  },
};
</script>
