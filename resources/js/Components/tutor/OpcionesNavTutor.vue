<script setup>
defineProps({
    
});
</script>

<template>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('tutor.inicio')">
        <i class="fas fa-home mr-2"></i>Inicio
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('tutor.calificaciones')">
            <i class="fa fa-star" aria-hidden="true"></i> Calificaciones
    </a>
    <a class="block text-black py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-cyan-500 hover:text-white"
        :href="route('tutor.perfil')">
        <i class="fas fa-user mr-2"></i>Perfil
    </a>
</template>
