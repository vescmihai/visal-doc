<template>
  <div class="landing-page">
    <!-- Header Section -->
    <header class="bg-gray-900 text-white py-4 px-8 shadow-md flex items-center justify-between">
      <div class="logo flex items-center space-x-2">
        <img src="/storage/Honda-Logo.png" alt="Visal Import Export S.A." class="h-12" />
        <span class="text-lg font-bold tracking-wide">Visal Import Export S.A.</span>
      </div>
      <nav class="ml-auto">
        <ul class="flex space-x-6 items-center">
          <li v-if="isAuthenticated">
            <a href="/dashboard" class="text-lg font-semibold hover:text-orange-500 transition">Dashboard</a>
          </li>
          <li v-else>
            <a href="/login" class="text-lg font-semibold hover:text-orange-500 transition">Iniciar sesión</a>
          </li>
          <li v-if="!isAuthenticated">
            <a href="/register" class="text-lg font-semibold bg-orange-500 text-white py-2 px-4 rounded-full hover:bg-orange-600 transition">
              Registrarse
            </a>
          </li>
        </ul>
      </nav>
    </header>

    <!-- Hero Section -->
    <section class="relative bg-cover bg-center py-32 px-6" style="background-image: url('/storage/hero-image.jpg');">
      <div class="absolute inset-0 bg-gradient-to-r from-black to-transparent opacity-80"></div>
      <div class="relative z-10 text-left max-w-4xl mx-auto">
        <h1 class="text-5xl md:text-6xl font-extrabold leading-tight text-white mb-6">
          Simplifica <br />
          <span class="text-orange-500">tu trámite</span> con nosotros.
        </h1>
        <p class="text-xl md:text-2xl text-gray-300 mb-8">
          Gestión rápida, segura y sencilla para obtener la placa de tu moto nueva. 
        </p>
        <button
          @click="navigateToForm"
          class="bg-orange-500 hover:bg-orange-600 text-white py-4 px-8 rounded-full text-lg font-bold shadow-lg transition"
        >
          Solicitar trámite ahora
        </button>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-100">
      <div class="max-w-6xl mx-auto text-center">
        <h2 class="text-4xl font-extrabold text-gray-800 mb-12">Nuestros Servicios</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
          <div class="service-item bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition transform hover:-translate-y-2">
            <div class="flex items-center justify-center mb-6">
              <svg class="h-12 w-12 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h11M9 21V3M13 7l6 6-6 6" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">Gestión Rápida de Placas</h3>
            <p class="text-gray-600">Facilitamos el registro de placas para motos nuevas, sin complicaciones.</p>
          </div>
          <div class="service-item bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition transform hover:-translate-y-2">
            <div class="flex items-center justify-center mb-6">
              <svg class="h-12 w-12 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.25 0-2.25 1-2.25 2.25S10.75 12.5 12 12.5s2.25-1 2.25-2.25S13.25 8 12 8zm-6.75 4c0-1.25 1-2.25 2.25-2.25s2.25 1 2.25 2.25-1 2.25-2.25 2.25-2.25-1-2.25-2.25zM18 10c-1.25 0-2.25 1-2.25 2.25S16.75 14.5 18 14.5s2.25-1 2.25-2.25S19.25 10 18 10z" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">Asesoramiento Legal</h3>
            <p class="text-gray-600">Ofrecemos orientación profesional sobre requisitos legales.</p>
          </div>
          <div class="service-item bg-white shadow-lg rounded-lg p-6 hover:shadow-xl transition transform hover:-translate-y-2">
            <div class="flex items-center justify-center mb-6">
              <svg class="h-12 w-12 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M3 10h11M9 21V3" />
              </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800 mb-4">Seguimiento Personalizado</h3>
            <p class="text-gray-600">Te mantenemos informado durante todo el proceso.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-900 text-white">
      <div class="max-w-5xl mx-auto text-center">
        <h2 class="text-4xl font-extrabold mb-6">¿Quiénes Somos?</h2>
        <p class="text-lg text-gray-300 leading-relaxed">
          En <strong>Visal Import Export S.A.</strong>, somos líderes en la gestión de trámites para motocicletas 0km. Nuestra misión es garantizar un proceso rápido y confiable, respaldado por un equipo experto y tecnología de vanguardia.
        </p>
      </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-6">
      <div class="max-w-6xl mx-auto flex items-center justify-between">
        <p>&copy; 2024 Visal Import Export S.A. | Todos los derechos reservados.</p>
        <div class="flex space-x-4">
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-facebook-f"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-twitter"></i></a>
          <a href="#" class="text-gray-400 hover:text-white transition"><i class="fab fa-instagram"></i></a>
        </div>
        <p>
          Esta página fue visitada {{ visits }} {{ visits === 1 ? 'vez' : 'veces' }}.
        </p>
        
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const visits = ref(0); // Contador de visitas
const { props } = usePage();
const isAuthenticated = computed(() => !!props.auth?.user);

// Navegación al formulario
const navigateToForm = () => {
  window.location.href = '/solicitar-tramite';
};

// Actualizar contador de visitas
const updateVisits = async () => {
  try {
    const pageUrl = window.location.pathname; // URL actual
    const response = await axios.post('/page-visit', { page_url: pageUrl }); // Enviar URL al backend
    visits.value = response.data.visits; // Actualizar visitas con la respuesta
  } catch (error) {
    console.error('Error al actualizar visitas:', error);
  }
};

// Llamar al contador al cargar la página
onMounted(() => {
  updateVisits();
});
</script>
