<template>
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-8 bg-white shadow-lg rounded-lg mt-10">
      <h1 class="text-3xl font-semibold text-gray-800 mb-6">Editar Usuario</h1>
      
      <form @submit.prevent="submit" class="space-y-6">
        
        <div class="flex flex-col">
          <label for="name" class="text-gray-700 font-medium mb-2">Nombre:</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el nombre del usuario"
          />
        </div>
        
        <div class="flex flex-col">
          <label for="email" class="text-gray-700 font-medium mb-2">Email:</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el email del usuario"
          />
        </div>
        
        <div class="flex flex-col">
          <label for="role" class="text-gray-700 font-medium mb-2">Rol:</label>
          <select
            id="role"
            v-model="form.role"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
          >
            <option value="cliente">Cliente</option>
            <option value="gestor">Gestor</option>
            <option value="admin">Administrador</option>
          </select>
        </div>
  
        <div>
          <button
            type="submit"
            class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
          >
            Actualizar
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
  import { useForm } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  const props = defineProps(['user']);

  const form = useForm({
    ...props.user,
  });

  const submit = () => {
    form.put(route('users.update', props.user.id));
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
