<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Gestión de Usuarios</h1>
      <div class="overflow-x-auto bg-white shadow-md rounded-lg p-4">
        <table class="w-full table-auto border-collapse border border-gray-200">
          <thead>
            <tr class="bg-gray-200 text-gray-700">
              <th class="border border-gray-300 px-4 py-2">Nombre</th>
              <th class="border border-gray-300 px-4 py-2">Email</th>
              <th class="border border-gray-300 px-4 py-2">Rol</th>
              <th class="border border-gray-300 px-4 py-2">Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="text-gray-700 hover:bg-gray-100">
              <td class="border border-gray-300 px-4 py-2">{{ user.name }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ user.email }}</td>
              <td class="border border-gray-300 px-4 py-2 capitalize">{{ user.role }}</td>
              <td class="border border-gray-300 px-4 py-2 flex items-center gap-2">
                <!-- Enlace para Editar -->
                <Link
                  :href="route('users.edit', user.id)"
                  class="text-blue-500 hover:underline"
                >
                  Editar
                </Link>
  
                <!-- Formulario para Eliminar -->
                <form
                  :action="route('users.destroy', user.id)"
                  method="post"
                  @submit.prevent="destroy(user.id)"
                  class="inline-block"
                >
                  <input type="hidden" name="_method" value="DELETE" />
                  <button
                    type="submit"
                    class="text-red-500 hover:underline"
                  >
                    Eliminar
                  </button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="mt-6">
        <!-- Enlace para Crear nuevo Usuario -->
        <Link
          :href="route('users.create')"
          class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
          Crear nuevo usuario
        </Link>
      </div>
    </div>
  </AuthenticatedLayout>
  </template>
  
  <script setup>
    import { Link } from '@inertiajs/vue3'; // Asegúrate de importar Link de Inertia.js
    import { Inertia } from '@inertiajs/inertia'; // Importar Inertia explícitamente
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    
    const props = defineProps(['users']);
    
    const destroy = (id) => {
      if (confirm('¿Estás seguro de eliminar este usuario?')) {
        // Realizar la solicitud DELETE utilizando la redirección de Inertia
        Inertia.visit(route('users.destroy', id), {
          method: 'delete',
          preserveState: true, // Si no deseas cambiar el estado de la página
        });
      }
    };
  </script>
  