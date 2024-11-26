<template>
    <AuthenticatedLayout>
      <div class="max-w-3xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">
        <h1 class="text-3xl font-semibold text-gray-800 mb-6">Editar Documento</h1>
  
        <form @submit.prevent="submit">
          <div class="mb-4">
            <label for="type" class="block text-gray-700 font-semibold mb-2">Tipo de Documento:</label>
            <select
              id="type"
              v-model="form.type"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required
            >
              <option value="" disabled>Seleccione un tipo de documento</option>
              <option v-for="type in availableDocumentTypes" :key="type" :value="type">
                {{ type }}
              </option>
            </select>
            <p v-if="documentAlreadyExists" class="text-red-500 text-sm mt-2">
              El tipo de documento seleccionado ya ha sido registrado para este trámite.
            </p>
          </div>
  
          <div class="mb-4">
            <label for="file" class="block text-gray-700 font-semibold mb-2">Archivo:</label>
            <input
              id="file"
              type="file"
              @change="handleFileUpload"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
            />
            <p class="text-gray-500 text-sm mt-2">Deje el archivo en blanco si no desea reemplazarlo.</p>
            <p v-if="fileTypeError" class="text-red-500 text-sm mt-2">
              El archivo debe ser una imagen (JPG, JPEG, PNG, GIF) o un PDF.
            </p>
          </div>
  
          <div class="mb-4">
            <label for="tramite_id" class="block text-gray-700 font-semibold mb-2">Trámite Asociado:</label>
            <select
              id="tramite_id"
              v-model="form.tramite_id"
              @change="updateAvailableDocumentTypes"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:outline-none"
              required
            >
              <option value="" disabled>Seleccione un trámite</option>
              <option v-for="tramite in tramites" :key="tramite.id" :value="tramite.id">
                {{ tramite.title }}
              </option>
            </select>
          </div>
  
          <button
            type="submit"
            class="w-full py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 disabled:bg-gray-300 transition duration-200"
            :disabled="documentAlreadyExists || fileTypeError"
          >
            Guardar Cambios
          </button>
        </form>
      </div>
    </AuthenticatedLayout>
  </template>
  
  <script>
  import { reactive, ref, watch } from "vue";
  import { Inertia } from "@inertiajs/inertia";
  import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
  
  export default {
    components: { AuthenticatedLayout },
    props: {
      document: Object, // Documento a editar
      tramites: Array,  // Trámites disponibles
      documentosRegistrados: Array, // Documentos existentes
    },
    setup(props) {
      const form = reactive({
        id: props.document.id,
        type: props.document.type,
        file: null, // Campo para el archivo nuevo
        tramite_id: props.document.tramite_id,
      });
  
      const documentTypes = [
        "Nota de Remisión",
        "Carnet de Identidad",
        "Factura",
        "Precontrato",
        "Póliza simple",
      ];
  
      const availableDocumentTypes = reactive([...documentTypes]);
  
      const documentAlreadyExists = ref(false);
      const fileTypeError = ref(false);
  
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
  
      const updateAvailableDocumentTypes = () => {
        if (form.tramite_id) {
          const existingDocuments = props.documentosRegistrados.filter(
            (doc) => doc.tramite_id === form.tramite_id && doc.id !== form.id
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
  console.log("documentAlreadyExists:", documentAlreadyExists.value);
  console.log("fileTypeError:", fileTypeError.value);
  if (documentAlreadyExists.value || fileTypeError.value) {
    return; 
  }

  const formData = new FormData();
  formData.append("type", form.type);
  formData.append("tramite_id", form.tramite_id);
  if (form.file) {
    formData.append("file", form.file);
  }

  const url = route("documents.update", form.id);
  console.log("Enviando PUT a la URL: ", url);

  Inertia.put(url, formData);
};







  
      return {
        form,
        tramites: props.tramites,
        availableDocumentTypes,
        documentAlreadyExists,
        fileTypeError,
        handleFileUpload,
        submit,
        updateAvailableDocumentTypes,
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
  