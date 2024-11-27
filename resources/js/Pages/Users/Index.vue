<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Usuarios</h1>
        <Link :href="route('users.create')" class="btn-primary">
          + Registrar Usuario
        </Link>
      </div>

      <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="w-full border-collapse text-left text-sm text-gray-700">
          <thead>
            <tr class="bg-gray-100 text-gray-600 uppercase text-xs tracking-wider">
              <th class="px-6 py-3">Nombre</th>
              <th class="px-6 py-3">Email</th>
              <th class="px-6 py-3">Rol</th>
              <th class="px-6 py-3 text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="user in users.data"
              :key="user.id"
              class="hover:bg-gray-50 transition"
            >
              <td class="px-6 py-4 font-medium text-gray-800">{{ user.name }}</td>
              <td class="px-6 py-4">{{ user.email }}</td>
              <td class="px-6 py-4 capitalize">
                <span
                  class="inline-block px-2 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-600': user.role === 'admin',
                    'bg-blue-100 text-blue-600': user.role === 'gestor',
                    'bg-gray-100 text-gray-600': user.role === 'cliente'
                  }"
                >
                  {{ user.role }}
                </span>
              </td>
              <td class="px-6 py-4 flex justify-center gap-4">
                <Link :href="route('users.edit', user.id)" class="btn-secondary">
                  Editar
                </Link>
                <form
                  :action="route('users.destroy', user.id)"
                  method="post"
                  @submit.prevent="destroy(user.id)"
                  class="inline-block"
                >
                  <input type="hidden" name="_method" value="DELETE" />
                  <button type="submit" class="btn-danger">Eliminar</button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>

        <!-- Paginación -->
        <div class="mt-6 flex justify-center items-center space-x-2">
          <!-- Botón anterior -->
          <button
            v-if="users.prev_page_url"
            @click="changePage(users.prev_page_url)"
            class="px-4 py-2 rounded border border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200"
          >
            Anterior
          </button>

          <!-- Botones numerados -->
          <button
            v-for="page in visiblePages"
            :key="page.url"
            @click="changePage(page.url)"
            :class="{
              'bg-blue-500 text-white': page.active,
              'bg-gray-100 text-gray-600 hover:bg-gray-200': !page.active
            }"
            class="px-4 py-2 rounded border border-gray-300"
            :disabled="!page.url"
          >
            {{ page.label }}
          </button>

          <!-- Botón siguiente -->
          <button
            v-if="users.next_page_url"
            @click="changePage(users.next_page_url)"
            class="px-4 py-2 rounded border border-gray-300 bg-gray-100 text-gray-600 hover:bg-gray-200"
          >
            Siguiente
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>



<script setup>
import { computed } from "vue"; // Importar computed
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

// Recibir la prop 'users' desde el backend
const props = defineProps(["users"]); // Recibir usuarios con paginación

// Función para eliminar un usuario
const destroy = (id) => {
  if (confirm("¿Estás seguro de eliminar este usuario?")) {
    Inertia.visit(route("users.destroy", id), {
      method: "delete",
      preserveState: true,
    });
  }
};

// Función para cambiar de página
const changePage = (url) => {
  if (url) {
    Inertia.get(url);
  }
};

// Calcular las páginas visibles (máximo 3 botones)
const visiblePages = computed(() => {
  const currentPage = props.users.current_page;
  const lastPage = props.users.last_page;

  let startPage = Math.max(currentPage - 1, 1);
  let endPage = Math.min(currentPage + 1, lastPage);

  // Ajustar rango de 3 páginas
  if (currentPage === 1) {
    endPage = Math.min(startPage + 2, lastPage);
  } else if (currentPage === lastPage) {
    startPage = Math.max(endPage - 2, 1);
  }

  // Filtrar enlaces de paginación según rango
  return props.users.links.filter(
    (link) =>
      link.label !== "&laquo; Previous" &&
      link.label !== "Next &raquo;" &&
      Number(link.label) >= startPage &&
      Number(link.label) <= endPage
  );
});
</script>




<style scoped>
.btn-primary {
  background-color: #2563eb; 
  color: white;
  font-size: 0.875rem; 
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem; 
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
}

.btn-primary:hover {
  background-color: #1d4ed8; 
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.btn-secondary {
  background-color: #34d399; 
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
}

.btn-secondary:hover {
  background-color: #10b981; 
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}

.btn-danger {
  background-color: #f87171; 
  color: white;
  font-size: 0.875rem;
  font-weight: 600;
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  transition: all 0.3s ease-in-out;
}

.btn-danger:hover {
  background-color: #ef4444; 
  box-shadow: 0 6px 10px rgba(0, 0, 0, 0.15);
}
</style> 
