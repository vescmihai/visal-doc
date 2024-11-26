<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-4xl font-extrabold text-gray-800">Gestión de Usuarios</h1>
        <Link
          :href="route('users.create')"
          class="btn-primary"
        >
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
              v-for="user in users"
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
                <Link
                  :href="route('users.edit', user.id)"
                  class="btn-secondary"
                >
                  Editar
                </Link>
                <form
                  :action="route('users.destroy', user.id)"
                  method="post"
                  @submit.prevent="destroy(user.id)"
                  class="inline-block"
                >
                  <input type="hidden" name="_method" value="DELETE" />
                  <button
                    type="submit"
                    class="btn-danger"
                  >
                    Eliminar
                  </button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import { Inertia } from "@inertiajs/inertia";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps(["users"]);

const destroy = (id) => {
  if (confirm("¿Estás seguro de eliminar este usuario?")) {
    Inertia.visit(route("users.destroy", id), {
      method: "delete",
      preserveState: true,
    });
  }
};
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
