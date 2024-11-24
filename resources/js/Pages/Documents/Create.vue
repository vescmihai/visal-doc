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
        <!-- Mensaje de error si el tipo de documento ya está registrado -->
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
    tramites: Array, // Recibe los trámites desde el controlador
    documentosRegistrados: Array, // Recibe los documentos ya registrados para cada trámite
  },
  setup(props) {
    const form = reactive({
      type: "",
      file: null,
      tramite_id: null, // Almacena el ID del trámite seleccionado
    });

    // Tipos de documentos predefinidos
    const documentTypes = [
      "Nota de Remisión",
      "Carnet de Identidad",
      "Factura",
      "Precontrato",
      "Póliza simple",
    ];

    // Lista de tipos de documentos disponibles (se actualiza según el trámite seleccionado)
    const availableDocumentTypes = reactive([...documentTypes]);

    // Variable para verificar si el documento ya está registrado
    const documentAlreadyExists = reactive(false);

    // Función para manejar la subida de archivos
    const handleFileUpload = (event) => {
      form.file = event.target.files[0];
    };

    // Función para actualizar los tipos de documentos disponibles según el trámite seleccionado
    const updateAvailableDocumentTypes = () => {
      if (form.tramite_id) {
        // Filtra los documentos ya registrados para el trámite seleccionado
        const existingDocuments = props.documentosRegistrados.filter(
          (doc) => doc.tramite_id === form.tramite_id
        ).map((doc) => doc.type);

        // Elimina los tipos de documentos ya registrados del array de opciones
        availableDocumentTypes.length = 0; // Limpiar la lista de documentos disponibles
        documentTypes.forEach((type) => {
          if (!existingDocuments.includes(type)) {
            availableDocumentTypes.push(type);
          }
        });

        // Verificar si el documento seleccionado ya está registrado para el trámite
        documentAlreadyExists.value = existingDocuments.includes(form.type);
      } else {
        // Si no hay trámite seleccionado, mostrar todos los tipos de documentos
        availableDocumentTypes.length = 0;
        availableDocumentTypes.push(...documentTypes);
        documentAlreadyExists.value = false;
      }
    };

    // Observa el cambio en el trámite y actualiza los tipos de documentos disponibles
    watch(() => form.tramite_id, updateAvailableDocumentTypes);

    // Función para enviar el formulario
    const submit = () => {
      // Verificar si el tipo de documento ya está registrado antes de enviar el formulario
      if (documentAlreadyExists.value) {
        // Si el documento ya está registrado, mostramos un mensaje de error y no enviamos el formulario
        return; // No enviamos el formulario si el documento ya existe
      }

      const formData = new FormData();
      formData.append("type", form.type);
      formData.append("file", form.file);
      formData.append("tramite_id", form.tramite_id); // Agregar el ID del trámite al formulario

      Inertia.post(route("documents.store"), formData);
    };

    return {
      form,
      tramites: props.tramites, // Listado de trámites
      availableDocumentTypes,
      documentAlreadyExists,
      handleFileUpload,
      submit,
      updateAvailableDocumentTypes,
    };
  },
};
</script>
