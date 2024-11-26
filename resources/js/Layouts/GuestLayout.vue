<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
    <div class="flex flex-col items-center justify-center space-y-2 py-6">
        <Link href="/" class="group flex flex-col items-center">
            <!-- Logo -->
            <img 
                src="/storage/Honda-Logo.png" 
                alt="Honda Logo" 
                class="h-28 w-28 object-contain transition-transform duration-300 group-hover:scale-110" 
            />
            <!-- Texto -->
            <span class="text-center text-xl md:text-2xl font-extrabold tracking-wide text-gray-800 group-hover:text-orange-500 transition-colors duration-300">
                Visal Import Export S.A.
            </span>
        </Link>
    </div>


        <div
            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
