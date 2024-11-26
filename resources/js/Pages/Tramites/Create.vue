<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-100 min-h-screen">
      <h1 class="text-3xl font-bold mb-6 text-gray-800">Creando Trámite...</h1>
      <p class="text-gray-700">El título se está generando automáticamente...</p>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import { ref, onMounted } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';  

export default {
  components: {
    AuthenticatedLayout,  
  },
  setup() {
    const generateRandomTitle = () => {
      const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
      let randomTitle = '';
      for (let i = 0; i < 4; i++) {
        randomTitle += characters.charAt(Math.floor(Math.random() * characters.length));
      }
      return randomTitle;
    };

    onMounted(() => {
      const title = generateRandomTitle();

      const formData = { title };

      Inertia.post(route('tramites.store'), formData);
    });

    return {};
  },
};
</script>
