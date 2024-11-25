<template>
  <div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Crear Nuevo Documento</h1>

    <form @submit.prevent="submit">
      <!-- Campo Tipo de Documento -->
      <div class="mb-4">
        <label for="type" class="block text-gray-700 font-bold mb-2">Tipo de Documento:</label>
        <select
          id="type"
          v-model="form.type"
          class="w-full px-4 py-2 border border-gray-300 rounded"
          required
        >
          <option value="" disabled>Seleccione un tipo de documento</option>
          <option
            v-for="type in availableDocumentTypes"
            :key="type"
            :value="type"
          >
            {{ type }}
          </option>
        </select>
        <p v-if="documentAlreadyExists" class="text-red-500 text-sm mt-2">
          El tipo de documento seleccionado ya ha sido registrado para este trámite.
        </p>
      </div>

      <!-- Campo Archivo -->
      <div class="mb-4">
        <label for="file" class="block text-gray-700 font-bold mb-2">Archivo:</label>
        <input
          id="file"
          type="file"
          @change="handleFileUpload"
          class="w-full px-4 py-2 border border-gray-300 rounded"
          required
        />
      </div>

      <!-- Campo Trámite Asociado -->
      <div class="mb-4">
        <label for="tramite_id" class="block text-gray-700 font-bold mb-2">Trámite Asociado:</label>
        <select
          id="tramite_id"
          v-model="form.tramite_id"
          @change="updateAvailableDocumentTypes"
          class="w-full px-4 py-2 border border-gray-300 rounded"
          required
        >
          <option value="" disabled>Seleccione un trámite</option>
          <option v-for="tramite in tramites" :key="tramite.id" :value="tramite.id">
            {{ tramite.title }}
          </option>
        </select>
      </div>

      <!-- Botón de Guardar -->
      <button
        type="submit"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        :disabled="documentAlreadyExists"
      >
        Guardar
      </button>
    </form>
  </div>
</template>

<script>
import { reactive, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";

export default {
  props: {
    tramites: Array, 
    documentosRegistrados: Array, 
  },
  setup(props) {
    const form = reactive({
      type: "",
      file: null,
      tramite_id: null, 
    });

    // Tipos de documentos predefinidos
    const documentTypes = [
      "Nota de Remisión",
      "Carnet de Identidad",
      "Factura",
      "Precontrato",
      "Póliza simple",
    ];

    const availableDocumentTypes = reactive([...documentTypes]);

    const documentAlreadyExists = reactive(false);

    const handleFileUpload = (event) => {
      form.file = event.target.files[0];
    };

    const updateAvailableDocumentTypes = () => {
      if (form.tramite_id) {
        const existingDocuments = props.documentosRegistrados.filter(
          (doc) => doc.tramite_id === form.tramite_id
        ).map((doc) => doc.type);

        availableDocumentTypes.length = 0; 
        documentTypes.forEach((type) => {
          if (!existingDocuments.includes(type)) {
            availableDocumentTypes.push(type);
          }
        });

        documentAlreadyExists.value = existingDocuments.includes(form.type);
      } else {
        availableDocumentTypes.length = 0;
        availableDocumentTypes.push(...documentTypes);
        documentAlreadyExists.value = false;
      }
    };

    watch(() => form.tramite_id, updateAvailableDocumentTypes);

    const submit = () => {
      if (documentAlreadyExists.value) {
        return; 
      }

      const formData = new FormData();
      formData.append("type", form.type);
      formData.append("file", form.file);
      formData.append("tramite_id", form.tramite_id); // Agregar el ID del trámite al formulario

      Inertia.post(route("documents.store"), formData);
    };

    return {
      form,
      tramites: props.tramites, 
      availableDocumentTypes,
      documentAlreadyExists,
      handleFileUpload,
      submit,
      updateAvailableDocumentTypes,
    };
  },
};
</script>
