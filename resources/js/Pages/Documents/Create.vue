<template>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Crear Nuevo Documento</h1>
  
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="type" class="block text-gray-700 font-bold mb-2">Tipo de Documento:</label>
          <input
            id="type"
            v-model="form.type"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
            placeholder="Ingrese el tipo de documento"
            required
          />
        </div>
  
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
  
        <button
          type="submit"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          Guardar
        </button>
      </form>
    </div>
  </template>
  
  <script>
  import { reactive } from "vue";
  import { Inertia } from "@inertiajs/inertia";
  
  export default {
    setup() {
      const form = reactive({
        type: "",
        file: null,
      });
  
      const handleFileUpload = (event) => {
        form.file = event.target.files[0];
      };
  
      const submit = () => {
        const formData = new FormData();
        formData.append("type", form.type);
        formData.append("file", form.file);
  
        Inertia.post(route("documents.store"), formData);
      };
  
      return {
        form,
        handleFileUpload,
        submit,
      };
    },
  };
  </script>
  