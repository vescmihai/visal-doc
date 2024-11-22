<template>
  <div class="landing-page">
    <!-- Header Section -->
    <header class="header">
      <div class="logo">
        <img src="/storage/Honda-Logo.png" alt="Visal Import Export S.A." />
      </div>
      <nav class="navigation">
        <ul>
          <li><a href="#services">Servicios</a></li>
          <li><a href="#about">Sobre Nosotros</a></li>
          <li><a href="#contact">Contacto</a></li>
        </ul>
      </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-overlay"></div>
      <div class="hero-content">
        <h1>Gestión de Placas para Motocicletas 0km</h1>
        <p>El proceso más rápido, seguro y sencillo para obtener la placa de tu moto nueva.</p>
        <button @click="navigateToForm">Solicita tu trámite ahora</button>
      </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services">
      <h2>Servicios</h2>
      <div class="service-item">
        <h3>Gestión Rápida de Placas</h3>
        <p>Nos encargamos de todo el proceso de registro de placas para motos nuevas, sin complicaciones.</p>
      </div>
      <div class="service-item">
        <h3>Asesoramiento Legal</h3>
        <p>Te proporcionamos toda la información necesaria sobre los requisitos legales para obtener la placa.</p>
      </div>
      <div class="service-item">
        <h3>Seguimiento de Proceso</h3>
        <p>Te mantenemos informado durante todo el proceso con notificaciones en tiempo real.</p>
      </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about">
      <h2>Sobre Visal Import Export S.A.</h2>
      <p>
        En **Visal Import Export S.A.**, nos especializamos en la gestión de trámites de placas para motocicletas 0km en Bolivia. 
        Contamos con un equipo de expertos que te guiarán durante todo el proceso de forma eficiente y segura. 
        Nuestro compromiso es ofrecerte el mejor servicio con una atención personalizada.
      </p>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
      <h2>Contacto</h2>
      <form>
        <label for="name">Nombre</label>
        <input type="text" id="name" placeholder="Tu nombre" required />
        
        <label for="email">Correo electrónico</label>
        <input type="email" id="email" placeholder="Tu correo" required />
        
        <label for="message">Mensaje</label>
        <textarea id="message" placeholder="Tu mensaje" required></textarea>
        
        <button type="submit">Enviar</button>
      </form>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
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
/* Basic Styles for Landing Page */
.landing-page {
  font-family: Arial, sans-serif;
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

.header {
  display: flex;
  justify-content: space-between;
  padding: 20px;
  background-color: #333;
  color: white;
}

.header .logo img {
  width: 120px;
}

.header .navigation ul {
  display: flex;
  list-style: none;
  gap: 20px;
}

.header .navigation ul li a {
  color: white;
  text-decoration: none;
  font-size: 18px;
}

.hero {
  background-image: url('/storage/hero-image.jpg');
  background-size: cover;
  background-position: center;
  color: white;
  text-align: center;
  padding: 100px 20px;
  position: relative;
}

.hero-overlay {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
  z-index: 1;
}

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-content h1 {
  font-size: 3rem;
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7); /* Add shadow for readability */
}

.hero-content p {
  font-size: 1.5rem;
  text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.7);
}

.hero button {
  background-color: #FF5733;
  color: white;
  font-size: 1rem;
  padding: 10px 20px;
  border: none;
  cursor: pointer;
}

.services {
  padding: 60px 20px;
  text-align: center;
}

.service-item {
  margin-bottom: 40px;
}

.about {
  padding: 60px 20px;
  background-color: #f4f4f4;
  text-align: center;
}

.contact {
  padding: 60px 20px;
}

.contact form {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.contact form label {
  font-size: 1rem;
}

.contact form input, 
.contact form textarea {
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
}

.contact form button {
  background-color: #FF5733;
  color: white;
  font-size: 1rem;
  padding: 10px;
  border: none;
  cursor: pointer;
}

.footer {
  padding: 20px;
  background-color: #333;
  color: white;
  text-align: center;
}
</style>
