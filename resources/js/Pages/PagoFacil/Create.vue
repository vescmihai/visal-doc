<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';

const page = usePage();
const qrImage = ref('');
const numeroTransaccion = ref('');
const modalMessageQr = ref('');
const modalMessageSuccess = ref('');
const isModalVisibleQr = ref(false);
const isModalVisibleSuccess = ref(false);
const placa = ref(page.props.placa);
const props = defineProps({
  numeroTransaccion: String,
  qrImage: String,
});

qrImage.value = props.qrImage;

const form = useForm({
  name: '',
  cedula: '',
  precio: '',
  tnTelefono: '',
  tcRazonSocial: 'GRUPO-01',
  placa: placa.value.placa,
  tcCorreo: '',
  tcCiNit: '',
  tnMonto: 0.01,
  taPedidoDetalle: [],
  tnTipoServicio: '',
});

const formPago = useForm({
  pago_id: '',
});

const submitForm = () => {
  axios.post(route('pagofacil.generar'), form)
    .then(response => {
      qrImage.value = response.data.qrImage || ''; // Asignación segura
      numeroTransaccion.value = response.data.nroTransaccion || ''; // Asignación segura
      formPago.pago_id = numeroTransaccion.value;
      showModalQr('¡QR Generado con éxito!');
      consultarTransaccion(numeroTransaccion.value);
    })
    .catch(error => {
      console.log('Hubo un error al crear la venta', error);
    });
};

const consultarTransaccion = (nroTransaccion) => {
  const intervalID = setInterval(async () => {
    try {
      const response = await axios.post(route('pagofacil.consultar'), {
        pago_id: nroTransaccion,
      });

      if (response.data.texto.trim() === "COMPLETADO-PROCESADO") {
        clearInterval(intervalID); // Detener el intervalo
        showModalSuccess("¡Su pedido fue pagado con éxito!");
      }
    } catch (error) {
      console.error('Error al consultar la transacción:', error);
      clearInterval(intervalID); // Detener el intervalo en caso de error
    }
  }, 10000); // Reintentar cada 10 segundos
};

function showModalQr(mensaje) {
  isModalVisibleSuccess.value = false;
  modalMessageQr.value = mensaje;
  isModalVisibleQr.value = true;
}

function showModalSuccess(mensaje) {
  isModalVisibleQr.value = false;
  modalMessageSuccess.value = mensaje;
  isModalVisibleSuccess.value = true;
}

function closeModalQr() {
  isModalVisibleQr.value = false;
}

function closeModalSuccess() {
  isModalVisibleSuccess.value = false;
}
</script>

<template>
  <AuthenticatedLayout>
    <!-- Modal de QR -->
    <div v-if="isModalVisibleQr" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <p class="text-xl font-semibold text-gray-800 mb-4">{{ modalMessageQr }}</p>
        <img v-if="qrImage" :src="qrImage" alt="QR Code" class="my-4 w-96 h-96 mx-auto" />
        <button @click="closeModalQr" class="bg-blue-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-blue-700 transition">Cerrar</button>
      </div>
    </div>

    <!-- Modal de éxito -->
    <div v-if="isModalVisibleSuccess" class="fixed inset-0 flex justify-center items-center bg-black bg-opacity-50 z-50">
      <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <p class="text-xl font-semibold text-gray-800 mb-4">{{ modalMessageSuccess }}</p>
        <button @click="closeModalSuccess" class="bg-green-600 text-white px-6 py-2 rounded-lg shadow-md hover:bg-green-700 transition">Cerrar</button>
      </div>
    </div>

    <!-- Contenido Principal -->
    <div class="max-w-2xl mx-auto p-8 bg-white shadow-lg rounded-lg mt-10">
      <h1 class="text-3xl font-semibold text-gray-800 mb-6">Registrar Pago</h1>
      <form @submit.prevent="submitForm" class="space-y-6">
        
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
          <label for="cedula" class="text-gray-700 font-medium mb-2">Cédula:</label>
          <input
            id="cedula"
            v-model="form.cedula"
            type="text"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese la cédula"
          />
        </div>

        <div class="flex flex-col">
          <label for="tcCorreo" class="text-gray-700 font-medium mb-2">Correo:</label>
          <input
            id="tcCorreo"
            v-model="form.tcCorreo"
            type="email"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el correo"
          />
        </div>

        <div class="flex flex-col">
          <label for="tnTelefono" class="text-gray-700 font-medium mb-2">Teléfono:</label>
          <input
            id="tnTelefono"
            v-model="form.tnTelefono"
            type="text"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el teléfono"
          />
        </div>

        <div class="flex flex-col">
          <label for="tcCiNit" class="text-gray-700 font-medium mb-2">CI/NIT:</label>
          <input
            id="tcCiNit"
            v-model="form.tcCiNit"
            type="text"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="Ingrese el CI/NIT"
          />
        </div>

        <div class="flex flex-col">
          <label for="tnMonto" class="text-gray-700 font-medium mb-2">Monto Total:</label>
          <input
    id="tnMonto"
    v-model="form.tnMonto"
    type="number"
    step="0.01" 
    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
    placeholder="Monto"
    min="0.01" 
  />
        </div>

        <div class="flex flex-col">
          <label for="tnTipoServicio" class="text-gray-700 font-medium mb-2">Tipo de Servicio:</label>
          <select
            id="tnTipoServicio"
            v-model="form.tnTipoServicio"
            class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
          >
            <option value="1">Servicio QR</option>
            <option value="2">Tigo Money</option>
          </select>
        </div>

        <div>
          <button
            type="submit"
            class="w-full py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-200"
          >
            Generar Pago
          </button>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
input, select {
  transition: all 0.3s ease;
}

input:focus, select:focus {
  border-color: #2563eb; 
  box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.3);
}
</style>
