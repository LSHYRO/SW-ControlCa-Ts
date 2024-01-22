<script setup>
import { ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    usuario: {type: Object}
});

const page = usePage();
const show = ref(true);
const style = ref('success');
const message = ref('');

const tipo_usuario = ref('');

const toggleSidebar = () => {
    sideNav.classList.toggle('hidden'); // Agrega o quita la clase 'hidden' para mostrar u ocultar la navegación lateral
}

watchEffect(async () => {
    style.value = page.props.jetstream.flash?.bannerStyle || 'success';
    message.value = page.props.jetstream.flash?.banner || '';
    show.value = true;
});

onMounted(async () => {
    try {
        const usuario = await axios.get(route('obtenerUsuario'));
        const idTipoUsuario = usuario.data.idTipoUsuario;

        const tipoUsuario = await axios.get(route('obtenerTipoUsuario', idTipoUsuario));
        const datosTipoUsuario = tipoUsuario.data.tipoUsuario;
        tipo_usuario.value = datosTipoUsuario;

    } catch (e) {
        tipo_usuario.value = "Sin asignar";
        console.log("Error: " + e);
    }
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
            <div>
                <i class="fas fa-user text-white font-thin font-['DM Sans']"></i>
                <i class="text-white font-['DM Sans']">  {{ " " + props.usuario.tipoUsuario1}} </i>
            </div>
        </div>
    </div>
</template>