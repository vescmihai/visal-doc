<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Documentos</h1>
        <Link href="/documents/create" class="btn-primary">+ Crear Documento</Link>
      </div>

      <div v-if="documents.length" class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full border-collapse text-left text-sm">
          <thead class="bg-gray-200 text-gray-700">
            <tr>
              <th class="px-6 py-3">Usuario</th>
              <th class="px-6 py-3">Tipo</th>
              <th class="px-6 py-3">Trámite</th>
              <th class="px-6 py-3">Estado</th>
              <th class="px-6 py-3">Observación</th>
              <th class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="doc in documents"
              :key="doc.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 text-gray-800">{{ doc.user.name }}</td>
              <td class="px-6 py-4 text-gray-800">{{ doc.type }}</td>
              <td class="px-6 py-4 text-gray-800">{{ doc.tramite ? doc.tramite.title : 'Sin trámite' }}</td>
              <td class="px-6 py-4">
                <span
                  class="inline-block px-3 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-600': doc.status === 'Aprobado',
                    'bg-red-100 text-red-600': doc.status === 'Rechazado',
                    'bg-yellow-100 text-yellow-600': doc.status === 'Pendiente',
                  }"
                >
                  {{ doc.status || 'Desconocido' }}
                </span>
              </td>
              <td class="px-6 py-4">
                <input
                  v-model="doc.observation"
                  placeholder="Añadir observación"
                  class="w-full px-3 py-2 border rounded-lg text-sm focus:ring focus:ring-blue-200"
                />
              </td>
              <td class="px-6 py-4 flex gap-2 justify-center">
                <button
                  @click="viewDocument(doc)"
                  class="btn-blue"
                >
                  Ver
                </button>
                <button
                  @click="updateStatus(doc, 'Aprobado')"
                  class="btn-success"
                >
                  Aprobar
                </button>
                <button
                  @click="updateStatus(doc, 'Rechazado')"
                  class="btn-danger"
                >
                  Rechazar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <p v-else class="text-center text-gray-500 text-lg">
        No hay documentos disponibles.
      </p>

      <div v-if="isModalOpen" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg w-3/4 md:w-1/2 relative">
          <button @click="closeModal" class="absolute top-2 right-2 text-xl text-gray-500 hover:text-gray-800">
            &times;
          </button>
          <h2 class="text-2xl font-bold mb-4 text-gray-800">Vista previa del documento</h2>
          <div v-if="fileType === 'image'">
            <img :src="fileUrl" alt="Documento" class="w-full h-auto max-h-[80vh] object-contain rounded-lg" />
          </div>
          <div v-else-if="fileType === 'pdf'">
            <iframe :src="fileUrl" class="w-full h-96 rounded-lg" frameborder="0"></iframe>
          </div>
          <div v-else>
            <p class="text-gray-500">No se puede previsualizar este tipo de archivo.</p>
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
import axios from "axios";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
  components: { Link, AuthenticatedLayout },
  setup() {
    const { props } = usePage();
    const documents = ref(props.documents || []);
    const isModalOpen = ref(false);
    const fileUrl = ref("");
    const fileType = ref("");

    const viewDocument = (doc) => {
      const url = `/storage/${doc.file_path}`;
      fileUrl.value = url;

      const extension = doc.file_path.split(".").pop().toLowerCase();
      fileType.value = ["jpg", "jpeg", "png", "gif"].includes(extension) ? "image" : extension === "pdf" ? "pdf" : "";

      isModalOpen.value = true;
    };

    const closeModal = () => {
      isModalOpen.value = false;
    };

    const updateStatus = (doc, status) => {
      axios
        .put(`/documents/${doc.id}`, { status, observation: doc.observation || "" })
        .then(() => {
          doc.status = status;
        })
        .catch((error) => {
          console.error("Error al actualizar el estado:", error);
        });
    };

    return { documents, updateStatus, viewDocument, closeModal, isModalOpen, fileUrl, fileType };
  },
};
</script>

<style scoped>
/* Botones */
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

.btn-blue {
  background-color: #3b82f6;
  color: white;
  padding: 0.5rem 1rem;
  font-weight: 600;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
}
.btn-blue:hover {
  background-color: #2563eb;
}

.btn-success {
  background-color: #34d399;
  color: white;
  padding: 0.5rem 1rem;
  font-weight: 600;
  border-radius: 0.5rem;
  transition: all 0.3s ease;
}
.btn-success:hover {
  background-color: #10b981;
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
