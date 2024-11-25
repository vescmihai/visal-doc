<template>
  <AuthenticatedLayout>
  <div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Gestión de Documentos</h1>

    <!-- Botón para crear un nuevo documento -->
    <div class="mb-4">
      <Link
        :href="route('documents.create')"
        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
      >
        Crear nuevo documento
      </Link>
    </div>

    <!-- Tabla de documentos -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
      <table class="w-full table-auto border-collapse border border-gray-200">
        <thead>
          <tr class="bg-gray-200 text-gray-700">
            <th class="border border-gray-300 px-4 py-2">Usuario</th>
            <th class="border border-gray-300 px-4 py-2">Tipo</th>
            <th class="border border-gray-300 px-4 py-2">Trámite</th> <!-- Nueva columna para el trámite -->
            <th class="border border-gray-300 px-4 py-2">Estado</th>
            <th class="border border-gray-300 px-4 py-2">Observación</th>
            <th class="border border-gray-300 px-4 py-2">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="doc in documents" :key="doc.id" class="text-gray-700 hover:bg-gray-100">
            <td class="border border-gray-300 px-4 py-2">{{ doc.user.name }}</td> <!-- Mostrar el nombre del usuario -->
            <td class="border border-gray-300 px-4 py-2">{{ doc.type }}</td>
            <!-- Mostrar el título del trámite asociado -->
            <td class="border border-gray-300 px-4 py-2">{{ doc.tramite ? doc.tramite.title : 'Sin trámite' }}</td>
            <td class="border border-gray-300 px-4 py-2">{{ doc.status }}</td>
            <td class="border border-gray-300 px-4 py-2">
              <!-- Input de observación siempre habilitado -->
              <input
                v-model="doc.observation"
                placeholder="Añadir observación"
                class="w-full px-2 py-1 border border-gray-300 rounded"
              />
            </td>
            <td class="border border-gray-300 px-4 py-2 flex items-center gap-2">
              <button
                @click="viewDocument(doc)"
                class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
              >
                Ver
              </button>
              <!-- Botones para actualizar estado -->
              <button
                @click="updateStatus(doc, 'Aprobado')"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
              >
                Aprobar
              </button>
              <button
                @click="updateStatus(doc, 'Rechazado')"
                class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
              >
                Rechazar
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal para previsualizar el documento -->
    <div v-if="isModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
      <div class="bg-white p-6 rounded-lg w-3/4 md:w-1/2 relative">
        <button @click="closeModal" class="absolute top-2 right-2 text-xl text-gray-500">
          &times;
        </button>
        <h2 class="text-2xl mb-4">Vista previa del documento</h2>

        <!-- Mostrar el documento -->
        <div v-if="fileType === 'image'">
          <img :src="fileUrl" alt="Documento" class="w-full h-auto" />
        </div>
        <div v-else-if="fileType === 'pdf'">
          <iframe :src="fileUrl" class="w-full h-96" frameborder="0"></iframe>
        </div>
        <div v-else>
          <p>No se puede previsualizar este tipo de archivo.</p>
        </div>
      </div>
    </div>
  </div>
</AuthenticatedLayout>
</template>

<script>
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import axios from "axios";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

export default {
  components: {
    Link,
    AuthenticatedLayout,
  },
  setup() {
    const { props } = usePage();
    const documents = ref(props.documents);
    const isModalOpen = ref(false);
    const fileUrl = ref('');
    const fileType = ref('');

    // Abrir el modal con el documento
    const viewDocument = (doc) => {
      const url = `/storage/${doc.file_path}`;
      fileUrl.value = url;

      // Determinar el tipo de archivo
      const extension = doc.file_path.split('.').pop().toLowerCase();
      if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        fileType.value = 'image';
      } else if (['pdf'].includes(extension)) {
        fileType.value = 'pdf';
      } else {
        fileType.value = ''; // Si no es una imagen ni un PDF
      }

      isModalOpen.value = true;
    };

    // Cerrar el modal
    const closeModal = () => {
      isModalOpen.value = false;
    };

    // Actualizar el estado de un documento
    const updateStatus = (doc, status) => {
      const data = {
        status: status, 
        observation: doc.observation || ""  
      };

      axios
        .put(`/documents/${doc.id}`, data)  
        .then((response) => {
          console.log(response.data); 
          doc.status = status;  
        })
        .catch((error) => {
          console.error("Error al actualizar el estado:", error.response.data); 
        });
    };

    return { documents, updateStatus, viewDocument, closeModal, isModalOpen, fileUrl, fileType };
  },
};
</script>
