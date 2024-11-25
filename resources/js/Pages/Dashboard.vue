<template>
    <div class="landing-page">
      <!-- Header Section -->
      <header class="header bg-gray-900 text-white py-4 px-8 shadow-md flex items-center justify-between">
        <div class="logo">
          <img src="/storage/Honda-Logo.png" alt="Visal Import Export S.A." class="h-12"/>
        </div>
        <nav class="navigation ml-auto">
          <ul class="flex space-x-6">
            <li><a href="login" class="text-lg font-semibold hover:text-orange-500 transition">Iniciar sesión</a></li>
            <li><a href="register" class="text-lg font-semibold hover:text-orange-500 transition">Registrarse</a></li>
            <li><a href="profile" class="text-lg font-semibold hover:text-orange-500 transition">Perfil</a></li>
          </ul>
        </nav>
      </header>
  
      <!-- Hero Section -->
      <section class="hero relative bg-cover bg-center text-white py-32 px-6" style="background-image: url('/storage/hero-image.jpg');">
        <div class="hero-overlay absolute inset-0 bg-black opacity-50"></div>
        <div class="hero-content relative z-10 text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">Gestión de Placas para Motocicletas 0km</h1>
          <p class="text-xl md:text-2xl mb-6">El proceso más rápido, seguro y sencillo para obtener la placa de tu moto nueva.</p>
          <button @click="navigateToForm" class="bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-full text-lg font-semibold transition">Solicita tu trámite ahora</button>
        </div>
      </section>
  
      <!-- Services Section -->
      <section id="services" class="services py-20 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-12">Servicios</h2>
        <div class="service-items grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12">
          <div class="service-item p-6 bg-white shadow-lg rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Gestión Rápida de Placas</h3>
            <p>Nos encargamos de todo el proceso de registro de placas para motos nuevas, sin complicaciones.</p>
          </div>
          <div class="service-item p-6 bg-white shadow-lg rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Asesoramiento Legal</h3>
            <p>Te proporcionamos toda la información necesaria sobre los requisitos legales para obtener la placa.</p>
          </div>
          <div class="service-item p-6 bg-white shadow-lg rounded-lg">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Seguimiento de Proceso</h3>
            <p>Te mantenemos informado durante todo el proceso con notificaciones en tiempo real.</p>
          </div>
        </div>
      </section>
  
      <!-- About Section -->
      <section id="about" class="about py-20 bg-gray-100 text-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-6">Sobre Visal Import Export S.A.</h2>
        <p class="text-lg text-gray-600 max-w-3xl mx-auto">
          En <strong>Visal Import Export S.A.</strong>, nos especializamos en la gestión de trámites de placas para motocicletas 0km en Bolivia. Contamos con un equipo de expertos que te guiarán durante todo el proceso de forma eficiente y segura. Nuestro compromiso es ofrecerte el mejor servicio con una atención personalizada.
        </p>
      </section>
  
      <!-- Contact Section -->
      <section id="contact" class="contact py-20">
        <h2 class="text-3xl font-bold text-gray-800 text-center mb-8">Contacto</h2>
        <div class="max-w-3xl mx-auto bg-white shadow-lg rounded-lg p-8">
          <form class="space-y-6">
            <div class="flex flex-col">
              <label for="name" class="text-gray-700 font-semibold">Nombre</label>
              <input type="text" id="name" placeholder="Tu nombre" class="py-3 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"/>
            </div>
            <div class="flex flex-col">
              <label for="email" class="text-gray-700 font-semibold">Correo electrónico</label>
              <input type="email" id="email" placeholder="Tu correo" class="py-3 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"/>
            </div>
            <div class="flex flex-col">
              <label for="message" class="text-gray-700 font-semibold">Mensaje</label>
              <textarea id="message" placeholder="Tu mensaje" class="py-3 px-4 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-3 px-6 rounded-lg text-lg font-semibold transition">Enviar</button>
          </form>
        </div>
      </section>
  
      <!-- Footer Section -->
      <footer class="footer bg-gray-900 text-white py-6 text-center">
        <p>&copy; 2024 Visal Import Export S.A. | Todos los derechos reservados.</p>
      </footer>
    </div>
  </template>
  
  <script setup>
    import { ref } from 'vue';
  
    const navigateToForm = () => {
      window.location.href = '/solicitar-tramite';
    };
  </script>
  
  <style scoped>
    /* Tailwind CSS is used for styling, no additional styles are needed here */
  </style>
  