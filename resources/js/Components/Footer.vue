<template>
  <footer class="bg-white text-gray-600">
    <div class="container mx-auto py-10 px-4 md:px-8">
      <!-- Contenido principal del footer -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Sección de información -->
        <div>
          <h3 class="text-lg font-bold text-gray-800 mb-4">Acerca de</h3>
          <p class="text-sm leading-relaxed">
            Bienvenido a nuestro sitio web. Aquí encontrarás información y servicios para completar tu tramite.
          </p>
        </div>

        <!-- Contador de visitas -->
        <div class="text-center">
          <h3 class="text-lg font-bold text-gray-800 mb-4">Estadísticas</h3>
          <p class="text-2xl font-extrabold text-gray-800">
            {{ visits }}
            <span class="text-sm font-light text-gray-600">
              {{ visits === 1 ? 'vez visitada' : 'visitas registradas' }}
            </span>
          </p>
        </div>

        <!-- Enlaces rápidos -->
        <div>
          <h3 class="text-lg font-bold text-gray-800 mb-4">Enlaces rápidos</h3>
          <ul class="space-y-2">
            <li><a href="/about" class="hover:text-blue-500 transition">Acerca de nosotros</a></li>
            <li><a href="/services" class="hover:text-blue-500 transition">Servicios</a></li>
            <li><a href="/contact" class="hover:text-blue-500 transition">Contáctanos</a></li>
            <li><a href="/faq" class="hover:text-blue-500 transition">Preguntas frecuentes</a></li>
          </ul>
        </div>
      </div>

      <!-- Separador -->
      <div class="my-8 border-t border-gray-300"></div>

      <!-- Redes sociales y copyright -->
      <div class="flex flex-col md:flex-row items-center justify-between">
        <!-- Redes sociales -->
        <div class="flex space-x-4 mb-4 md:mb-0">
          <a href="https://facebook.com" target="_blank" class="hover:text-blue-500 transition">
            <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M22.675 0h-21.35C.603 0 0 .603 0 1.351v21.299C0 23.398.603 24 1.351 24H12.82V14.709H9.692v-3.647h3.128V8.398c0-3.1 1.891-4.788 4.655-4.788 1.325 0 2.465.099 2.796.143v3.24h-1.918c-1.505 0-1.796.717-1.796 1.764v2.311h3.586l-.467 3.647h-3.119V24h6.116c.749 0 1.351-.602 1.351-1.35V1.351C24 .603 23.398 0 22.675 0z" />
            </svg>
          </a>
          <a href="https://twitter.com" target="_blank" class="hover:text-blue-500 transition">
            <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M23.954 4.569c-.885.385-1.83.644-2.825.762 1.014-.611 1.794-1.574 2.163-2.723-.949.564-2.007.974-3.127 1.197-.897-.961-2.173-1.558-3.592-1.558-2.719 0-4.924 2.208-4.924 4.928 0 .386.045.762.127 1.124-4.092-.205-7.719-2.165-10.148-5.144-.425.729-.666 1.573-.666 2.476 0 1.71.871 3.216 2.191 4.096-.807-.025-1.566-.248-2.231-.616v.063c0 2.387 1.693 4.381 3.946 4.828-.413.111-.849.171-1.296.171-.315 0-.623-.03-.923-.086.623 1.956 2.432 3.379 4.574 3.418-1.68 1.31-3.8 2.096-6.104 2.096-.395 0-.779-.022-1.162-.067 2.179 1.397 4.768 2.213 7.548 2.213 9.054 0 13.999-7.496 13.999-13.986 0-.213 0-.425-.015-.637.961-.694 1.8-1.562 2.462-2.549z" />
            </svg>
          </a>
          <a href="https://linkedin.com" target="_blank" class="hover:text-blue-500 transition">
            <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
              <path d="M22.225 0H1.771C.792 0 0 .771 0 1.729V22.27c0 .959.792 1.73 1.771 1.73h20.451c.978 0 1.771-.771 1.771-1.73V1.729C23.995.771 23.203 0 22.225 0zM7.072 20.452H3.746V8.456h3.326v11.996zm-1.648-13.68c-1.07 0-1.938-.876-1.938-1.953 0-1.076.868-1.953 1.938-1.953s1.939.876 1.939 1.953c-.001 1.077-.869 1.953-1.939 1.953zM20.451 20.452h-3.328v-5.803c0-1.384-.029-3.165-1.93-3.165-1.933 0-2.23 1.511-2.23 3.068v5.9H9.647V8.456h3.203v1.646h.047c.445-.84 1.535-1.725 3.16-1.725 3.38 0 4.005 2.225 4.005 5.115v6.96z" />
            </svg>
          </a>
        </div>

        <!-- Nota de copyright -->
        <p class="text-sm text-gray-500">&copy; {{ new Date().getFullYear() }} Visal Import Export S.A. Todos los derechos reservados.</p>
      </div>
    </div>
  </footer>
</template>





<script>
import axios from "axios";

export default {
  data() {
    return {
      visits: 0,
    };
  },
  mounted() {
    this.updateVisits();
  },
  methods: {
    async updateVisits() {
      try {
        const pageUrl = window.location.pathname; // Obtener la URL de la página actual
        const response = await axios.post("/page-visit", { page_url: pageUrl }); // Enviar la URL actual al backend
        this.visits = response.data.visits; // Actualizar el contador de visitas
      } catch (error) {
        console.error("Error al actualizar visitas:", error);
      }
    },
  },
};
</script>

<style scoped>
footer {
  background-color: #f8f9fa;
  color: #333;
  font-size: 1rem;
}
</style>
