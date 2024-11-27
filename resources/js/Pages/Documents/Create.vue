<template>
  <AuthenticatedLayout>
    <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
      <h1 class="text-3xl font-semibold text-gray-800 mb-6">Crear Nuevo Documento</h1>

      <form @submit.prevent="submit">
        <!-- Campo para seleccionar el tipo de documento -->
        <div class="mb-4">
          <label for="type" class="block text-gray-700 font-semibold mb-2">Tipo de Documento:</label>
          <select
            id="type"
            v-model="form.type"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
            required
          >
            <option value="" disabled>Seleccione un tipo de documento</option>
            <option v-for="type in documentTypes" :key="type" :value="type">
              {{ type }}
            </option>
          </select>
        </div>

        <!-- Campo para cargar el archivo -->
        <div class="mb-4">
          <label for="file" class="block text-gray-700 font-semibold mb-2">Archivo:</label>
          <input
            id="file"
            type="file"
            @change="handleFileUpload"
            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
            required
          />
          <p v-if="fileTypeError" class="text-red-500 text-sm mt-2">
            El archivo debe ser una imagen (JPG, JPEG, PNG, GIF) o un PDF.
          </p>
        </div>

        <!-- Botón para enviar el formulario -->
        <button
          type="submit"
          class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:bg-gray-300 transition duration-200"
          :disabled="fileTypeError || !form.type || !form.file"
        >
          Guardar
        </button>
      </form>
    </div>
  </AuthenticatedLayout>
</template>


<script>
import { ref, reactive } from "vue";
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: { AuthenticatedLayout },
  props: {
    tramiteId: Number, // Tramite asociado pasado desde el backend.
  },
  setup(props) {
    // Inicializar el formulario
    const form = reactive({
      type: "",
      file: null,
      tramite_id: props.tramiteId || null, // Asignar el trámite automáticamente desde las props
    });

    // Tipos de documentos disponibles
    const documentTypes = [
      "Nota de Remisión",
      "Carnet de Identidad",
      "Factura",
      "Precontrato",
      "Póliza simple",
    ];

    // Estado para validar el tipo de archivo
    const fileTypeError = ref(false);

    // Función que maneja la carga de archivos
    const handleFileUpload = (event) => {
      const file = event.target.files[0];
      if (file) {
        const fileExtension = file.name.split(".").pop().toLowerCase();
        if (!["jpg", "jpeg", "png", "gif", "pdf"].includes(fileExtension)) {
          fileTypeError.value = true;
          form.file = null;
        } else {
          fileTypeError.value = false;
          form.file = file;
        }
      }
    };

    // Enviar el formulario
    const submit = () => {
      if (fileTypeError.value || !form.type || !form.file) {
        return; // No envía el formulario si hay errores
      }

      const formData = new FormData();
      formData.append("type", form.type);
      formData.append("file", form.file);
      formData.append("tramite_id", form.tramite_id);

      Inertia.post(route("documents.store"), formData)
        .then(response => {
          console.log("Documento enviado con éxito:", response);
        })
        .catch(error => {
          console.error("Error al guardar el documento:", error);
        });
    };

    return {
      form,
      documentTypes,
      fileTypeError,
      handleFileUpload,
      submit,
    };
  },
};
</script>


<style scoped>
input, select {
  transition: all 0.3s ease;
}

input:focus, select:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
}
</style>
