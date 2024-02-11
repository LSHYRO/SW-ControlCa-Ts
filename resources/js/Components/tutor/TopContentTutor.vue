<script setup>
import { ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    usuario: { type: Object }
});

const page = usePage();
const show = ref(true);
const style = ref('success');
const message = ref('');

const toggleSidebar = () => {
            sideNav.classList.toggle('hidden'); // Agrega o quita la clase 'hidden' para mostrar u ocultar la navegación lateral
        }

watchEffect(async () => {
    style.value = page.props.jetstream.flash?.bannerStyle || 'success';
    message.value = page.props.jetstream.flash?.banner || '';
    show.value = true;
});
</script>

<template>
<!-- Barra de navegación superior -->
<div class="bg-cyan-600 text-white shadow w-full p-2 flex items-center justify-between">
    <div class="flex items-center">
        <div class="md:hidden flex items-center pe-2"> <!-- Se muestra solo en dispositivos pequeños -->
            <button id="menuBtn" @click="toggleSidebar">
                <i class="fas fa-bars text-white text-lg"></i> <!-- Ícono de menú -->
            </button>
        </div>
        <div class="flex items-center"> <!-- Mostrado en todos los dispositivos -->
            <h1 class="font-bold text-2xl">Telesecundaria clave: 20DTV1474D</h1>
        </div>

    </div>

    <!-- Ícono de Notificación y Perfil -->
    <div class="space-x-5">
        <!-- Botón de Perfil -->
        <div class="text-center">
            <i class="fas fa-user text-white font-thin font-['DM Sans']"></i>
            <i class="text-white font-['DM Sans'] m-3"> {{ props.usuario.tipoUsuario6.charAt(0).toUpperCase() + props.usuario.tipoUsuario6.slice(1) }} </i>
        </div>
    </div>
</div>
</template>