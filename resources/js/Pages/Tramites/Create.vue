<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Crear Nuevo Trámite</h1>
      
      <form @submit.prevent="submit">
        <div class="mb-4">
          <label for="title" class="block text-gray-700">Título</label>
          <input
            v-model="form.title"
            id="title"
            type="text"
            class="w-full px-4 py-2 border border-gray-300 rounded"
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
  </AuthenticatedLayout>
</template>

<script>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';  

export default {
  components: {
    AuthenticatedLayout,  
  },
  setup() {
    const form = ref({ title: '' });

    const submit = () => {
      Inertia.post(route('tramites.store'), form.value);
    };

    return { form, submit };
  },
};

</script>
