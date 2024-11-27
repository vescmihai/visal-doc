<template>
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-8 bg-white shadow-lg rounded-lg mt-10">
      <!-- Mensaje de éxito -->
      <div v-if="successMessage" class="bg-green-100 text-green-800 px-4 py-2 rounded mb-6">
        {{ successMessage }}
      </div>


      <h1 class="text-3xl font-semibold text-gray-800 mb-6">Registrar Usuario</h1>

      <form @submit.prevent="submit" class="space-y-6">
        <!-- Campo Nombre -->
        <div class="flex flex-col">
          <label for="name" class="text-gray-700 font-medium mb-2">Nombre:</label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el nombre del usuario"
          />
          <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
        </div>

        <!-- Campo Email -->
        <div class="flex flex-col">
          <label for="email" class="text-gray-700 font-medium mb-2">Email:</label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el email del usuario"
          />
          <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
        </div>

        <!-- Campo Contraseña -->
        <div class="flex flex-col">
          <label for="password" class="text-gray-700 font-medium mb-2">Contraseña:</label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese una contraseña"
          />
          <p v-if="form.errors.password" class="text-red-500 text-sm mt-1">{{ form.errors.password }}</p>
        </div>

        <!-- Campo Rol -->
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
          <p v-if="form.errors.role" class="text-red-500 text-sm mt-1">{{ form.errors.role }}</p>
        </div>

        <!-- Botón Guardar -->
        <div>
          <button
            type="submit"
            class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
          >
            Guardar
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { usePage } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const { props } = usePage();
console.log(props);
const successMessage = props.flash?.success || null;


const form = useForm({
  name: '',
  email: '',
  password: '',
  role: 'cliente',
});

const submit = () => {
  form.post(route('users.store'), {
    onSuccess: () => {
      // Resetear el formulario en caso de éxito
      form.reset();
    },
  });
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


<style scoped>
input, select {
  transition: all 0.3s ease;
}
input:focus, select:focus {
  border-color: #2563eb; 
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
}
</style>
