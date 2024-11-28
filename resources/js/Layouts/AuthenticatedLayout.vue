<script setup>
import { ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Footer from '@/Components/Footer.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const { auth } = usePage().props;
const isGestorOrAdmin = ['gestor', 'admin'].includes(auth.user.role);

const showingNavigationDropdown = ref(false);

// Notificaciones
const notifications = ref([]);
const unreadCount = ref(0);
const showNotifications = ref(false);

const fetchNotifications = async () => {
  try {
    const response = await axios.get('/notifications');
    notifications.value = response.data.notifications;
    unreadCount.value = response.data.unread_count;
  } catch (error) {
    console.error('Error al obtener notificaciones:', error);
  }
};

const markAllAsRead = async () => {
  try {
    await axios.post('/notifications/mark-as-read');
    unreadCount.value = 0;
    await fetchNotifications();
  } catch (error) {
    console.error('Error al marcar notificaciones como leídas:', error);
  }
};

onMounted(fetchNotifications);
</script>

<template>
  <div>
    <div class="min-h-screen bg-gray-100 flex flex-col">
      <nav class="border-b border-gray-100 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex h-16 justify-between">
            <!-- Logo y Links de Navegación -->
            <div class="flex">
              <!-- Logo -->
              <div class="flex shrink-0 items-center">
                <Link :href="route('dashboard')">
                  <img src="/storage/Honda-Logo.png" alt="Honda Logo" class="h-20 w-20 object-contain" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                <NavLink
                  :href="route('dashboard')"
                  :active="route().current('dashboard')"
                >
                  Dashboard
                </NavLink>
                <NavLink
                  v-if="isGestorOrAdmin"
                  :href="route('users.index')"
                  :active="route().current('users.index')"
                >
                  Gestionar usuarios
                </NavLink>
                <NavLink
                  :href="route('tramites.index')"
                  :active="route().current('tramites.index')"
                >
                  Gestionar trámites
                </NavLink>
                <NavLink
                  :href="route('placas.index')"
                  :active="route().current('placas.index')"
                >
                  Gestionar placas
                </NavLink>
              </div>
            </div>

            <!-- Notificaciones y Dropdown -->
            <div class="hidden sm:ms-6 sm:flex sm:items-center">
              <!-- Notificaciones -->
              <div class="relative me-4">
                <button
                  @click="showNotifications = !showNotifications"
                  class="relative text-gray-500 hover:text-gray-700"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
                  </svg>
                  <span
                    v-if="unreadCount > 0"
                    class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full"
                  >
                    {{ unreadCount }}
                  </span>
                </button>
                <!-- Dropdown de Notificaciones -->
                <div
                  v-if="showNotifications"
                  class="absolute right-0 mt-2 w-64 bg-white shadow-lg rounded-lg z-10"
                >
                  <ul>
                    <li
                      v-for="notification in notifications"
                      :key="notification.id"
                      class="px-4 py-2 border-b"
                    >
                      <p class="text-sm text-gray-700">
                        {{ notification.data.message }}
                      </p>
                      <p class="text-xs text-gray-500">{{ notification.created_at }}</p>
                    </li>
                  </ul>
                  <button
                    @click="markAllAsRead"
                    class="w-full text-center py-2 text-sm text-blue-500 hover:underline"
                  >
                    Marcar todas como leídas
                  </button>
                </div>
              </div>

              <!-- Dropdown de Usuario -->
              <Dropdown align="right" width="48">
                <template #trigger>
                  <span class="inline-flex rounded-md">
                    <button
                      type="button"
                      class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                    >
                      {{ auth.user.name }}
                      <svg
                        class="-me-0.5 ms-2 h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 20 20"
                        fill="currentColor"
                      >
                        <path
                          fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd"
                        />
                      </svg>
                    </button>
                  </span>
                </template>
                <template #content>
                  <DropdownLink :href="route('profile.edit')">Perfil</DropdownLink>
                  <DropdownLink :href="route('logout')" method="post" as="button">Cerrar sesión</DropdownLink>
                </template>
              </Dropdown>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-grow">
        <slot />
      </main>

      <!-- Footer -->
      <Footer :page-url="$page.url" />
    </div>
  </div>
</template>
