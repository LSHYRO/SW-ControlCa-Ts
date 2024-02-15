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
<div class="bg-cyan-600 text-white shadow w-full flex items-center justify-between md:max-h-12 h-16 rounded-sm">
    <div class="flex items-center h-full">
        <div class="md:hidden flex items-center pe-2 mx-1"> <!-- Se muestra solo en dispositivos pequeños -->
            <button id="menuBtn" @click="toggleSidebar">
                <i class="fas fa-bars text-white text-lg"></i> <!-- Ícono de menú -->
            </button>
        </div>
        <div class="h-full">
            <img src="https://www.nvinoticias.com/sites/default/files/styles/normal_size/public/articulos/2023/Mayo/ieepo.png.webp?itok=lCgzpmJN"
                alt="IEEPO Logo" class="md:h-full h-14 md:mx-1 border-2 border-cyan-600 object-cover rounded-lg">
        </div>
        <div class="md:flex items-center justify-center text-center hidden"> <!-- Mostrado en todos los dispositivos -->
            <h1 class="md:font-bold md:text-2xl font-semibold text-xl">Telesecundaria clave: 20DTV1474D</h1>
        </div>
        <div class="flex items-center justify-center text-center md:hidden"> <!-- Mostrado en todos los dispositivos -->
            <h1 class="md:font-bold md:text-2xl font-semibold text-lg">TS 20DTV1474D</h1>
        </div>
    </div>
    <div class="flex items-center h-full">
        <div class="flex items-center text-center h-full justify-center mx-1">
            <i class="fas fa-user text-white font-thin mx-1"></i>
            <i class="text-white font-['DM Sans'] mx-1"> {{ props.usuario.tipoUsuario6.charAt(0).toUpperCase() + props.usuario.tipoUsuario6.slice(1) }} </i>
        </div>
        <div class="h-full">
            <img src="https://seeklogo.com/images/T/telesecundaria-logo-44765C9421-seeklogo.com.png"
            alt="Telesecundaria Logo" class="md:h-full h-14 md:mx-1 object-cover rounded-lg border-2 border-cyan-600">
        </div>
    </div>
</div>
</template>